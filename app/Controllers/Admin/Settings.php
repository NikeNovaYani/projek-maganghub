<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Settings extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $rows = $db->table('site_settings')->orderBy('group', 'ASC')->orderBy("id", 'ASC')->get()->getResultArray();
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['group']][] = $row;
        }
        return view('admin/settings/index', ['grouped' => $grouped, 'pageTitle' => 'Pengaturan Website']);
    }

    public function save()
    {
        $db = \Config\Database::connect();
        if (strtolower($this->request->getMethod()) !== 'post') { return redirect()->to('admin/settings'); }
        
        $allSettings = $db->table('site_settings')->get()->getResultArray();
        foreach ($allSettings as $setting) {
            $key = $setting['key'];
            $val = $this->request->getPost($key);
            if ($val !== null) {
                $db->table('site_settings')->where('key', $key)->update(['value' => trim($val), 'updated_at' => date('Y-m-d H:i:s')]);
            }
        }

        // Handle File Uploads for Hero Background Images
        $files = $this->request->getFiles();
        $heroFiles = $files['hero_bg_files'] ?? [];
        if (! is_array($heroFiles)) {
            $heroFiles = [$heroFiles];
        }

        $uploadErrors = [];
        if (! empty($heroFiles)) {
            $uploadedPaths = [];
            $heroUploadDir = FCPATH . 'uploads/hero';
            if (! is_dir($heroUploadDir)) {
                mkdir($heroUploadDir, 0755, true);
            }

            foreach ($heroFiles as $file) {
                if (! $file instanceof \CodeIgniter\HTTP\Files\UploadedFile) {
                    continue;
                }

                if (! $file->isValid()) {
                    if ($file->getError() !== UPLOAD_ERR_NO_FILE) {
                        $uploadErrors[] = $file->getErrorString();
                    }
                    continue;
                }

                if (! $file->hasMoved()) {
                    $newName = $file->getRandomName();
                    try {
                        if ($file->move($heroUploadDir, $newName)) {
                            $uploadedPaths[] = 'uploads/hero/' . $newName;
                        }
                    } catch (\Throwable $exception) {
                        log_message('error', 'Upload hero gagal: {message}', ['message' => $exception->getMessage()]);
                        $uploadErrors[] = $file->getName() . ': ' . $exception->getMessage();
                    }
                }
            }
            if (!empty($uploadedPaths)) {
                $existing = $db->table('site_settings')->where('key', 'hero_bg_images')->get()->getRowArray();
                $currentList = [];
                if ($existing && !empty($existing['value'])) {
                    $currentList = array_filter(array_map('trim', explode(',', $existing['value'])));
                }
                $newList = array_merge($currentList, $uploadedPaths);
                $db->table('site_settings')->where('key', 'hero_bg_images')->update([
                    'value'      => implode(',', $newList),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        // Handle delete photo single
        $deletePhoto = $this->request->getPost('delete_hero_photo');
        if (!empty($deletePhoto)) {
            $existing = $db->table('site_settings')->where('key', 'hero_bg_images')->get()->getRowArray();
            if ($existing && !empty($existing['value'])) {
                $currentList = array_filter(array_map('trim', explode(',', $existing['value'])));
                $newList = array_filter($currentList, function($img) use ($deletePhoto) {
                    return $img !== $deletePhoto;
                });
                $db->table('site_settings')->where('key', 'hero_bg_images')->update([
                    'value'      => implode(',', $newList),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $redirect = redirect()->to('admin/settings')->with('success', 'Pengaturan website berhasil disimpan!');
        if (! empty($uploadErrors)) {
            $redirect->with('error', 'Sebagian foto hero gagal diupload: ' . implode('; ', $uploadErrors));
        }

        return $redirect;
    }
}
