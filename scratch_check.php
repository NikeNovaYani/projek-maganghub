<?php
$m = new mysqli('localhost', 'root', 'zerodowntime', 'db_compro_sirs');
$res = $m->query("SELECT * FROM site_settings WHERE `key`in ('hero_bg_images', 'site_tagline')");
print_r($res->fetch_all(MYSQL_ASSOC));
