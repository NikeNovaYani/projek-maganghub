<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrganisasiAnggotaModel;
use App\Models\OrganisasiModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;

class Organisasi extends BaseController
{
    private const NODE_TYPES = [
        'kepala'  => 'Kepala',
        'penjab'  => 'Penjab',
        'katim'   => 'Ka Tim',
        'admin'   => 'Administrasi',
        'tim'     => 'Tim',
    ];

    public function index()
    {
        $model = new OrganisasiModel();
        $tree = $model->getTree();
        $attachMembers = function (array &$nodes) use (&$attachMembers, $model): void {
            foreach ($nodes as &$node) {
                if ($node['node_type'] === 'tim') {
                    $node['anggota'] = $model->getGroupMembers((int) $node['id']);
                }
                $attachMembers($node['children']);
            }
            unset($node);
        };
        $attachMembers($tree);

        return view('admin/organisasi/index', ['tree' => $tree]);
    }

    public function create()
    {
        $model = new OrganisasiModel();
        $parentId = filter_var($this->request->getGet('parent_id'), FILTER_VALIDATE_INT);
        $parent = $parentId ? $model->getProfile($parentId) : null;
        $nodeType = (string) $this->request->getGet('node_type');
        
        if (! isset(self::NODE_TYPES[$nodeType])) {
            if ($parent === null) {
                $nodeType = 'kepala';
            } elseif ((int) $parent['level'] === 1) {
                $nodeType = 'penjab';
            } elseif ((int) $parent['level'] === 2) {
                $nodeType = 'katim';
            } elseif ((int) $parent['level'] === 3) {
                $nodeType = 'tim';
            } else {
                $nodeType = 'pejabat';
            }
        }

        return view('admin/organisasi/form', [
            'mode' => 'create',
            'position' => [
                'level' => $parent === null ? 1 : (int) $parent['level'] + 1,
                'node_type' => $nodeType,
                'layout_type' => 'main',
                'urutan' => 0,
                'parent_id' => $parentId ?: null,
            ],
            'positions' => $this->allPositions(),
            'nodeTypes' => self::NODE_TYPES,
            'action' => base_url('admin/organisasi/store'),
        ]);
    }

