<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\JsonStorage;

class PresentationController extends BaseController
{
    public function view($slug)
    {
        $tutorials = JsonStorage::where('tutorials', 'url_presentation', $slug);
        
        if (empty($tutorials)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Tutorial tidak ditemukan'
            );
        }
        
        $tutorial = $tutorials[0];
        
        $details = JsonStorage::whereAll('details', [
            'tutorial_id' => $tutorial['id'],
            'status' => 'show',
        ]);
        
        usort($details, function($a, $b) {
            return $a['order'] - $b['order'];
        });

        return view('v_presentation', [
            'tutorial' => $tutorial,
            'details' => $details,
            'slug' => $slug,
        ]);
    }

    public function data($slug)
    {
        $tutorials = JsonStorage::where('tutorials', 'url_presentation', $slug); //cari tutorial berdasarkan slug URL
        
        if (empty($tutorials)) {
            return $this->response->setJSON([
                'status' => 'not_found',
                'message' => 'Tutorial tidak ditemukan',
            ])->setStatusCode(404);
        }

        $tutorial = $tutorials[0];
        
        $details = JsonStorage::whereAll('details', [  //ambil semua detail dengan tutorial_id yang sama dan status show
            'tutorial_id' => $tutorial['id'],
            'status' => 'show',
        ]);

        usort($details, function($a, $b) {
            return $a['order'] - $b['order'];
        });

        return $this->response->setJSON([   // kembalikan sebagai JSON response
            'status' => 'success',
            'tutorial' => [
                'id' => $tutorial['id'],
                'judul' => $tutorial['judul'],
                'nama_matkul' => $tutorial['nama_matkul'],
            ],
            'details' => $details,
            'timestamp' => time(),
        ]);
    }
}
