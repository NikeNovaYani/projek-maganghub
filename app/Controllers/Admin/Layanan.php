<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LayananItemModel;
use App\Models\LayananKategoriModel;

class Layanan extends BaseController
{
    public function index()
    {
        return view('admin/layanan/index', [
            'kategori' => (new LayananKategoriModel())->getAllWithItems(),
        ]);
    }

    /* ===================== KATEGORI ===================== */

    public function storeKategori()
    {
        $model = new LayananKategoriModel();
        $data = $this->kategoriFormData();
        $file = $this->request->getFile('gambar_kategori');
        $fileErrors = $this->validateMediaFile($file, 'gambar_kategori');
        if ($fileErrors !== []) {
            return $this->kategoriError($fileErrors, null);
        }

        $mediaName = $this->storeMediaFile($file);
        if ($mediaName === false) {
            return $this->kategoriError(['gambar_kategori' => 'Gambar kategori gagal diunggah.'], null);
        }
        if ($mediaName !== null) {
            $data['gambar_kategori'] = $mediaName;
        }

        $userId = auth()->user()?->id;

        $data['slug'] = $model->uniqueSlug($data['nama']);
        $data['urutan'] = $model->nextOrder();
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        if (! $model->insert($data)) {
            if ($mediaName !== null) {
                $this->removeMediaFile($mediaName);
            }
            return $this->kategoriError($model->errors(), null);
        }

        return redirect()->to('admin/layanan')->with('message', 'Kategori berhasil ditambahkan.');
    }

    public function updateKategori(int $id)
    {
        $model = new LayananKategoriModel();
        $kategori = $model->find($id);
        if ($kategori === null) {
            return redirect()->to('admin/layanan')->with('error', 'Kategori tidak ditemukan.');
        }

        $data = $this->kategoriFormData();
        $file = $this->request->getFile('gambar_kategori');
        $fileErrors = $this->validateMediaFile($file, 'gambar_kategori');
        if ($fileErrors !== []) {
            return $this->kategoriError($fileErrors, $id);
        }

        $mediaName = $this->storeMediaFile($file);
        if ($mediaName === false) {
            return $this->kategoriError(['gambar_kategori' => 'Gambar kategori gagal diunggah.'], $id);
        }
        if ($mediaName !== null) {
            $data['gambar_kategori'] = $mediaName;
        }

        $data['updated_by'] = auth()->user()?->id;

        if (! $model->update($id, $data)) {
            if ($mediaName !== null) {
                $this->removeMediaFile($mediaName);
            }
            return $this->kategoriError($model->errors(), $id);
        }

        if ($mediaName !== null && ! empty($kategori['gambar_kategori'])) {
            $this->removeMediaFile($kategori['gambar_kategori']);
        }

        return redirect()->to('admin/layanan')->with('message', 'Kategori berhasil diperbarui.');
    }

    public function deleteKategori(int $id)
    {
        $model = new LayananKategoriModel();
        $kategori = $model->find($id);
        if ($kategori === null) {
            return redirect()->to('admin/layanan')->with('error', 'Kategori tidak ditemukan.');
        }

        if ($model->hasItems($id)) {
            return redirect()->to('admin/layanan')->with('error', 'Kategori "' . $kategori['nama'] . '" tidak dapat dihapus karena masih memiliki layanan. Hapus atau pindahkan layanannya terlebih dahulu.');
        }

        if (! $model->delete($id)) {
            return redirect()->to('admin/layanan')->with('error', 'Kategori gagal dihapus.');
        }

        if (! empty($kategori['gambar_kategori'])) {
            $this->removeMediaFile($kategori['gambar_kategori']);
        }

        return redirect()->to('admin/layanan')->with('message', 'Kategori berhasil dihapus.');
    }

    public function moveKategori(int $id, string $direction)
    {
        if (! in_array($direction, ['up', 'down'], true)) {
            return redirect()->to('admin/layanan')->with('error', 'Arah pengurutan tidak valid.');
        }

        $rows = (new LayananKategoriModel())->orderBy('urutan', 'ASC')->orderBy('id', 'ASC')->findAll();
        $ids = array_map('intval', array_column($rows, 'id'));

        $index = array_search($id, $ids, true);
        if ($index === false) {
            return redirect()->to('admin/layanan')->with('error', 'Kategori tidak ditemukan.');
        }

        $target = $index + ($direction === 'up' ? -1 : 1);
        if (! isset($ids[$target])) {
            return redirect()->to('admin/layanan')->with('message', 'Kategori sudah berada di urutan tersebut.');
        }

        [$ids[$index], $ids[$target]] = [$ids[$target], $ids[$index]];

        if (! $this->saveOrder('layanan_kategori', $ids)) {
            return redirect()->to('admin/layanan')->with('error', 'Urutan kategori gagal diperbarui.');
        }

        return redirect()->to('admin/layanan')->with('message', 'Urutan kategori berhasil diperbarui.');
    }

