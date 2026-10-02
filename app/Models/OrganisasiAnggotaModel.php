<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganisasiAnggotaModel extends Model
{
    protected $table = 'organisasi_anggota';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'organisasi_id',
        'nama',
        'jabatan',
        'foto',
        'urutan',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $validationRules = [
        'organisasi_id' => 'required|is_natural_no_zero',
        'nama' => 'required|max_length[150]',
        'jabatan' => 'required|max_length[150]',
        'foto' => 'permit_empty|max_length[255]',
        'urutan' => 'required|is_natural',
    ];
}
