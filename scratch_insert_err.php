<?php
$m = new mysqli('localhost', 'root', 'zerodowntime', 'db_compro_sirs');
$m->query("INSERT INTO site_settings (`id`, `key`, `value`, `label`, `tipe`, `group`) VALUES (11, 'hero_bg_images', '', 'Foto Background Hero (URL yang dipisah kama atau upload foto baru)', 'textarea', 'general')");
echo $m->error;
