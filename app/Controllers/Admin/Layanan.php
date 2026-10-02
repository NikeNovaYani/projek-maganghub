<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LayananKategoriModel;

class Layanan extends BaseController
{
    public function index()
    {
        return view('admin/layanan/index', [
            'kategori' => (new LayananKategoriModel())->getAllWithItems(),
        ]);
    }
}