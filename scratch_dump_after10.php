<?php
$m = new mysqli('localhost', 'root', 'zerodowntime', 'db_compro_sirs');
$res = $m->query("SELECT * FROM site_settings WHERE id > 10");
var_dump($res->fetch_all(MYSQLI_ASSOC));
