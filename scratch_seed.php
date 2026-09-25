<?php
$m = new mysqli('localhost', 'root', 'zerodowntime', 'db_compro_sirs');
$m->query("INSERT INTO site_settings (`key`, `value`, `label`, `tipe`, `group`) VALUES ('hero_bg_images', '', 'Foto Background Hero (URL yang dipisah kama atau upload foto baru)', 'textarea', 'general') ON DUPLICATE KEY UPDATE `label` = 'Foto Background Hero (URL yang dipisah kama atau upload foto baru)'");
echo "OK";