    /* ===================== LAYANAN (ITEM) ===================== */

    public function storeItem()
    {
        $model = new LayananItemModel();
        $kategoriId = (int) $this->request->getPost('kategori_id');
        $data = $this->itemFormData();
        $data['kategori_id'] = $kategoriId;
        $file = $this->request->getFile('gambar_layanan');
        $fileErrors = $this->validateMediaFile($file, 'gambar_layanan');
        if ($fileErrors !== []) {
            return $this->itemError($fileErrors, null, $kategoriId);
        }

        $mediaName = $this->storeMediaFile($file);
        if ($mediaName === false) {
            return $this->itemError(['gambar_layanan' => 'Gambar layanan gagal diunggah.'], null, $kategoriId);
        }
        if ($mediaName !== null) {
            $data['gambar_layanan'] = $mediaName;
        }

        if ($data['judul'] !== '' && $model->titleExists($kategoriId, $data['judul'])) {
            if ($mediaName !== null) {
                $this->removeMediaFile($mediaName);
            }
            return $this->itemError(['judul' => 'Layanan dengan judul tersebut sudah ada di kategori ini.'], null, $kategoriId);
        }

        $userId = auth()->user()?->id;
        $data['urutan'] = $model->nextOrder($kategoriId);
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        if (! $model->insert($data)) {
            if ($mediaName !== null) {
                $this->removeMediaFile($mediaName);
            }
            return $this->itemError($model->errors(), null, $kategoriId);
        }

        return redirect()->to('admin/layanan')->with('message', 'Layanan berhasil ditambahkan.');
    }

    public function updateItem(int $id)
    {
        $model = new LayananItemModel();
        $item = $model->find($id);
        if ($item === null) {
            return redirect()->to('admin/layanan')->with('error', 'Layanan tidak ditemukan.');
        }

        $kategoriId = (int) $item['kategori_id'];
        $data = $this->itemFormData();
        $file = $this->request->getFile('gambar_layanan');
        $fileErrors = $this->validateMediaFile($file, 'gambar_layanan');
        if ($fileErrors !== []) {
            return $this->itemError($fileErrors, $id, $kategoriId);
        }

        $mediaName = $this->storeMediaFile($file);
        if ($mediaName === false) {
            return $this->itemError(['gambar_layanan' => 'Gambar layanan gagal diunggah.'], $id, $kategoriId);
        }
        if ($mediaName !== null) {
            $data['gambar_layanan'] = $mediaName;
        }

        if ($data['judul'] !== '' && $model->titleExists($kategoriId, $data['judul'], $id)) {
            if ($mediaName !== null) {
                $this->removeMediaFile($mediaName);
            }
            return $this->itemError(['judul' => 'Layanan dengan judul tersebut sudah ada di kategori ini.'], $id, $kategoriId);
        }

        $data['updated_by'] = auth()->user()?->id;

        if (! $model->update($id, $data)) {
            if ($mediaName !== null) {
                $this->removeMediaFile($mediaName);
            }
            return $this->itemError($model->errors(), $id, $kategoriId);
        }

        if ($mediaName !== null && ! empty($item['gambar_layanan'])) {
            $this->removeMediaFile($item['gambar_layanan']);
        }

        return redirect()->to('admin/layanan')->with('message', 'Layanan berhasil diperbarui.');
    }

    public function deleteItem(int $id)
    {
        $model = new LayananItemModel();
        $item = $model->find($id);
        if ($item === null) {
            return redirect()->to('admin/layanan')->with('error', 'Layanan tidak ditemukan.');
        }

        if (! $model->delete($id)) {
            return redirect()->to('admin/layanan')->with('error', 'Layanan gagal dihapus.');
        }

        if (! empty($item['gambar_layanan'])) {
            $this->removeMediaFile($item['gambar_layanan']);
        }

        return redirect()->to('admin/layanan')->with('message', 'Layanan berhasil dihapus.');
    }

