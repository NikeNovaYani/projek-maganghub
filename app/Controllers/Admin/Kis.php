<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KisAplikasiModel;
use App\Models\KisKategoriModel;

class Kis extends BaseController
{
    public function index()
    {
        $data = (new KisKategoriModel())->getAllWithApps();

        return view('admin/kis/index', [
            'kategori'      => $data['kategori'],
            'tanpaKategori' => $data['tanpa_kategori'],
            'klasifikasi'   => KisAplikasiModel::KLASIFIKASI,
        ]);
    }

    /* ===================== KATEGORI ===================== */

    public function storeKategori()
    {
        $model = new KisKategoriModel();
        $data = $this->kategoriFormData();
        $userId = auth()->user()?->id;

        $data['slug'] = $model->uniqueSlug($data['nama']);
        $data['urutan'] = $model->nextOrder();
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        if (! $model->insert($data)) {
            return $this->formError($model->errors(), ['type' => 'kategori', 'id' => null]);
        }

        return $this->backTo($data['slug'])->with('message', 'Kategori berhasil ditambahkan.');
    }

    public function updateKategori(int $id)
    {
        $model = new KisKategoriModel();
        $kategori = $model->find($id);
        if ($kategori === null) {
            return $this->backTo('')->with('error', 'Kategori tidak ditemukan.');
        }

        $data = $this->kategoriFormData();
        $data['updated_by'] = auth()->user()?->id;

        if (! $model->update($id, $data)) {
            return $this->formError($model->errors(), ['type' => 'kategori', 'id' => $id]);
        }

        return $this->backTo($kategori['slug'])->with('message', 'Kategori berhasil diperbarui.');
    }

    public function deleteKategori(int $id)
    {
        $model = new KisKategoriModel();
        $kategori = $model->find($id);
        if ($kategori === null) {
            return $this->backTo('')->with('error', 'Kategori tidak ditemukan.');
        }

        if ($model->hasApps($id)) {
            return $this->backTo($kategori['slug'])->with('error', 'Kategori "' . $kategori['nama'] . '" tidak dapat dihapus karena masih memiliki aplikasi. Pindahkan atau hapus aplikasinya terlebih dahulu.');
        }

        if (! $model->delete($id)) {
            return $this->backTo($kategori['slug'])->with('error', 'Kategori gagal dihapus.');
        }

        return $this->backTo('')->with('message', 'Kategori berhasil dihapus.');
    }

    public function moveKategori(int $id, string $direction)
    {
        if (! in_array($direction, ['up', 'down'], true)) {
            return $this->backTo()->with('error', 'Arah pengurutan tidak valid.');
        }

        $rows = (new KisKategoriModel())->orderBy('urutan', 'ASC')->orderBy('id', 'ASC')->findAll();
        $ids = array_map('intval', array_column($rows, 'id'));

        $index = array_search($id, $ids, true);
        if ($index === false) {
            return $this->backTo('')->with('error', 'Kategori tidak ditemukan.');
        }

        $target = $index + ($direction === 'up' ? -1 : 1);
        if (! isset($ids[$target])) {
            return $this->backTo()->with('message', 'Kategori sudah berada di urutan tersebut.');
        }

        [$ids[$index], $ids[$target]] = [$ids[$target], $ids[$index]];

        if (! $this->saveOrder('kis_kategori', $ids)) {
            return $this->backTo()->with('error', 'Urutan kategori gagal diperbarui.');
        }

        return $this->backTo()->with('message', 'Urutan kategori berhasil diperbarui.');
    }

    /* ===================== APLIKASI ===================== */

    public function storeApp()
    {
        $model = new KisAplikasiModel();
        $data = $this->appFormData();
        $kategoriId = $data['kategori_id'];

        if ($data['nama_aplikasi'] !== '' && $kategoriId > 0 && $model->nameExists($kategoriId, $data['nama_aplikasi'])) {
            return $this->formError(['nama_aplikasi' => 'Aplikasi dengan nama tersebut sudah ada di kategori ini.'], ['type' => 'app', 'id' => null]);
        }

        $userId = auth()->user()?->id;
        $data['urutan'] = $model->nextOrder($kategoriId > 0 ? $kategoriId : null);
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        if (! $model->insert($data)) {
            return $this->formError($model->errors(), ['type' => 'app', 'id' => null]);
        }

        return $this->backTo($this->slugOf($kategoriId))->with('message', 'Aplikasi berhasil ditambahkan.');
    }

    public function updateApp(int $id)
    {
        $model = new KisAplikasiModel();
        $app = $model->find($id);
        if ($app === null) {
            return $this->backTo()->with('error', 'Aplikasi tidak ditemukan.');
        }

        $data = $this->appFormData();
        $kategoriId = $data['kategori_id'];
        $oldKategoriId = empty($app['kategori_id']) ? 0 : (int) $app['kategori_id'];

        if ($data['nama_aplikasi'] !== '' && $kategoriId > 0 && $model->nameExists($kategoriId, $data['nama_aplikasi'], $id)) {
            return $this->formError(['nama_aplikasi' => 'Aplikasi dengan nama tersebut sudah ada di kategori ini.'], ['type' => 'app', 'id' => $id]);
        }

        // Pindah kategori: taruh di urutan terakhir kategori baru
        if ($kategoriId !== $oldKategoriId && $kategoriId > 0) {
            $data['urutan'] = $model->nextOrder($kategoriId);
        }
        $data['updated_by'] = auth()->user()?->id;

        if (! $model->update($id, $data)) {
            return $this->formError($model->errors(), ['type' => 'app', 'id' => $id]);
        }

        return $this->backTo($this->slugOf($kategoriId))->with('message', 'Aplikasi berhasil diperbarui.');
    }

