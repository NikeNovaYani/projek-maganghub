<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHeroBackgroundSetting extends Migration
{
    public function up()
    {
        $exists = $this->db->table('site_settings')
            ->where('key', 'hero_bg_images')
            ->countAllResults();

        if ($exists === 0) {
            $now = date('Y-m-d H:i:s');
            $this->db->table('site_settings')->insert([
                'key'        => 'hero_bg_images',
                'value'      => '',
                'label'      => 'Foto Background Hero Beranda',
                'tipe'       => 'textarea',
                'group'      => 'tampilan',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        $this->db->table('site_settings')->where('key', 'hero_bg_images')->delete();
    }
}