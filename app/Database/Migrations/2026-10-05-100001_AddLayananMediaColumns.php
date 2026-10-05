<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLayananMediaColumns extends Migration
{
    public function up()
    {
        $this->forge->addColumn('layanan_kategori', [
            'gambar_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);

        $this->forge->addColumn('layanan_item', [
            'gambar_layanan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('layanan_item', 'gambar_layanan');
        $this->forge->dropColumn('layanan_kategori', 'gambar_kategori');
    }
}
