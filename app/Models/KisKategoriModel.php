<?php

namespace App\Models;

use CodeIgniter\Model;

class KisKategoriModel extends Model
{
    protected $table         = 'kis_kategori';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['nama', 'slug', 'deskripsi', 'urutan', 'created_by', 'updated_by'];

    protected $validationRules = [
        'nama'      => 'required|min_length[3]|max_length[100]',
        'slug'      => 'required|max_length[120]',
        'deskripsi' => 'permit_empty|max_length[255]',
    ];

    protected $validationMessages = [
        'nama' => [
            'required'   => 'Nama kategori wajib diisi.',
            'min_length' => 'Nama kategori minimal 3 karakter.',
            'max_length' => 'Nama kategori maksimal 100 karakter.',
        ],
        'deskripsi' => [
            'max_length' => 'Deskripsi maksimal 255 karakter.',
        ],
    ];

    /**
     * Semua kategori (urut) beserta aplikasinya, ditambah aplikasi yang belum punya kategori.
     *
     * @return array{kategori: list<array<string, mixed>>, tanpa_kategori: list<array<string, mixed>>}
     */
    public function getAllWithApps(): array
    {
        $kategori = $this->orderBy('urutan', 'ASC')->orderBy('id', 'ASC')->findAll();

        $apps = (new KisAplikasiModel())
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $byKategori = [];
        $tanpaKategori = [];
        foreach ($apps as $app) {
            if (empty($app['kategori_id'])) {
                $tanpaKategori[] = $app;
                continue;
            }
            $byKategori[$app['kategori_id']][] = $app;
        }

        foreach ($kategori as &$row) {
            $row['apps'] = $byKategori[$row['id']] ?? [];
        }
        unset($row);

        return ['kategori' => $kategori, 'tanpa_kategori' => $tanpaKategori];
    }

    public function hasApps(int $id): bool
    {
        return db_connect()->table('kis_aplikasi')->where('kategori_id', $id)->countAllResults() > 0;
    }

    public function nextOrder(): int
    {
        $row = db_connect()->table($this->table)->selectMax('urutan', 'max_urutan')->get()->getRowArray();

        return (int) ($row['max_urutan'] ?? 0) + 1;
    }

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