<?php

if (!function_exists('site_setting')) {
    /**
     * Helper untuk mengambil value site_settings secara cepat.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function site_setting(string $key, $default = '')
    {
        static $cachedSettings = null;

        if ($cachedSettings === null) {
            $db = \Config\Database::connect();
            if ($db->tableExists('site_settings')) {
                $rows = $db->table('site_settings')->select('key, value')->get()->getResultArray();
                $cachedSettings = [];
                foreach ($rows as $row) {
                    $cachedSettings[$row['key']] = $row['value'];
                }
            } else {
                $cachedSettings = [];
            }
        }

        return $cachedSettings[$key] ?? $default;
    }
}
