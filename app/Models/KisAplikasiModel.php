<?php

namespace App\Models;

use CodeIgniter\Model;

class KisAplikasiModel extends Model
{
    // Kunci disimpan di database, label dipakai untuk tampilan
    public const KLASIFIKASI = [
        'pmk82'      => 'PMK 82',
        'diluar_pmk' => 'Diluar PMK',
        'eksternal'  => 'Integrasi Eksternal',
    ];

    protected $table         = 'kis_aplikasi';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'kategori_id',
        'nama_aplikasi',
        'deskripsi_singkat',
        'url',
        'klasifikasi',
        'akses_internal',
        'is_aktif',
        'urutan',
        'created_by',
        'updated_by',
    ];

    protected $validationRules = [
        'kategori_id'       => 'required|is_natural_no_zero|is_not_unique[kis_kategori.id]',
        'nama_aplikasi'     => 'required|min_length[2]|max_length[150]',
        'deskripsi_singkat' => 'permit_empty|max_length[500]',
        'url'               => 'permit_empty|max_length[255]|valid_url_strict[http,https]',
        'klasifikasi'       => 'permit_empty|in_list[pmk82,diluar_pmk,eksternal]',
        'akses_internal'    => 'permit_empty|in_list[0,1]',
        'is_aktif'          => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [
        'kategori_id' => [
            'required'           => 'Kategori wajib dipilih.',
            'is_natural_no_zero' => 'Kategori wajib dipilih.',
            'is_not_unique'      => 'Kategori tidak ditemukan.',
        ],
        'nama_aplikasi' => [
            'required'   => 'Nama aplikasi wajib diisi.',
            'min_length' => 'Nama aplikasi minimal 2 karakter.',
            'max_length' => 'Nama aplikasi maksimal 150 karakter.',
        ],
        'deskripsi_singkat' => [
            'max_length' => 'Deskripsi maksimal 500 karakter.',
        ],
        'url' => [
            'max_length'       => 'Link maksimal 255 karakter.',
            'valid_url_strict' => 'Link harus berformat URL yang valid dan diawali http:// atau https://.',
        ],
        'klasifikasi' => [
            'in_list' => 'Klasifikasi tidak valid.',
        ],
    ];

    public function nextOrder(?int $kategoriId): int
    {
        $row = db_connect()->table($this->table)
            ->selectMax('urutan', 'max_urutan')
            ->where('kategori_id', $kategoriId)
            ->get()
            ->getRowArray();

        return (int) ($row['max_urutan'] ?? 0) + 1;
    }

    /**
     * Cek nama kembar dalam satu kategori (tidak membedakan huruf besar/kecil).
     */
    public function nameExists(?int $kategoriId, string $nama, ?int $ignoreId = null): bool
    {
        $builder = db_connect()->table($this->table)
            ->where('kategori_id', $kategoriId)
            ->where('LOWER(TRIM(nama_aplikasi)) =', mb_strtolower(trim($nama)));
        if ($ignoreId !== null) {
            $builder->where('id !=', $ignoreId);
        }

        return $builder->countAllResults() > 0;
    }
}