    public function store()
    {
        $model = new OrganisasiModel();
        $data = $this->formData();
        if ($data['node_type'] === 'tim') {
            $data['nama'] = $data['jabatan'];
        }
        $data['layout_type'] = $data['node_type'] === 'admin' ? 'side' : 'main';
        $data['urutan'] = $this->nextOrder($data['parent_id']);

        if ($error = $this->validateNode($data)) {
            return $this->formError($error);
        }

        if ($errors = $this->validatePhoto($this->request->getFile('foto'))) {
            return $this->formError($errors);
        }

        if (! $model->validate($data)) {
            return $this->formError($model->errors());
        }

        $newPhoto = $this->storePhoto($this->request->getFile('foto'));
        if ($newPhoto === false) {
            return $this->formError(['foto' => 'Foto gagal disimpan. Silakan coba kembali.']);
        }

        $user = auth()->user();
        $data['foto'] = $newPhoto;
        $data['created_by'] = $user?->id;
        $data['updated_by'] = $user?->id;

        if (! $model->insert($data)) {
            $this->removePhoto($newPhoto);
            return $this->formError($model->errors());
        }

        return redirect()->to('admin/organisasi')->with('message', 'Posisi berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $model = new OrganisasiModel();
        $position = $model->find($id);
        if ($position === null) {
            throw PageNotFoundException::forPageNotFound();
        }
        $positions = array_values(array_filter(
            $this->allPositions(),
            static fn (array $candidate): bool => (int) $candidate['id'] !== $id && ! $model->isInSubtree((int) $candidate['id'], $id),
        ));

        return view('admin/organisasi/form', [
            'mode' => 'edit',
            'position' => $position,
            'positions' => $positions,
            'nodeTypes' => self::NODE_TYPES,
            'action' => base_url('admin/organisasi/update/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new OrganisasiModel();
        $position = $model->find($id);
        if ($position === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = $this->formData();
        if ($position['node_type'] !== $data['node_type'] && $model->hasChildren($id)) {
            return $this->formError(['node_type' => 'Tipe node tidak dapat diubah selama posisi ini masih memiliki bawahan.']);
        }
        if ($position['node_type'] === 'tim' && $data['node_type'] !== 'tim' && $model->getGroupMemberCount($id) > 0) {
            return $this->formError(['node_type' => 'Tipe tim tidak dapat diubah selama masih memiliki anggota.']);
        }
        if ($data['node_type'] === 'tim') {
            $data['nama'] = $data['jabatan'];
        }
        $data['layout_type'] = $data['node_type'] === 'admin' ? 'side' : 'main';
        $data['urutan'] = (int) $position['urutan'];
        if ($data['parent_id'] !== ($position['parent_id'] === null ? null : (int) $position['parent_id'])) {
            $data['urutan'] = $this->nextOrder($data['parent_id']);
        }
        if ($error = $this->validateNode($data, $id)) {
            return $this->formError($error);
        }
        if ($errors = $this->validatePhoto($this->request->getFile('foto'))) {
            return $this->formError($errors);
        }
        if (! $model->validate($data)) {
            return $this->formError($model->errors());
        }

        $uploaded = $this->request->getFile('foto');
        $newPhoto = $this->storePhoto($uploaded);
        if ($newPhoto === false) {
            return $this->formError(['foto' => 'Foto gagal disimpan. Silakan coba kembali.']);
        }

        $user = auth()->user();
        $data['updated_by'] = $user?->id;
        $data['foto'] = $newPhoto ?? $position['foto'];

        if (! $model->update($id, $data)) {
            if ($newPhoto !== null) {
                $this->removePhoto($newPhoto);
            }
            return $this->formError($model->errors());
        }

        if ($newPhoto !== null && ! empty($position['foto'])) {
            $this->removePhoto($position['foto']);
        }

        return redirect()->to('admin/organisasi')->with('message', 'Posisi berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $model = new OrganisasiModel();
        $position = $model->find($id);
        if ($position === null) {
            return redirect()->to('admin/organisasi')->with('error', 'Posisi tidak ditemukan.');
        }
        if ($model->hasChildren($id)) {
            return redirect()->to('admin/organisasi')->with('delete_warning', 'Posisi ini tidak dapat dihapus karena masih memiliki bawahan. Silakan pindahkan atau hapus posisi bawahannya terlebih dahulu.');
        }
        $memberCount = $position['node_type'] === 'tim' ? $model->getGroupMemberCount($id) : 0;
        if ($memberCount > 0) {
            return redirect()->to('admin/organisasi')->with('delete_warning', 'Tim ini masih memiliki ' . $memberCount . ' staf. Pindahkan atau hapus anggotanya terlebih dahulu.');
        }

        if (! $model->delete($id)) {
            return redirect()->to('admin/organisasi')->with('error', 'Posisi gagal dihapus.');
        }

        if (! empty($position['foto'])) {
            $this->removePhoto($position['foto']);
        }

        return redirect()->to('admin/organisasi')->with('message', 'Posisi berhasil dihapus.');
    }

    public function move(int $id, string $direction)
    {
        if (! in_array($direction, ['up', 'down'], true)) {
            return redirect()->to('admin/organisasi')->with('error', 'Arah pengurutan tidak valid.');
        }

        $model = new OrganisasiModel();
        $node = $model->find($id);
        if ($node === null) {
            return redirect()->to('admin/organisasi')->with('error', 'Posisi tidak ditemukan.');
        }

        $siblingsQuery = $model->where('parent_id', $node['parent_id']);
        $siblings = $siblingsQuery->orderBy('urutan', 'ASC')->orderBy('id', 'ASC')->findAll();
        $currentIndex = array_search($id, array_column($siblings, 'id'), false);
        $targetIndex = $currentIndex + ($direction === 'up' ? -1 : 1);
        if ($currentIndex === false || ! isset($siblings[$targetIndex])) {
            return redirect()->to('admin/organisasi')->with('message', 'Posisi sudah berada di urutan tersebut.');
        }

        $db = db_connect();
        $movingNode = array_splice($siblings, $currentIndex, 1)[0];
        array_splice($siblings, $targetIndex, 0, [$movingNode]);
        $userId = auth()->user()?->id;
        $timestamp = date('Y-m-d H:i:s');
        $db->transBegin();
        foreach ($siblings as $index => $sibling) {
            if (! $db->table('organisasi')->where('id', $sibling['id'])->update([
                'urutan' => $index + 1,
                'updated_by' => $userId,
                'updated_at' => $timestamp,
            ])) {
                $db->transRollback();
                return redirect()->to('admin/organisasi')->with('error', 'Urutan posisi gagal diperbarui.');
            }
        }
        if (! $db->transStatus()) {
            $db->transRollback();
            return redirect()->to('admin/organisasi')->with('error', 'Urutan posisi gagal diperbarui.');
        }
        $db->transCommit();

        return redirect()->to('admin/organisasi')->with('message', 'Urutan posisi berhasil diperbarui.');
    }

    public function createMember(int $groupId)
    {
        $group = (new OrganisasiModel())->find($groupId);
        if ($group === null || $group['node_type'] !== 'tim') {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('admin/organisasi/member_form', [
            'mode' => 'create',
            'group' => $group,
            'member' => ['organisasi_id' => $groupId, 'urutan' => 0],
            'action' => base_url('admin/organisasi/anggota/store/' . $groupId),
        ]);
    }

    public function storeMember(int $groupId)
    {
        $group = (new OrganisasiModel())->find($groupId);
        if ($group === null || $group['node_type'] !== 'tim') {
            throw PageNotFoundException::forPageNotFound();
        }

        $model = new OrganisasiAnggotaModel();
        $data = $this->memberFormData($groupId);
        $data['urutan'] = $this->nextMemberOrder($groupId);
        if ($errors = $this->validatePhoto($this->request->getFile('foto'))) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }
        if (! $model->validate($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        $photo = $this->storePhoto($this->request->getFile('foto'));
        if ($photo === false) {
            return redirect()->back()->withInput()->with('errors', ['foto' => 'Foto gagal disimpan.']);
        }
        $user = auth()->user();
        $data['foto'] = $photo;
        $data['created_by'] = $user?->id;
        $data['updated_by'] = $user?->id;
        if (! $model->insert($data)) {
            $this->removePhoto($photo);
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('admin/organisasi')->with('message', 'Anggota tim berhasil ditambahkan.');
    }

    public function editMember(int $groupId, int $memberId)
    {
        $group = (new OrganisasiModel())->find($groupId);
        $member = (new OrganisasiAnggotaModel())->where('organisasi_id', $groupId)->find($memberId);
        if ($group === null || $group['node_type'] !== 'tim' || $member === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('admin/organisasi/member_form', [
            'mode' => 'edit',
            'group' => $group,
            'member' => $member,
            'action' => base_url('admin/organisasi/anggota/update/' . $groupId . '/' . $memberId),
        ]);
    }

    public function updateMember(int $groupId, int $memberId)
    {
        $model = new OrganisasiAnggotaModel();
        $member = $model->where('organisasi_id', $groupId)->find($memberId);
        if ($member === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = $this->memberFormData($groupId);
        if ($errors = $this->validatePhoto($this->request->getFile('foto'))) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }
        if (! $model->validate($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        $photo = $this->storePhoto($this->request->getFile('foto'));
        if ($photo === false) {
            return redirect()->back()->withInput()->with('errors', ['foto' => 'Foto gagal disimpan.']);
        }
        $user = auth()->user();
        $data['updated_by'] = $user?->id;
        $data['foto'] = $photo ?? $member['foto'];
        if (! $model->update($memberId, $data)) {
            if ($photo !== null) {
                $this->removePhoto($photo);
            }
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
        if ($photo !== null && ! empty($member['foto'])) {
            $this->removePhoto($member['foto']);
        }

        return redirect()->to('admin/organisasi')->with('message', 'Anggota tim berhasil diperbarui.');
    }

    public function deleteMember(int $groupId, int $memberId)
    {
        $model = new OrganisasiAnggotaModel();
        $member = $model->where('organisasi_id', $groupId)->find($memberId);
        if ($member === null) {
            return redirect()->to('admin/organisasi')->with('error', 'Anggota tidak ditemukan.');
        }
        if (! $model->delete($memberId)) {
            return redirect()->to('admin/organisasi')->with('error', 'Anggota gagal dihapus.');
        }
        if (! empty($member['foto'])) {
            $this->removePhoto($member['foto']);
        }

        return redirect()->to('admin/organisasi')->with('message', 'Anggota tim berhasil dihapus.');
    }

    private function formData(): array
    {
        $parentId = $this->request->getPost('parent_id');
        $nodeType = $this->request->getPost('node_type');
        $name = $this->request->getPost('nama');
        $jobTitle = $this->request->getPost('jabatan');
        $parentId = is_scalar($parentId) && ctype_digit((string) $parentId) && (int) $parentId > 0
            ? (int) $parentId
            : (($parentId === null || $parentId === '') ? null : 0);

        return [
            'parent_id' => $parentId,
            'node_type' => is_string($nodeType) && isset(self::NODE_TYPES[$nodeType]) ? $nodeType : '',
            'nama' => is_scalar($name) ? trim((string) $name) : '',
            'jabatan' => is_scalar($jobTitle) ? trim((string) $jobTitle) : '',
        ];
    }

    private function validateNode(array &$data, ?int $excludeId = null): array
    {
        $parentId = $data['parent_id'];
        $nodeType = $data['node_type'];
        $model = new OrganisasiModel();
        $parent = $parentId === null ? null : $model->find($parentId);

        $allowedParentTypes = ['kepala', 'penjab', 'katim', 'pejabat'];
        if ($parentId !== null && ($parent === null || ! in_array($parent['node_type'], $allowedParentTypes, true))) {
            return ['parent_id' => 'Atasan harus berupa node Pimpinan/Pejabat yang dapat memiliki bawahan.'];
        }
        if ($excludeId !== null && ($parentId === $excludeId || ($parentId !== null && $model->isInSubtree($parentId, $excludeId)))) {
            return ['parent_id' => 'Atasan tidak boleh berupa node ini atau turunannya.'];
        }

        $level = $parent === null ? 1 : (int) $parent['level'] + 1;
        $data['level'] = $level;
        $data['layout_type'] = $nodeType === 'admin' ? 'side' : 'main';

        if ($excludeId !== null && $model->hasChildren($excludeId)) {
            $current = $model->find($excludeId);
            if ($current !== null && (int) $current['level'] !== $level) {
                return ['parent_id' => 'Tingkat node tidak dapat diubah selama masih memiliki bawahan.'];
            }
        }

        if (in_array($nodeType, ['kepala', 'penjab', 'katim', 'pejabat'], true) && ($parentId !== null && $level > 3)) {
            return ['node_type' => 'Pejabat/Pimpinan hanya dapat dibuat sampai tingkat ketiga.'];
        }
        if ($nodeType === 'admin' && ($parentId === null || $level !== 2)) {
            return ['node_type' => 'Posisi Administrasi harus menjadi anak langsung Pimpinan.'];
        }
        if ($nodeType === 'tim' && ($parent === null || $level !== 4)) {
            return ['node_type' => 'Tim harus berada di bawah Pejabat tingkat ketiga.'];
        }
        if ($nodeType === '' || ! isset(self::NODE_TYPES[$nodeType])) {
            return ['node_type' => 'Tipe node tidak valid.'];
        }

        return [];
    }

    private function memberFormData(int $groupId): array
    {
        $name = $this->request->getPost('nama');
        $jobTitle = $this->request->getPost('jabatan');

        return [
            'organisasi_id' => $groupId,
            'nama' => is_scalar($name) ? trim((string) $name) : '',
            'jabatan' => is_scalar($jobTitle) ? trim((string) $jobTitle) : '',
            'urutan' => 0,
        ];
    }

    private function nextMemberOrder(int $groupId): int
    {
        $row = db_connect()->table('organisasi_anggota')
            ->selectMax('urutan', 'max_urutan')
            ->where('organisasi_id', $groupId)
            ->get()
            ->getRowArray();

        return (int) ($row['max_urutan'] ?? 0) + 1;
    }

    private function validatePhoto(?UploadedFile $photo): array
    {
        if ($photo === null || $photo->getError() === UPLOAD_ERR_NO_FILE) {
            return [];
        }
        if (! $photo->isValid() || $photo->hasMoved()) {
            return ['foto' => 'Upload foto tidak valid.'];
        }

        $extension = strtolower($photo->getExtension());
        $mimeType = $photo->getMimeType();
        $imageInfo = @getimagesize($photo->getTempName());
        if (! in_array($extension, ['png', 'jpg', 'jpeg'], true)
            || ! in_array($mimeType, ['image/png', 'image/jpeg'], true)
            || $imageInfo === false
            || $photo->getSize() > 4 * 1024 * 1024
        ) {
            return ['foto' => 'Foto harus berupa PNG/JPG/JPEG yang valid dan maksimal 4 MB.'];
        }

        return [];
    }

    private function storePhoto(?UploadedFile $photo): string|false|null
    {
        if ($photo === null || $photo->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $directory = FCPATH . 'uploads/organisasi';
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            return false;
        }

        $name = $photo->getRandomName();
        try {
            return $photo->move($directory, $name) ? $name : false;
        } catch (\Throwable $exception) {
            log_message('error', 'Upload foto organisasi gagal: {message}', ['message' => $exception->getMessage()]);
            return false;
        }
    }

    private function removePhoto(string $name): void
    {
        $path = FCPATH . 'uploads/organisasi/' . basename($name);
        if (is_file($path)) {
            unlink($path);
        }
    }

    private function allPositions(): array
    {
        return (new OrganisasiModel())
            ->select('id, level, node_type, nama, jabatan, urutan')
            ->whereIn('node_type', ['kepala', 'penjab', 'katim', 'pejabat'])
            ->orderBy('level', 'ASC')
            ->orderBy('urutan', 'ASC')
            ->orderBy('nama', 'ASC')
            ->findAll();
    }

    private function nextOrder(?int $parentId): int
    {
        $builder = db_connect()->table('organisasi')->selectMax('urutan', 'max_urutan');
        if ($parentId === null) {
            $builder->where('parent_id', null);
        } else {
            $builder->where('parent_id', $parentId);
        }

        return (int) ($builder->get()->getRowArray()['max_urutan'] ?? 0) + 1;
    }

    private function formError(array|string $errors)
    {
        $positionId = $this->request->getPost('id');
        $url = $positionId ? 'admin/organisasi/edit/' . $positionId : 'admin/organisasi/create';

        return redirect()->to($url)->withInput()->with('errors', $errors);
    }
}