<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\JsonStorage;

class DetailController extends BaseController
{
    public function __construct()
    {
        helper(['form', 'tutorial']);
    }

    public function store($tutorialId)
    {
        $tutorial = JsonStorage::find('tutorials', $tutorialId);
        if ($tutorial === null) {
            session()->setFlashdata('failed', 'Tutorial tidak ditemukan');
            return redirect()->to('/tutorial');
        }

        $type = $this->request->getPost('type');
        $validTypes = ['text', 'image', 'code', 'url'];
        if (!in_array($type, $validTypes)) {
            session()->setFlashdata('failed', 'Tipe detail tidak valid');
            return redirect()->back();
        }

        $data = [
            'tutorial_id' => (int)$tutorialId,
            'type' => $type,
            'text' => null,
            'gambar' => null,
            'code' => null,
            'code_language' => null,
            'url' => null,
            'order' => JsonStorage::maxOrder('details', ['tutorial_id' => $tutorialId]) + 1,
            'status' => 'show',
        ];

        if ($type === 'text') {
            $text = $this->request->getPost('text');
            if (empty($text)) {
                session()->setFlashdata('failed', 'Teks wajib diisi');
                return redirect()->back()->withInput();
            }
            $data['text'] = $text;
        } elseif ($type === 'url') {
            $url = $this->request->getPost('url');
            if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
                session()->setFlashdata('failed', 'URL tidak valid');
                return redirect()->back()->withInput();
            }
            $data['url'] = $url;
        } elseif ($type === 'code') {
            $code = $this->request->getPost('code');
            if (empty($code)) {
                session()->setFlashdata('failed', 'Kode wajib diisi');
                return redirect()->back()->withInput();
            }
            $data['code'] = $code;
            $data['code_language'] = $this->request->getPost('code_language') ?: 'plaintext';
        } elseif ($type === 'image') {
            $file = $this->request->getFile('gambar');
            if (!$file || !$file->isValid() || $file->hasMoved()) {
                session()->setFlashdata('failed', 'Upload gambar gagal');
                return redirect()->back();
            }

            $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array(strtolower($file->getExtension()), $allowedExts)) {
                session()->setFlashdata('failed', 'Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WebP.');
                return redirect()->back();
            }

