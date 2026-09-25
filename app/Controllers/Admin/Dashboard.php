<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $stats = [
            'total_berita'    => $db->table('berita')->countAllResults(),
            'total_organisasi'=> $db->table('organisasi')->countAllResults(),
            'total_layanan'   => $db->table('layanan_item')->countAllResults(),
            'total_kis'       => $db->table('kis_aplikasi')->countAllResults(),
            'total_kegiatan'  => $db->table('kegiatan')->countAllResults(),
            'total_kategori'  => $db->table('kategori')->countAllResults(),
        ];

        $latestBerita = $db->table('berita')
            ->select('berita.*, kategori.nama as kategori_nama')
            ->join('kategori', 'kategori.id = berita.kategori_id', 'left')
            ->orderBy('berita.id', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $data = [
            'stats'        => $stats,
            'latestBerita' => $latestBerita,
        ];

        return view('admin/dashboard', $data);
    }
}
