<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananItemModel extends Model
{
    protected $table            = 'layanan_item';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['kategori_id', 'judul', 'deskripsi', 'urutan', 'created_by', 'updated_by'];

    protected $validationRules = [
        'kategori_id' => 'required|is_natural_no_zero|is_not_unique[layanan_kategori.id]',
        'judul'       => 'required|min_length[2]|max_length[150]',
        'deskripsi'   => 'permit_empty|max_length[2000]',
    ];

    protected $validationMessages = [
        'kategori_id' => [
            'required'      => 'Kategori wajib dipilih.',
            'is_not_unique' => 'Kategori tidak ditemukan.',
        ],
        'judul' => [
            'required'   => 'Judul layanan wajib diisi.',
            'min_length' => 'Judul layanan minimal 2 karakter.',
            'max_length' => 'Judul layanan maksimal 150 karakter.',
        ],
    ];

    public function nextOrder(int $kategoriId): int
    {
        $row = db_connect()->table($this->table)
            ->selectMax('urutan', 'max_urutan')
            ->where('kategori_id', $kategoriId)
            ->get()
            ->getRowArray();

        return (int) ($row['max_urutan'] ?? 0) + 1;
    }

    /**
     * Cek judul kembar dalam satu kategori (tidak membedakan huruf besar/kecil).
     */
    public function titleExists(int $kategoriId, string $judul, ?int $ignoreId = null): bool
    {
        $builder = db_connect()->table($this->table)
            ->where('kategori_id', $kategoriId)
            ->where('LOWER(TRIM(judul)) =', mb_strtolower(trim($judul)));
        if ($ignoreId !== null) {
            $builder->where('id !=', $ignoreId);
        }

        return $builder->countAllResults() > 0;
    }
}