            if ($file->getSize() > 2 * 1024 * 1024) {
                session()->setFlashdata('failed', 'Ukuran file maksimal 2MB');
                return redirect()->back();
            }
        }

        // Insert first to get ID
        $result = JsonStorage::insert('details', $data);

        // Process image after knowing ID
        if ($type === 'image') {
            $file = $this->request->getFile('gambar');
            $filename = generate_image_filename($result['id'], $file->getName());
            $file->move(FCPATH . 'uploads', $filename);
            JsonStorage::update('details', $result['id'], ['gambar' => $filename]);
        }

        session()->setFlashdata('success', 'Detail berhasil ditambahkan');
        return redirect()->to('/tutorial/detail/' . $tutorialId);
    }

    public function edit($id)
    {
        $detail = JsonStorage::find('details', $id);
        if ($detail === null) {
            session()->setFlashdata('failed', 'Detail tidak ditemukan');
            return redirect()->to('/tutorial');
        }

        $tutorial = JsonStorage::find('tutorials', $detail['tutorial_id']);
        
        return view('v_detail_form', [
            'detail' => $detail,
            'tutorial' => $tutorial,
        ]);
    }

    public function update($id)
    {
        $detail = JsonStorage::find('details', $id);
        if ($detail === null) {
            session()->setFlashdata('failed', 'Detail tidak ditemukan');
            return redirect()->to('/tutorial');
        }

        $tutorialId = $detail['tutorial_id'];
        $type = $this->request->getPost('type');
        $validTypes = ['text', 'image', 'code', 'url'];
        if (!in_array($type, $validTypes)) {
            session()->setFlashdata('failed', 'Tipe detail tidak valid');
            return redirect()->back();
        }

        $update = [
            'type' => $type,
            'text' => null,
            'gambar' => $detail['gambar'],
            'code' => null,
            'code_language' => null,
            'url' => null,
        ];

        if ($type === 'text') {
            $text = $this->request->getPost('text');
            if (empty($text)) {
                session()->setFlashdata('failed', 'Teks wajib diisi');
                return redirect()->back()->withInput();
            }
            $update['text'] = $text;
        } elseif ($type === 'url') {
            $url = $this->request->getPost('url');
            if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
                session()->setFlashdata('failed', 'URL tidak valid');
                return redirect()->back()->withInput();
            }
            $update['url'] = $url;
        } elseif ($type === 'code') {
            $code = $this->request->getPost('code');
            if (empty($code)) {
                session()->setFlashdata('failed', 'Kode wajib diisi');
                return redirect()->back()->withInput();
            }
            $update['code'] = $code;
            $update['code_language'] = $this->request->getPost('code_language') ?: 'plaintext';
        }

        if ($type === 'image') {
            $file = $this->request->getFile('gambar');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array(strtolower($file->getExtension()), $allowedExts)) {
                    session()->setFlashdata('failed', 'Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WebP.');
                    return redirect()->back();
                }

                if ($file->getSize() > 2 * 1024 * 1024) {
                    session()->setFlashdata('failed', 'Ukuran file maksimal 2MB');
                    return redirect()->back();
                }

                delete_detail_image($detail['gambar']);

                $filename = generate_image_filename($id, $file->getName());
                $file->move(FCPATH . 'uploads', $filename);
                $update['gambar'] = $filename;
            }
        } elseif ($detail['gambar'] !== null) {
            delete_detail_image($detail['gambar']);
            $update['gambar'] = null;
        }

        JsonStorage::update('details', $id, $update);
        session()->setFlashdata('success', 'Detail berhasil diupdate');
        return redirect()->to('/tutorial/detail/' . $tutorialId);
    }

    public function delete($id)
    {
        $detail = JsonStorage::find('details', $id);
        if ($detail === null) {
            return redirect()->to('/tutorial');
        }

        $tutorialId = $detail['tutorial_id'];
        
        if (!empty($detail['gambar'])) {
            delete_detail_image($detail['gambar']);
        }

        JsonStorage::delete('details', $id);
        
        session()->setFlashdata('success', 'Detail berhasil dihapus');
        return redirect()->to('/tutorial/detail/' . $tutorialId);
    }

    public function toggle($id) //auto update status show/hide (dari sisi creator di database)
    {
        $detail = JsonStorage::find('details', $id);
        if ($detail === null) {
            return redirect()->to('/tutorial');
        }

        $newStatus = ($detail['status'] === 'show') ? 'hide' : 'show';
        JsonStorage::update('details', $id, ['status' => $newStatus]); // update status di storage jadi show atau hide

        session()->setFlashdata('success', "Status diubah menjadi {$newStatus}");
        return redirect()->to('/tutorial/detail/' . $detail['tutorial_id']);
    }

    public function move($id, $direction)
    {
        $detail = JsonStorage::find('details', $id);
        if ($detail === null) {
            return redirect()->to('/tutorial');
        }

        $tutorialId = $detail['tutorial_id'];
        $allDetails = JsonStorage::whereAll('details', ['tutorial_id' => $tutorialId]);
        
        usort($allDetails, function($a, $b) {
            $orderA = $a['order'] ?? 0;
            $orderB = $b['order'] ?? 0;
            return $orderA <=> $orderB;
        });

        $currentIndex = -1;
        foreach ($allDetails as $index => $item) {
            if ($item['id'] == $id) {
                $currentIndex = $index;
                break;
            }
        }

        if ($currentIndex !== -1) {
            $swapWithIndex = -1;
            
            if ($direction === 'up' && $currentIndex > 0) {
                $swapWithIndex = $currentIndex - 1;
            } elseif ($direction === 'down' && $currentIndex < count($allDetails) - 1) {
                $swapWithIndex = $currentIndex + 1;
            }

            if ($swapWithIndex !== -1) {
                $currentOrder = $allDetails[$currentIndex]['order'];
                $targetOrder = $allDetails[$swapWithIndex]['order'];

                JsonStorage::update('details', $allDetails[$currentIndex]['id'], ['order' => $targetOrder]);
                JsonStorage::update('details', $allDetails[$swapWithIndex]['id'], ['order' => $currentOrder]);
                
                session()->setFlashdata('success', 'Urutan berhasil diubah');
            }
        }

        return redirect()->to('/tutorial/detail/' . $tutorialId);
    }

    public function getDetailsJson($tutorialId)
    {
        $details = JsonStorage::whereAll('details', [
            'tutorial_id' => (int)$tutorialId,
            'status' => 'show'
        ]);
        
        usort($details, function($a, $b) {
            $orderA = $a['order'] ?? 0;
            $orderB = $b['order'] ?? 0;
            return $orderA <=> $orderB;
        });

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $details,
            'timestamp' => time(),
        ]);
    }
}
