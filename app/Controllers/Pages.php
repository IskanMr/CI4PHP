<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $data =[
            'title' => 'Home | CI 4 App',
            'test' => ['satu', 'dua', 'tiga']
        ];
        echo view('pages/home',$data);
    }

    public function about()
    {
        $data =[
            'title' => 'About me | CI 4 App'
        ];
        echo view('pages/about', $data);
    }

    public function contact()
    {
        $data =[
            'title' => 'Contact | CI 4 App',
            'alamat' => [
                [
                    'tipe' => 'rumah',
                    'alamat' => 'Jl. abc No. 123',
                    'kota' => 'Bandung'
                ],
                [
                    'tipe' => 'kantor',
                    'alamat' => 'Jl. Setiabudi No. 193',
                    'kota' => 'Bandung'
                ]
            ]
        ];
        echo view('pages/contact', $data);
    }
}
