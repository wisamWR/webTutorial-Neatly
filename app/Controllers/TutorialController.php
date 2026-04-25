<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\JsonStorage;
use App\Libraries\JwtService;

class TutorialController extends BaseController
{
    public function __construct()
    {
        helper('form');
    }

    public function index()
    {
        $tutorials = JsonStorage::read('tutorials');

        // Sort by created_at descending (terbaru di atas)
        usort($tutorials, function ($a, $b) {
            $dateA = $a['created_at'] ?? '';
            $dateB = $b['created_at'] ?? '';
            return $dateB <=> $dateA;
        });

        return view('v_tutorial_index', ['tutorials' => $tutorials]);
    }

    public function create()
    {
        $token = session()->get('refreshToken');
        $makul = [];
        if ($token) {
            $makul = JwtService::getMakul($token);
        }

        if (empty($makul)) {
            session()->setFlashdata('failed', 'Gagal mengambil data mata kuliah. Silakan login ulang.');
        }

        return view('v_tutorial_form', [
            'makul_list' => $makul,
            'tutorial' => null,
            'action' => 'create'
        ]);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required|min_length[3]|max_length[200]',
            'kode_matkul' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('failed', 'Validasi gagal: ' . implode(', ', $validation->getErrors()));
            return redirect()->back()->withInput();
        }

        $judul = $this->request->getPost('judul');
        $kode_matkul = $this->request->getPost('kode_matkul');

        // Lookup nama_matkul based on kode_matkul
        $token = session()->get('refreshToken');
        $makul_list = [];
        if ($token) {
            $makul_list = JwtService::getMakul($token);
        }

        $nama_matkul = '';
        foreach ($makul_list as $m) {
            if ($m['kdmk'] == $kode_matkul) {
                $nama_matkul = $m['nama'];
                break;
            }
        }

        $urls = generate_unique_urls($judul); //create unique url masing masing presentasi

        $data = [
            'judul' => $judul,
            'kode_matkul' => $kode_matkul,
            'nama_matkul' => $nama_matkul,
            'url_presentation' => $urls['url_presentation'], // create url unik dari slug dan 15 digit acak
            'url_finished' => $urls['url_finished'],
            'creator_email' => session()->get('email')
        ];

        JsonStorage::insert('tutorials', $data);

        session()->setFlashdata('success', 'Tutorial berhasil ditambahkan');
        return redirect()->to('/tutorial');
    }

    public function edit($id)
    {
        $tutorial = JsonStorage::find('tutorials', $id);

        if ($tutorial === null) {
            session()->setFlashdata('failed', 'Tutorial tidak ditemukan');
            return redirect()->to('/tutorial');
        }

        $token = session()->get('refreshToken');
        $makul = [];
        if ($token) {
            $makul = JwtService::getMakul($token);
        }

        return view('v_tutorial_form', [
            'tutorial' => $tutorial,
            'makul_list' => $makul,
            'action' => 'edit'
        ]);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required|min_length[3]|max_length[200]',
            'kode_matkul' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('failed', 'Validasi gagal: ' . implode(', ', $validation->getErrors()));
            return redirect()->back()->withInput();
        }

        $tutorialLama = JsonStorage::find('tutorials', $id);
        if ($tutorialLama === null) {
            session()->setFlashdata('failed', 'Tutorial tidak ditemukan');
            return redirect()->to('/tutorial');
        }

        $judul = $this->request->getPost('judul');
        $kode_matkul = $this->request->getPost('kode_matkul');

        // Lookup nama_matkul
        $token = session()->get('refreshToken');
        $makul_list = [];
        if ($token) {
            $makul_list = JwtService::getMakul($token);
        }
        $nama_matkul = '';
        foreach ($makul_list as $m) {
            if ($m['kdmk'] == $kode_matkul) {
                $nama_matkul = $m['nama'];
                break;
            }
        }

        $data = [
            'judul' => $judul,
            'kode_matkul' => $kode_matkul,
            'nama_matkul' => $nama_matkul,
            'url_presentation' => $tutorialLama['url_presentation'],
            'url_finished' => $tutorialLama['url_finished'],
            'creator_email' => $tutorialLama['creator_email']
        ];

        if (JsonStorage::update('tutorials', $id, $data)) {
            session()->setFlashdata('success', 'Tutorial berhasil diupdate');
        } else {
            session()->setFlashdata('failed', 'Gagal update tutorial - tidak ditemukan');
        }

        return redirect()->to('/tutorial');
    }

    public function delete($id)
    {
        // Ambil tutorial
        $tutorial = JsonStorage::find('tutorials', $id);
        if (!$tutorial) {
            session()->setFlashdata('failed', 'Tutorial tidak ditemukan');
            return redirect()->to('tutorial');
        }

        // Cascade: ambil semua detail terkait, hapus file gambar, lalu hapus detail
        $details = JsonStorage::whereAll('details', ['tutorial_id' => $id]);
        foreach ($details as $detail) {
            if (!empty($detail['gambar'])) {
                delete_detail_image($detail['gambar']);
            }
            JsonStorage::delete('details', $detail['id']);
        }

        // Hapus master
        JsonStorage::delete('tutorials', $id);

        session()->setFlashdata('success', 'Tutorial dan semua detailnya berhasil dihapus');
        return redirect()->to('tutorial');
    }

    public function detail($id)
    {
        $data = get_tutorial_and_details($id);

        if ($data['tutorial'] === null) {
            session()->setFlashdata('failed', 'Tutorial tidak ditemukan');
            return redirect()->to('/tutorial');
        }

        return view('v_tutorial_detail', [
            'tutorial' => $data['tutorial'],
            'details' => $data['details'],
        ]);
    }
}
