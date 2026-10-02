<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ComproSirsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // 1. Site Settings Default
        $settings = [
            [
                'key'        => 'site_name',
                'value'      => 'Instalasi SIRS RSUP Dr. Kariadi',
                'label'      => 'Nama Instalasi',
                'tipe'       => 'text',
                'group'      => 'general',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'site_tagline',
                'value'      => 'Transformasi Digital Menuju Layanan Kesehatan Paripurna',
                'label'      => 'Tagline / Slogan',
                'tipe'       => 'text',
                'group'      => 'general',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'manpro_url',
                'value'      => 'https://manpro.rskariadi.id',
                'label'      => 'URL Sistem Manpro SIRS',
                'tipe'       => 'url',
                'group'      => 'integrasi',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'contact_email',
                'value'      => 'sirs@rskariadi.id',
                'label'      => 'Email Resmi SIRS',
                'tipe'       => 'text',
                'group'      => 'kontak',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'contact_phone',
                'value'      => '(024) 8413476 Ext. 2100 / 2101',
                'label'      => 'Telepon & Extension',
                'tipe'       => 'text',
                'group'      => 'kontak',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'contact_whatsapp',
                'value'      => '081234567890',
                'label'      => 'Nomor WhatsApp Helpdesk',
                'tipe'       => 'text',
                'group'      => 'kontak',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'hospital_address',
                'value'      => 'Gedung Penunjang Lt. 3, RSUP Dr. Kariadi - Jl. Dr. Sutomo No.16, Semarang 50244',
                'label'      => 'Alamat Kantor SIRS',
                'tipe'       => 'textarea',
                'group'      => 'kontak',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'service_hours',
                'value'      => 'Senin - Jumat: 07.30 - 16.00 WIB | Technical Support: 24 Jam 7 Hari',
                'label'      => 'Jam Operasional Layanan',
                'tipe'       => 'text',
                'group'      => 'kontak',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'about_sirs',
                'value'      => 'Instalasi Sistem Informasi Rumah Sakit (SIRS) RSUP Dr. Kariadi merupakan unit kerja pengelola teknologi informasi yang bertugas merencanakan, mengembangkan, memelihara, dan mengamankan seluruh infrastruktur TI, sistem informasi manajemen rumah sakit (SIMRS - KIS), serta memberikan layanan teknis 24/7 demi keandalan operasional pelayanan medis dan non-medis.',
                'label'      => 'Profil Singkat SIRS',
                'tipe'       => 'textarea',
                'group'      => 'profil',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'visi_sirs',
                'value'      => 'Menjadi ICT rumah sakit terdepan, modern dan berdaya saing tinggi di tingkat asia',
                'label'      => 'Visi SIRS',
                'tipe'       => 'textarea',
                'group'      => 'profil',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'misi_sirs',
                'value'      => '1. Mengembangkan sistem informasi manajemen rumah sakit yang terintegrasi, adaptif, dan user-friendly.
2. Menyediakan infrastruktur jaringan, server, dan keamanan data yang handal dengan zero downtime.
3. Memberikan pelayanan dukungan teknis yang responsif, profesional, dan berorientasi pada kepuasan pengguna.
4. Mengoptimalkan pemanfaatan data dan analitik kesehatan untuk pengambilan keputusan strategis.',
                'label'      => 'Misi SIRS',
                'tipe'       => 'textarea',
                'group'      => 'profil',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'hero_bg_images',
                'value'      => '',
                'label'      => 'Foto Background Hero Beranda',
                'tipe'       => 'textarea',
                'group'      => 'tampilan',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        $this->db->table('site_settings')->ignore(true)->insertBatch($settings);

        // 2. Kategori Berita & Kegiatan
        $kategori = [
            ['id' => 1, 'nama' => 'Pengumuman', 'slug' => 'pengumuman', 'tipe' => 'berita', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'nama' => 'Update SIMRS', 'slug' => 'update-simrs', 'tipe' => 'berita', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'nama' => 'Teknologi & Inovasi', 'slug' => 'teknologi-inovasi', 'tipe' => 'berita', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'nama' => 'Keamanan Informasi', 'slug' => 'keamanan-informasi', 'tipe' => 'berita', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'nama' => 'Pelatihan & Sosialisasi', 'slug' => 'pelatihan-sosialisasi', 'tipe' => 'kegiatan', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'nama' => 'Maintenance & Deployment', 'slug' => 'maintenance-deployment', 'tipe' => 'kegiatan', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'nama' => 'Kegiatan Internal SIRS', 'slug' => 'kegiatan-internal', 'tipe' => 'kegiatan', 'created_at' => $now, 'updated_at' => $now],
        ];
        $this->db->table('kategori')->ignore(true)->insertBatch($kategori);

        // 3. 4 Kategori Layanan Tetap
        $layananKategori = [
            [
                'id'         => 1,
                'nama'       => 'Hardware',
                'slug'       => 'hardware',
                'ikon'       => 'server',
                'deskripsi'  => 'Pengelolaan workstation PC, printer medis/label, barcode scanner, CCTV medis, UPS, dan perangkat komputasi di seluruh unit kerja.',
                'urutan'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 2,
                'nama'       => 'Software',
                'slug'       => 'software',
                'ikon'       => 'code',
                'deskripsi'  => 'Pengembangan, pemeliharaan modul Rekam Medis Elektronik (RME/EMR), Billing, Farmasi, Laboratorium, Radiologi, dan integrasi SatuSehat/BPJS.',
                'urutan'     => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 3,
                'nama'       => 'Penunjang Teknologi Informasi',
                'slug'       => 'penunjang-ti',
                'ikon'       => 'shield-check',
                'deskripsi'  => 'Penyediaan konektivitas intranet LAN/Wi-Fi rumah sakit, data center, firewall, backup data otomatis, dan proteksi keamanan siber.',
                'urutan'     => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 4,
                'nama'       => 'Technical Support 24 Jam',
                'slug'       => 'technical-support',
                'ikon'       => 'headphones',
                'deskripsi'  => 'Layanan cepat tanggap penanganan kendala IT bagi dokter, perawat, dan staf operasional 24 jam sehari 7 hari seminggu.',
                'urutan'     => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        $this->db->table('layanan_kategori')->ignore(true)->insertBatch($layananKategori);

        // 4. Layanan Items
        $layananItems = [
            ['kategori_id' => 1, 'judul' => 'Perbaikan & Pemeliharaan PC / Laptop Kerja', 'deskripsi' => 'Perawatan berkala hardware, penggantian sparepart, dan instalasi OS standar rumah sakit.', 'urutan' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['kategori_id' => 1, 'judul' => 'Printer Thermal & Label Gelang Pasien', 'deskripsi' => 'Pengaturan printer resep, billing kasir, dan printer gelang identifikasi pasien.', 'urutan' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['kategori_id' => 1, 'judul' => 'Perangkat Antrean Elektronik & Display Poliklinik', 'deskripsi' => 'Pemeliharaan kiosk mesin antrean mandiri dan monitor antrean poli.', 'urutan' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['kategori_id' => 2, 'judul' => 'Rekam Medis Elektronik (RME / EMR)', 'deskripsi' => 'Pengisian CPPT, e-Resep, order lab/radiologi, dan ringkasan pulang pasien.', 'urutan' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['kategori_id' => 2, 'judul' => 'Integrasi Bridging BPJS V-Claim & SatuSehat', 'deskripsi' => 'Koneksi real-time untuk SEP BPJS, antrean online, dan resume medis SatuSehat Kemenkes.', 'urutan' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['kategori_id' => 2, 'judul' => 'Modul Farmasi & Logistik Obat', 'deskripsi' => 'Manajemen stok depo farmasi, verifikasi telaah resep obat, dan kontrol exp date.', 'urutan' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['kategori_id' => 3, 'judul' => 'Akses Intranet & Wi-Fi Medis', 'deskripsi' => 'Pengaturan SSID khusus klinis dengan proteksi bandwidth prioritas.', 'urutan' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['kategori_id' => 3, 'judul' => 'Data Center & Backup Redundan', 'deskripsi' => 'Replikasi data real-time, backup terjadwal harian, dan disaster recovery plan.', 'urutan' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['kategori_id' => 4, 'judul' => 'Service Desk On-Call 24/7 (Ext. 2100)', 'deskripsi' => 'Pusat panggilan darurat kendala IT untuk unit gawat darurat, ICU, dan rawat inap.', 'urutan' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['kategori_id' => 4, 'judul' => 'Tiket Pelaporan Masalah via Manpro', 'deskripsi' => 'Pencatatan kendala dan pemantauan SLA penyelesaian oleh teknisi secara transparan.', 'urutan' => 2, 'created_at' => $now, 'updated_at' => $now],
        ];
        $existingLayananItems = $this->db->table('layanan_item')
            ->select('kategori_id, judul')
            ->get()
            ->getResultArray();
        $existingLayananKeys = [];
        foreach ($existingLayananItems as $item) {
            $existingLayananKeys[$item['kategori_id'] . ':' . strtolower(trim($item['judul']))] = true;
        }

        $newLayananItems = array_filter($layananItems, static function ($item) use (&$existingLayananKeys) {
            $itemKey = $item['kategori_id'] . ':' . strtolower(trim($item['judul']));
            if (isset($existingLayananKeys[$itemKey])) {
                return false;
            }

            $existingLayananKeys[$itemKey] = true;
            return true;
        });

        if (! empty($newLayananItems)) {
            $this->db->table('layanan_item')->insertBatch(array_values($newLayananItems));
        }

        // 5. Daftar Aplikasi KIS
        $kisAplikasi = [
            ['nama_aplikasi' => 'KIS Rawat Jalan & Poliklinik', 'deskripsi_singkat' => 'Modul pendaftaran, rekam medis rawat jalan, dan antrean poli spesialis.', 'url' => 'https://kis.rskariadi.id/rajal', 'ikon' => 'clipboard-document-check', 'urutan' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nama_aplikasi' => 'KIS Rawat Inap & ICU', 'deskripsi_singkat' => 'Manajemen bed, asuhan keperawatan, rekam medis terpadu pasien opname.', 'url' => 'https://kis.rskariadi.id/ranap', 'ikon' => 'building-office-2', 'urutan' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['nama_aplikasi' => 'KIS Farmasi & Depo Obat', 'deskripsi_singkat' => 'Dispensing e-resep racikan/non-racikan dan manajemen gudang logistik farmasi.', 'url' => 'https://kis.rskariadi.id/farmasi', 'ikon' => 'beaker', 'urutan' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['nama_aplikasi' => 'KIS Laboratorium (LIS)', 'deskripsi_singkat' => 'Integrasi alat analyzer laboratorium darah, patologi anatomi, dan hasil tes otomatis.', 'url' => 'https://kis.rskariadi.id/lab', 'ikon' => 'chart-bar', 'urutan' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['nama_aplikasi' => 'KIS Radiologi (RIS / PACS)', 'deskripsi_singkat' => 'Penyimpanan dan penampil citra radiologi digital (X-Ray, CT-Scan, MRI) berstandar DICOM.', 'url' => 'https://kis.rskariadi.id/radiologi', 'ikon' => 'film', 'urutan' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['nama_aplikasi' => 'KIS Kasir & Billing Pasien', 'deskripsi_singkat' => 'Perhitungan tarif layanan, rincian biaya perawatan, dan integrasi payment gateway.', 'url' => 'https://kis.rskariadi.id/billing', 'ikon' => 'banknotes', 'urutan' => 6, 'created_at' => $now, 'updated_at' => $now],
        ];
        $this->db->table('kis_aplikasi')->ignore(true)->insertBatch($kisAplikasi);

        // 6. Struktur Organisasi
        $this->db->table('organisasi_anggota')->where('id >', 0)->delete();
        $this->db->table('organisasi')->where('id >', 0)->delete();

        $orgRows = [
            ['id' => 1, 'parent_id' => null, 'level' => 1, 'node_type' => 'kepala', 'layout_type' => 'main', 'nama' => 'Dr. Ir. Bambang Hermanto, M.Kom', 'jabatan' => 'Ka. Instalasi SIRS & Komunikasi', 'foto' => null, 'urutan' => 1],
            ['id' => 2, 'parent_id' => 1, 'level' => 2, 'node_type' => 'admin', 'layout_type' => 'side', 'nama' => 'Rahmat Hidayat, S.Kom, M.T', 'jabatan' => 'Administrasi', 'foto' => null, 'urutan' => 1],
            ['id' => 3, 'parent_id' => 1, 'level' => 2, 'node_type' => 'penjab', 'layout_type' => 'main', 'nama' => 'Dimas Prasetyo, S.T', 'jabatan' => 'Penjab Layanan & Mutu Layanan', 'foto' => null, 'urutan' => 2],
            ['id' => 4, 'parent_id' => 1, 'level' => 2, 'node_type' => 'penjab', 'layout_type' => 'main', 'nama' => 'Siti Nurhaliza, S.Kom', 'jabatan' => 'Penjab Sarana & Prasarana', 'foto' => null, 'urutan' => 3],
            ['id' => 5, 'parent_id' => 4, 'level' => 3, 'node_type' => 'katim', 'layout_type' => 'main', 'nama' => 'Fajar Nugroho, S.Kom', 'jabatan' => 'Ka Tim Pengembangan dan Pemeliharaan Perangkat Lunak', 'foto' => null, 'urutan' => 1],
            ['id' => 6, 'parent_id' => 4, 'level' => 3, 'node_type' => 'katim', 'layout_type' => 'main', 'nama' => 'Maya Lestari, S.Kom', 'jabatan' => 'Ka Tim Pengembangan dan Pemeliharaan Infrastruktur Teknologi Informasi', 'foto' => null, 'urutan' => 2],
            ['id' => 7, 'parent_id' => 4, 'level' => 3, 'node_type' => 'katim', 'layout_type' => 'main', 'nama' => 'Andi Kurniawan, S.T', 'jabatan' => 'Ka Tim Penunjang Teknologi Informasi', 'foto' => null, 'urutan' => 3],
        ];

        $staffGroups = [
            ['parent_id' => 5, 'names' => ['Ari Wibowo', 'Budi Santoso'], 'role' => 'Business Analis'],
            ['parent_id' => 5, 'names' => ['Citra Puspita', 'Deni Firmansyah'], 'role' => 'Analisis Sistem'],
            ['parent_id' => 5, 'names' => ['Aditya Pranata', 'Bella Maharani', 'Chandra Kusuma', 'Dewi Anggraini', 'Erlangga Putra', 'Fina Oktaviani', 'Gilang Ramadhan', 'Hani Lestari', 'Indra Gunawan', 'Jihan Safitri', 'Kevin Alamsyah', 'Laras Wulandari', 'Miko Setiaji'], 'role' => 'Programer'],
            ['parent_id' => 6, 'names' => ['Agus Setiawan', 'Bayu Kurniawan'], 'role' => 'Administrator Database'],
            ['parent_id' => 6, 'names' => ['Eko Saputra', 'Farhan Maulana'], 'role' => 'Administrator Aplikasi'],
            ['parent_id' => 6, 'names' => ['Galih Pratama', 'Hendra Wijaya'], 'role' => 'Administrator Server dan Network'],
            ['parent_id' => 6, 'names' => ['Iqbal Ramadhan', 'Joko Susilo', 'Kurniawan Putra', 'Lukman Hakim', 'Nanda Pratama', 'Oscar Firmansyah', 'Putri Amelia', 'Raka Mahendra', 'Salsa Nabila', 'Tegar Prakoso', 'Vina Kartika'], 'role' => 'Teknisi'],
            ['parent_id' => 7, 'names' => ['Wahyu Haryanto', 'Yuni Astuti'], 'role' => 'Implementator'],
            ['parent_id' => 7, 'names' => ['Zaki Akbar', 'Anisa Rahma'], 'role' => 'Help Desk'],
            ['parent_id' => 7, 'names' => ['Bagas Adi', 'Cahyo Nugraha'], 'role' => 'Logistik'],
        ];

        $nextId = 8;
        $nextMemberId = 1;
        $groupOrdersByParent = [];
        $memberOrdersByGroup = [];
        $memberRows = [];
        foreach ($staffGroups as $group) {
            $parentId = $group['parent_id'];
            $groupId = $nextId++;
            $groupOrdersByParent[$parentId] = ($groupOrdersByParent[$parentId] ?? 0) + 1;
            $orgRows[] = [
                'id' => $groupId,
                'parent_id' => $parentId,
                'level' => 4,
                'node_type' => 'tim',
                'layout_type' => 'main',
                'nama' => $group['role'],
                'jabatan' => $group['role'],
                'foto' => null,
                'urutan' => $groupOrdersByParent[$parentId],
            ];
            foreach ($group['names'] as $name) {
                $memberOrdersByGroup[$groupId] = ($memberOrdersByGroup[$groupId] ?? 0) + 1;
                $memberRows[] = [
                    'id' => $nextMemberId++,
                    'organisasi_id' => $groupId,
                    'nama' => $name,
                    'jabatan' => $group['role'],
                    'foto' => null,
                    'urutan' => $memberOrdersByGroup[$groupId],
                ];
            }
        }

        foreach ($orgRows as &$orgRow) {
            $orgRow['created_at'] = $now;
            $orgRow['updated_at'] = $now;
        }
        unset($orgRow);

        $this->db->table('organisasi')->insertBatch($orgRows);
        foreach ($memberRows as &$memberRow) {
            $memberRow['created_at'] = $now;
            $memberRow['updated_at'] = $now;
        }
        unset($memberRow);
        $this->db->table('organisasi_anggota')->insertBatch($memberRows);

        // 7. Berita Awal
        $berita = [
            [
                'id'           => 1,
                'kategori_id'  => 1,
                'judul'        => 'Penerapan Modul Rekam Medis Elektronik Terintegrasi SatuSehat Kemenkes RI',
                'slug'         => 'penerapan-modul-rekam-medis-elektronik-satusehat',
                'ringkasan'    => 'RSUP Dr. Kariadi melalui Divisi SIRS berhasil merampungkan integrasi penuh RME ke platform SatuSehat Kementerian Kesehatan RI.',
                'konten'       => '<p>Sebagai wujud komitmen peningkatan mutu layanan dan transparansi data kesehatan nasional, Instalasi SIRS RSUP Dr. Kariadi telah berhasil mengintegrasikan seluruh data resume rekam medis elektronik rawat jalan dan rawat inap ke dalam ekosistem SatuSehat Kemenkes RI.</p>',
                'thumbnail'    => null,
                'status'       => 'publish',
                'published_at' => $now,
                'views'        => 142,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'id'           => 2,
                'kategori_id'  => 2,
                'judul'        => 'Pembaruan Fitur e-Resep KIS Farmasi dan Validasi Alergi Otomatis',
                'slug'         => 'pembaruan-fitur-e-resep-kis-farmasi',
                'ringkasan'    => 'Sistem KIS Farmasi mendapatkan peningkatan sistem skrining klinis otomatis untuk mendeteksi potensi alergi obat pasien.',
                'konten'       => '<p>Divisi Pengembangan Software SIRS merilis fitur terbaru pada modul KIS Farmasi. Fitur ini secara otomatis memberikan peringatan visual kepada dokter peresep dan apoteker apabila terdeteksi interaksi obat berisiko tinggi atau riwayat alergi pasien.</p>',
                'thumbnail'    => null,
                'status'       => 'publish',
                'published_at' => $now,
                'views'        => 89,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];
        $this->db->table('berita')->ignore(true)->insertBatch($berita);

        // 8. Kegiatan & Dokumentasi Galeri
        $kegiatan = [
            [
                'id'          => 1,
                'kategori_id' => 5,
                'judul'       => 'Workshop Pelatihan Modul RME Rawat Inap & Asuhan Keperawatan Elektronik',
                'slug'        => 'workshop-pelatihan-rme-rawat-inap',
                'tanggal'     => date('Y-m-d', strtotime('-10 days')),
                'cover_foto'  => null,
                'deskripsi'   => 'Kegiatan sosialisasi dan bimbingan teknis pengisian rekam medis elektronik bagi seluruh perawat dan dokter spesialis rawat inap RSUP Dr. Kariadi.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 2,
                'kategori_id' => 6,
                'judul'       => 'Pemeliharaan Rutin Server & Upgrade Core Switch Data Center SIRS',
                'slug'        => 'pemeliharaan-server-data-center',
                'tanggal'     => date('Y-m-d', strtotime('-25 days')),
                'cover_foto'  => null,
                'deskripsi'   => 'Proses upgrade perangkat core network switch 10G dan penataan kabel fiber optic di Data Center Gedung Penunjang untuk memastikan keandalan jaringan 24/7.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 3,
                'kategori_id' => 5,
                'judul'       => 'Simulasi Penanganan Insiden Keamanan Siber & Uji Disaster Recovery SIMRS',
                'slug'        => 'simulasi-keamanan-siber-dr-simrs',
                'tanggal'     => date('Y-m-d', strtotime('-45 days')),
                'cover_foto'  => null,
                'deskripsi'   => 'Uji coba failover database cluster ke server cadangan dan simulasi penanganan ancaman siber bersama tim CSIRT Kemenkes.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 4,
                'kategori_id' => 7,
                'judul'       => 'Rapat Kerja Koordinasi Evaluasi Tahunan & Rencana Strategis SIRS 2026',
                'slug'        => 'raker-evaluasi-tahunan-sirs-2026',
                'tanggal'     => date('Y-m-d', strtotime('-60 days')),
                'cover_foto'  => null,
                'deskripsi'   => 'Evaluasi kinerja layanan service desk, pencapaian target SLA, dan roadmap pengembangan inovasi AI untuk diagnostik SIMRS.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];
        $this->db->table('kegiatan')->ignore(true)->insertBatch($kegiatan);

        // 9. Foto-foto Dokumentasi Kegiatan
        $fotos = [
            ['kegiatan_id' => 1, 'foto' => 'sample-workshop-1.jpg', 'caption' => 'Pembukaan workshop pelatihan RME oleh Kepala Instalasi SIRS', 'urutan' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['kegiatan_id' => 1, 'foto' => 'sample-workshop-2.jpg', 'caption' => 'Praktik langsung penginputan CPPT elektronik oleh perawat ruang ICU', 'urutan' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['kegiatan_id' => 1, 'foto' => 'sample-workshop-3.jpg', 'caption' => 'Sesi tanya jawab dan asistensi teknis bersama staf pengembang SIRS', 'urutan' => 3, 'created_at' => $now, 'updated_at' => $now],
            
            ['kegiatan_id' => 2, 'foto' => 'sample-dc-1.jpg', 'caption' => 'Tim infrastruktur melakukan konfigurasi rack server utama', 'urutan' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['kegiatan_id' => 2, 'foto' => 'sample-dc-2.jpg', 'caption' => 'Pengujian redundansi daya UPS dan suhu pendingin data center', 'urutan' => 2, 'created_at' => $now, 'updated_at' => $now],
            
            ['kegiatan_id' => 3, 'foto' => 'sample-cyber-1.jpg', 'caption' => 'Monitoring real-time traffic jaringan pada dashboard SOC SIRS', 'urutan' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['kegiatan_id' => 3, 'foto' => 'sample-cyber-2.jpg', 'caption' => 'Uji ketahanan failover database transaksi KIS Billing & Farmasi', 'urutan' => 2, 'created_at' => $now, 'updated_at' => $now],
            
            ['kegiatan_id' => 4, 'foto' => 'sample-raker-1.jpg', 'caption' => 'Pemaparan roadmap transformasi digital SIRS tahun 2026', 'urutan' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['kegiatan_id' => 4, 'foto' => 'sample-raker-2.jpg', 'caption' => 'Foto bersama jajaran pimpinan dan staf Instalasi SIRS RSUP Dr. Kariadi', 'urutan' => 2, 'created_at' => $now, 'updated_at' => $now],
        ];
        $this->db->table('kegiatan_foto')->ignore(true)->insertBatch($fotos);
    }
}
