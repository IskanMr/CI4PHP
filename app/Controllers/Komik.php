<?php

namespace App\Controllers;

use App\Models\KomikModel;
use CodeIgniter\CLI\Console;

class Komik extends BaseController
{
    protected $komikModel;

    public function __construct()
    {
        $this->komikModel = new KomikModel();
    }

    public function index()
    {
        $data =[
            'title' => 'Daftar Komik',
            'komik' => $this->komikModel->getKomik()
        ];


        return view('komik/index',$data);
    }

    public function detail($slug){
        $data=[
            'title' => 'Detail Komik',
            'komik' => $this->komikModel->getKomik($slug)
        ];

        //jika komik tidak ada di tabel
        if(empty($data['komik'])){
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul komik ' . $slug . ' tidak ditemukan.');
        }

        return view('komik/detail',$data);
    }

    public function create(){
        session();
        $data=[
            'title' => 'Form Tambah Data',
            'validation' => \Config\Services::validation()
        ];
        return view('komik/create', $data);
    }

    public function save(){
        //validasi input
        $rules = [
            'judul' => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} komik sudah terdaftar.'
                ]
                ],
            'penulis' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                ]
                ],
            'penerbit' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                ]
                ],
            'sampul' => [
                'rules' => 'max_size[sampul,4096]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar.',
                    'is_image' => 'Yang anda pilih bukan gambar.',
                    'mime_in' => 'Yang anda pilih bukan gambar.'
                ]
                ]

        ];
        if(!$this->validate($rules)){
            return redirect()->back()->withInput();
        }
            //ambil gambar
            $fileSampul = $this->request->getFile('sampul');
            // apakah tidak ada gambar yang diupload
            if($fileSampul->getError() == 4){
                $namaSampul = 'default.png';
            }else{
            // generate nama file random
            $namaSampul = $fileSampul->getRandomName();
            //pindahkan gambar
            $fileSampul->move('img', $namaSampul);
            }

            $komik = $this->request->getVar();
            $this->komikModel->save([
                'judul' => $komik['judul'],
                'slug' => url_title($komik['judul'],'-',true),
                'penulis' => $komik['penulis'],
                'penerbit' => $komik['penerbit'],
                'sampul' => $namaSampul
            ]);
            session()->setFlashdata('pesan','Data berhasil ditambahkan.');

        return redirect()->to('/komik');
    }

    public function delete($id){

        //cari gambar berdasarkan id
        $komik = $this->komikModel->find($id);
        //cek jika file default.png
        if($komik['sampul'] != 'default.png'){
            //hapus gambar
            unlink('img/'.$komik['sampul']);
        }

        $this->komikModel->delete($id);
        session()->setFlashdata('pesan','Data berhasil dihapus.');
        return redirect()->to('/komik');
    }

    public function edit($slug){
        session();
        $data=[
            'title' => 'Form Ubah Data',
            'validation' => \Config\Services::validation(),
            'komik' => $this->komikModel->getKomik($slug)
        ];
        return view('komik/edit', $data);
    }

    public function update($id){
        // cek judul
        $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));
        $rule_judul = $komikLama['judul'] == $this->request->getVar('judul')? 'required' : 'required|is_unique[komik.judul]';

       //validasi input
       $rules = [
        'judul' => [
            'rules' => $rule_judul,
            'errors' => [
                'required' => '{field} komik harus diisi.',
                'is_unique' => '{field} komik sudah terdaftar.'
            ]
            ],
        'penulis' => [
            'rules' => 'required',
            'errors' => [
                'required' => '{field} komik harus diisi.',
            ]
            ],
        'penerbit' => [
            'rules' => 'required',
            'errors' => [
                'required' => '{field} komik harus diisi.',
            ]
            ],
        'sampul' => [
            'rules' => 'max_size[sampul,4096]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Ukuran gambar terlalu besar.',
                'is_image' => 'Yang anda pilih bukan gambar.',
                'mime_in' => 'Yang anda pilih bukan gambar.'
            ]
            ]
    ];

    if(!$this->validate($rules)){
        return redirect()->back()->withInput();
    }
    $fileSampul = $this->request->getFile('sampul');
    //cek gambar, apakah tetap gambar lama
    if($fileSampul->getError() == 4){
        $namaSampul = $this->request->getVar('sampulLama');
    }else{
        //generate nama file random
        $namaSampul = $fileSampul->getRandomName();
        //pindahkan gambar
        $fileSampul->move('img', $namaSampul);
        //hapus file yang lama
        unlink('img/'.$this->request->getVar('sampulLama'));
    }
        $komik = $this->request->getVar();
        $this->komikModel->save([
            'id' => $id,
            'judul' => $komik['judul'],
            'slug' => url_title($komik['judul'],'-',true),
            'penulis' => $komik['penulis'],
            'penerbit' => $komik['penerbit'],
            'sampul' => $namaSampul
        ]);
        session()->setFlashdata('pesan','Data berhasil diubah.');
    return redirect()->to('/komik');
    }
}