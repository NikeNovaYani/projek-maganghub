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

        if ($parent['node_type'] === 'tim') {
            return $this->response->setJSON([
                'success' => true,
                'mode'    => 'staff',
                'parent'  => [
                    'id'      => $parent['id'],
                    'nama'    => $parent['nama'],
                    'jabatan' => $parent['jabatan'],
                    'foto'    => null,
                ],
                'staff' => $model->getGroupMembers($parentId),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'mode'    => 'profile',
            'parent'  => [
                'id'          => $parent['id'],
                'nama'        => $parent['nama'],
                'jabatan'     => $parent['jabatan'],
                'foto'        => $parent['foto'],
            ],
            'staff' => [$parent],
        ]);
    }

    public function staf(int $parentId)
    {
        return $this->get_staf($parentId);
    }
}
