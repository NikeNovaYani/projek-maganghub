<?php

namespace App\Controllers;

use App\Models\OrganisasiModel;

class Home extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        // Data Organisasi
        $organisasi = (new OrganisasiModel())->getMainTree();

        // Data 4 Kategori Layanan + Items
        $layananKategori = $db->table('layanan_kategori')
            ->orderBy('urutan', 'ASC')
            ->get()
            ->getResultArray();
            
        $layananItems = $db->table('layanan_item')
            ->orderBy('urutan', 'ASC')
            ->get()
            ->getResultArray();

        $itemsByKategori = [];
        $seenLayananItems = [];
        foreach ($layananItems as $item) {
            $itemKey = $item['kategori_id'] . ':' . strtolower(trim($item['judul']));
            if (isset($seenLayananItems[$itemKey])) {
                continue;
            }

            $seenLayananItems[$itemKey] = true;
            $itemsByKategori[$item['kategori_id']][] = $item;
        }

        // Data KIS Aplikasi
        $kisAplikasi = $db->table('kis_aplikasi')
            ->orderBy('urutan', 'ASC')
            ->get()
            ->getResultArray();

        // Berita Terpublikasi
        $berita = $db->table('berita')
            ->select('berita.*, kategori.nama as kategori_nama')
            ->join('kategori', 'kategori.id = berita.kategori_id', 'left')
            ->where('status', 'publish')
            ->orderBy('published_at', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();

        // Kategori Kegiatan untuk Filter Galeri
        $kategoriKegiatan = $db->table('kategori')
            ->where('tipe', 'kegiatan')
            ->get()
            ->getResultArray();

        // Kegiatan / Galeri + Foto-foto per album
        $kegiatanRaw = $db->table('kegiatan')
            ->select('kegiatan.*, kategori.nama as kategori_nama, kategori.slug as kategori_slug')
            ->join('kategori', 'kategori.id = kegiatan.kategori_id', 'left')
            ->orderBy('kegiatan.tanggal', 'DESC')
            ->get()
            ->getResultArray();

        $kegiatan = [];
        foreach ($kegiatanRaw as $k) {
            $fotos = $db->table('kegiatan_foto')
                ->where('kegiatan_id', $k['id'])
                ->orderBy('urutan', 'ASC')
                ->get()
                ->getResultArray();
            $k['fotos'] = $fotos;
            $k['total_foto'] = count($fotos);
            $kegiatan[] = $k;
        }

        $data = [
            'visi'             => site_setting('visi_sirs', ''),
            'misi'             => site_setting('misi_sirs', ''),
            'organisasi'       => $organisasi,
            'layananKategori'  => $layananKategori,
            'itemsByKategori'  => $itemsByKategori,
            'kisAplikasi'      => $kisAplikasi,
            'berita'           => $berita,
            'kategoriKegiatan' => $kategoriKegiatan,
            'kegiatan'         => $kegiatan,
        ];

        return view('home/index', $data);
    }
}
