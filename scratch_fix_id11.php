<?php
$m = new mysqli('localhost', 'root', 'zerodowntime', 'db_compro_sirs');
$m->query("UPDATE site_settings SET label = 'Misi SIRS' WHERE id = 11");
$m->query("INSERT INTO site_settings (`key`, `value`, `label`, `tipe`, `group`) VALUES ('hero_bg_images', '', 'Foto Background Hero (URL yang dipisah kama atau upload foto baru)', 'textarea', 'general')");
echo "Done";