    public function deleteApp(int $id)
    {
        $model = new KisAplikasiModel();
        $app = $model->find($id);
        if ($app === null) {
            return $this->backTo()->with('error', 'Aplikasi tidak ditemukan.');
        }

        if (! $model->delete($id)) {
            return $this->backTo()->with('error', 'Aplikasi gagal dihapus.');
        }

        return $this->backTo()->with('message', 'Aplikasi berhasil dihapus.');
    }

    public function moveApp(int $id, string $direction)
    {
        if (! in_array($direction, ['up', 'down'], true)) {
            return $this->backTo()->with('error', 'Arah pengurutan tidak valid.');
        }

        $model = new KisAplikasiModel();
        $app = $model->find($id);
        if ($app === null) {
            return $this->backTo()->with('error', 'Aplikasi tidak ditemukan.');
        }

        // Urutan hanya berlaku di dalam satu kategori (atau di grup "belum dikelompokkan")
        $rows = $model->where('kategori_id', empty($app['kategori_id']) ? null : $app['kategori_id'])
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
        $ids = array_map('intval', array_column($rows, 'id'));

        $index = array_search($id, $ids, true);
        $target = $index === false ? false : $index + ($direction === 'up' ? -1 : 1);
        if ($index === false || ! isset($ids[$target])) {
            return $this->backTo()->with('message', 'Aplikasi sudah berada di urutan tersebut.');
        }

        [$ids[$index], $ids[$target]] = [$ids[$target], $ids[$index]];

        if (! $this->saveOrder('kis_aplikasi', $ids)) {
            return $this->backTo()->with('error', 'Urutan aplikasi gagal diperbarui.');
        }

        return $this->backTo()->with('message', 'Urutan aplikasi berhasil diperbarui.');
    }

    public function toggleApp(int $id)
    {
        $model = new KisAplikasiModel();
        $app = $model->find($id);
        if ($app === null) {
            return $this->backTo()->with('error', 'Aplikasi tidak ditemukan.');
        }

        $aktif = (int) $app['is_aktif'] === 1 ? 0 : 1;
        if (! $model->update($id, ['is_aktif' => $aktif, 'updated_by' => auth()->user()?->id])) {
            return $this->backTo()->with('error', 'Status aplikasi gagal diperbarui.');
        }

        return $this->backTo()->with('message', $aktif === 1
            ? 'Aplikasi sekarang tampil di website publik.'
            : 'Aplikasi disembunyikan dari website publik.');
    }

    /* ===================== HELPER ===================== */

    private function kategoriFormData(): array
    {
        $nama = $this->request->getPost('nama');

        return [
            'nama'      => is_scalar($nama) ? trim((string) $nama) : '',
            'deskripsi' => $this->cleanText($this->request->getPost('deskripsi')),
        ];
    }

    private function appFormData(): array
    {
        $nama = $this->request->getPost('nama_aplikasi');
        $klasifikasi = $this->request->getPost('klasifikasi');

        return [
            'kategori_id'       => (int) $this->request->getPost('kategori_id'),
            'nama_aplikasi'     => is_scalar($nama) ? trim((string) $nama) : '',
            'deskripsi_singkat' => $this->cleanText($this->request->getPost('deskripsi_singkat')),
            'url'               => $this->cleanText($this->request->getPost('url')),
            'klasifikasi'       => is_string($klasifikasi) && isset(KisAplikasiModel::KLASIFIKASI[$klasifikasi]) ? $klasifikasi : null,
            'akses_internal'    => $this->request->getPost('akses_internal') ? 1 : 0,
        ];
    }

    // Teks kosong disimpan sebagai NULL
    private function cleanText(mixed $value): ?string
    {
        $text = is_scalar($value) ? trim((string) $value) : '';

        return $text === '' ? null : $text;
    }

    private function slugOf(int $kategoriId): string
    {
        if ($kategoriId <= 0) {
            return '';
        }

        $kategori = (new KisKategoriModel())->find($kategoriId);

        return $kategori['slug'] ?? '';
    }

    // Simpan urutan baru (1..n) sesuai susunan id, dalam satu transaksi
    private function saveOrder(string $table, array $orderedIds): bool
    {
        $db = db_connect();
        $userId = auth()->user()?->id;
        $now = date('Y-m-d H:i:s');

        $db->transStart();
        foreach ($orderedIds as $position => $rowId) {
            $db->table($table)->where('id', $rowId)->update([
                'urutan'     => $position + 1,
                'updated_by' => $userId,
                'updated_at' => $now,
            ]);
        }
        $db->transComplete();

        return $db->transStatus();
    }

    // Kembali ke halaman utama dengan tab tertentu (tanpa argumen: tab yang sedang aktif)
    private function backTo(?string $tab = null)
    {
        $tab ??= (string) $this->request->getPost('tab');
        $url = 'admin/kis';
        if ($tab !== '' && preg_match('/^[a-z0-9-]+$/', $tab) === 1) {
            $url .= '?tab=' . $tab;
        }

        return redirect()->to($url);
    }

    // Kembali dengan isian lama dan pesan error, lalu pop up dibuka lagi
    private function formError(array $errors, array $reopen)
    {
        return $this->backTo()->withInput()->with('errors', $errors)->with('reopen', $reopen);
    }
}