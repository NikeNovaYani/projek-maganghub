<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananKategoriModel extends Model
{
    protected $table            = 'layanan_kategori';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['nama', 'slug', 'ikon', 'deskripsi', 'urutan', 'created_by', 'updated_by'];

    protected $validationRules = [
        'nama'      => 'required|min_length[3]|max_length[100]',
        'slug'      => 'required|max_length[120]',
        'ikon'      => 'permit_empty|max_length[100]',
        'deskripsi' => 'permit_empty|max_length[1000]',
    ];

    protected $validationMessages = [
        'nama' => [
            'required'   => 'Nama kategori wajib diisi.',
            'min_length' => 'Nama kategori minimal 3 karakter.',
            'max_length' => 'Nama kategori maksimal 100 karakter.',
        ],
        'deskripsi' => [
            'max_length' => 'Deskripsi maksimal 1000 karakter.',
        ],
    ];

    /**
     * Semua kategori (urut) beserta item-itemnya, untuk halaman admin.
     * Tidak menyaring duplikat, supaya data asli terlihat.
     */
    public function getAllWithItems(): array
    {
        $kategori = $this->orderBy('urutan', 'ASC')->orderBy('id', 'ASC')->findAll();

        $items = (new LayananItemModel())
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $byKategori = [];
        foreach ($items as $item) {
            $byKategori[$item['kategori_id']][] = $item;
        }

        foreach ($kategori as &$row) {
            $row['items'] = $byKategori[$row['id']] ?? [];
        }
        unset($row);

        return $kategori;
    }

    public function hasItems(int $id): bool
    {
        return db_connect()->table('layanan_item')->where('kategori_id', $id)->countAllResults() > 0;
    }

    public function nextOrder(): int
    {
        $row = db_connect()->table($this->table)->selectMax('urutan', 'max_urutan')->get()->getRowArray();

        return (int) ($row['max_urutan'] ?? 0) + 1;
    }

    /**
     * Slug unik dari nama, mis. "Technical Support" -> "technical-support", "technical-support-2", dst.
     */
    public function uniqueSlug(string $nama, ?int $ignoreId = null): string
    {
        helper('url');

        $base = url_title($nama, '-', true);
        if ($base === '') {
            $base = 'kategori';
        }

        $slug = $base;
        $suffix = 2;
        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreId): bool
    {
        $builder = db_connect()->table($this->table)->where('slug', $slug);
        if ($ignoreId !== null) {
            $builder->where('id !=', $ignoreId);
        }

        return $builder->countAllResults() > 0;
    }
}