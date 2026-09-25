<?php
$m = new mysqli('localhost', 'root', 'zerodowntime', 'db_compro_sirs');
$res = $m->query("SELECT * FROM site_settings WHERE id = 11");
var_dump($res->fetch_assoc());
