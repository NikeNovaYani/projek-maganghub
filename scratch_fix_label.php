<?php
$m = new mysqli('localhost', 'root', 'zerodowntime', 'db_compro_sirs');
$m->query("UPDATE site_settings SET label = 'Foto Background Hero (URL yang dipisah koma atau upload foto baru)' WHERE `key` = 'hero_bg_images'");
echo "OK";
