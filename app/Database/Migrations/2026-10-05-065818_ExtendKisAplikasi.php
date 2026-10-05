<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExtendKisAplikasi extends Migration
{
    public function up(): void
    {
        // 1) Tabel kategori KIS
        if (! $this->db->tableExists('kis_kategori')) {
            $this->forge->addField([
                'id'         => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
                'nama'       => ['type' => 'VARCHAR', 'constraint' => 100],
                'slug'       => ['type' => 'VARCHAR', 'constraint' => 120],
                'deskripsi'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'urutan'     => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
                'created_by' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
                'updated_by' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('slug');
            $this->forge->createTable('kis_kategori');
        }

        // Isi awal 4 kategori (deskripsi hanya contoh, bisa diubah dari halaman admin)
        if ($this->db->table('kis_kategori')->countAllResults() === 0) {
            $now = date('Y-m-d H:i:s');
            $rows = [
                ['Front Office', 'front-office', 'Aplikasi untuk pelayanan langsung kepada pasien.', 1],
                ['Back Office', 'back-office', 'Aplikasi manajemen dan administrasi rumah sakit.', 2],
                ['Bridging System', 'bridging-system', 'Aplikasi penghubung dengan sistem pihak eksternal.', 3],
                ['Aplikasi Pendukung', 'aplikasi-pendukung', 'Aplikasi penunjang operasional lainnya.', 4],
            ];
            foreach ($rows as [$nama, $slug, $deskripsi, $urutan]) {
                $this->db->table('kis_kategori')->insert([
                    'nama'       => $nama,
                    'slug'       => $slug,
                    'deskripsi'  => $deskripsi,
                    'urutan'     => $urutan,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // 2) Kolom baru di kis_aplikasi
        $fields = [];
        if (! $this->db->fieldExists('kategori_id', 'kis_aplikasi')) {
            $fields['kategori_id'] = ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true];
        }
        if (! $this->db->fieldExists('klasifikasi', 'kis_aplikasi')) {
            $fields['klasifikasi'] = ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true];
        }
        if (! $this->db->fieldExists('akses_internal', 'kis_aplikasi')) {
            $fields['akses_internal'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0];
        }
        if (! $this->db->fieldExists('is_aktif', 'kis_aplikasi')) {
            $fields['is_aktif'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1];
        }
        if ($fields !== []) {
            $this->forge->addColumn('kis_aplikasi', $fields);
        }

        // 3) Link boleh kosong, deskripsi diperpanjang menjadi 500 karakter
        $this->forge->modifyColumn('kis_aplikasi', [
            'url' => ['name' => 'url', 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'deskripsi_singkat' => ['name' => 'deskripsi_singkat', 'type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
        ]);

        // 4) Relasi ke kategori (kategori yang masih punya aplikasi tidak bisa dihapus)
        if (isset($fields['kategori_id'])) {
            $this->db->query(
                'ALTER TABLE kis_aplikasi ADD CONSTRAINT kis_aplikasi_kategori_id_foreign '
                . 'FOREIGN KEY (kategori_id) REFERENCES kis_kategori (id) ON DELETE RESTRICT ON UPDATE CASCADE'
            );
        }
    }

    public function down(): void
    {
        if ($this->db->fieldExists('kategori_id', 'kis_aplikasi')) {
            $this->db->query('ALTER TABLE kis_aplikasi DROP FOREIGN KEY kis_aplikasi_kategori_id_foreign');
        }
        $this->forge->dropColumn('kis_aplikasi', ['kategori_id', 'klasifikasi', 'akses_internal', 'is_aktif']);
        $this->forge->dropTable('kis_kategori', true);
    }
}