    public function moveItem(int $id, string $direction)
    {
        if (! in_array($direction, ['up', 'down'], true)) {
            return redirect()->to('admin/layanan')->with('error', 'Arah pengurutan tidak valid.');
        }

        $model = new LayananItemModel();
        $item = $model->find($id);
        if ($item === null) {
            return redirect()->to('admin/layanan')->with('error', 'Layanan tidak ditemukan.');
        }

        // Urutan hanya berlaku di dalam satu kategori
        $rows = $model->where('kategori_id', $item['kategori_id'])->orderBy('urutan', 'ASC')->orderBy('id', 'ASC')->findAll();
        $ids = array_map('intval', array_column($rows, 'id'));

        $index = array_search($id, $ids, true);
        $target = $index + ($direction === 'up' ? -1 : 1);
        if ($index === false || ! isset($ids[$target])) {
            return redirect()->to('admin/layanan')->with('message', 'Layanan sudah berada di urutan tersebut.');
        }

        [$ids[$index], $ids[$target]] = [$ids[$target], $ids[$index]];

        if (! $this->saveOrder('layanan_item', $ids)) {
            return redirect()->to('admin/layanan')->with('error', 'Urutan layanan gagal diperbarui.');
        }

        return redirect()->to('admin/layanan')->with('message', 'Urutan layanan berhasil diperbarui.');
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

    private function itemFormData(): array
    {
        $judul = $this->request->getPost('judul');

        return [
            'judul'     => is_scalar($judul) ? trim((string) $judul) : '',
            'deskripsi' => $this->cleanText($this->request->getPost('deskripsi')),
        ];
    }

    private function validateMediaFile(?\CodeIgniter\HTTP\Files\UploadedFile $file, string $field): array
    {
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return [];
        }
        if (! $file->isValid() || $file->hasMoved()) {
            return [$field => 'File media tidak valid atau gagal diunggah.'];
        }

        $extension = strtolower($file->getExtension());
        $mimeType = $file->getMimeType();
        $validExtension = match ($mimeType) {
            'image/jpeg' => in_array($extension, ['jpg', 'jpeg'], true),
            'image/png'  => $extension === 'png',
            'video/webm' => $extension === 'webm',
            default      => false,
        };

        if (! $validExtension
            || $file->getSize() > 4 * 1024 * 1024
            || ($mimeType !== 'video/webm' && @getimagesize($file->getTempName()) === false)
        ) {
            return [$field => 'File harus berupa JPG, PNG, atau WebM yang valid dan maksimal 4 MB.'];
        }

        return [];
    }

    private function storeMediaFile(?\CodeIgniter\HTTP\Files\UploadedFile $file): string|false|null
    {
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $directory = FCPATH . 'uploads/layanan';
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            log_message('error', 'Direktori upload layanan gagal dibuat: {directory}', ['directory' => $directory]);

            return false;
        }

        try {
            $name = $file->getRandomName();

            return $file->move($directory, $name) ? $name : false;
        } catch (\Throwable $exception) {
            log_message('error', 'Upload media layanan gagal: {message}', ['message' => $exception->getMessage()]);

            return false;
        }
    }

    private function removeMediaFile(string $name): bool
    {
        if ($name !== basename($name)) {
            log_message('error', 'Nama file media layanan tidak valid saat penghapusan.');

            return false;
        }

        $path = FCPATH . 'uploads/layanan/' . $name;
        if (! is_file($path)) {
            return true;
        }
        if (! unlink($path)) {
            log_message('error', 'File media layanan gagal dihapus: {name}', ['name' => $name]);

            return false;
        }

        return true;
    }

    // Teks kosong disimpan sebagai NULL
    private function cleanText(mixed $value): ?string
    {
        $text = is_scalar($value) ? trim((string) $value) : '';

        return $text === '' ? null : $text;
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

    // Kembali ke index, isian lama dipertahankan, dan pop up dibuka lagi bersama pesan error
    private function kategoriError(array $errors, ?int $id)
    {
        return redirect()->to('admin/layanan')
            ->withInput()
            ->with('errors', $errors)
            ->with('reopen', ['type' => 'kategori', 'id' => $id]);
    }

    private function itemError(array $errors, ?int $id, int $kategoriId)
    {
        return redirect()->to('admin/layanan')
            ->withInput()
            ->with('errors', $errors)
            ->with('reopen', ['type' => 'item', 'id' => $id, 'kategori_id' => $kategoriId]);
    }
}