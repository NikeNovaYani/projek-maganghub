<?php

namespace App\Controllers;

use App\Models\OrganisasiModel;

class Struktur extends BaseController
{
    public function index(): string
    {
        return view('sections/struktur', [
            'organisasiTree' => (new OrganisasiModel())->getMainTree(),
        ]);
    }

    public function get_staf(int $parentId)
    {
        $model = new OrganisasiModel();
        $parent = $model->getProfile($parentId);

        if ($parent === null) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Posisi organisasi tidak ditemukan.',
            ]);
        }

        $isStaffGroup = (int) $parent['level'] === 4;
        $staff = $isStaffGroup ? $model->getLevelFiveStaff($parentId) : [$parent];

        return $this->response->setJSON([
            'success' => true,
            'mode'    => $isStaffGroup ? 'staff' : 'profile',
            'parent'  => [
                'id'          => $parent['id'],
                'nama'        => $parent['nama'],
                'jabatan'     => $parent['jabatan'],
                'foto'        => $parent['foto'],
            ],
            'staff' => $staff,
        ]);
    }

    public function staf(int $parentId)
    {
        return $this->get_staf($parentId);
    }
}
