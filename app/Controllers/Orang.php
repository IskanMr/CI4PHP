<?php

namespace App\Controllers;

use App\Models\OrangModel;
use CodeIgniter\CLI\Console;

class Orang extends BaseController
{
    protected $orangModel;

    public function __construct()
    {
        $this->orangModel = new orangModel();
    }


    public function index()
    {
        $currentPage = $this->request->getVar('page_orang') ? $this->request->getVar('page_orang') : 1;
        $totalView = 10;

        $keyword = $this->request->getVar('keyword');
        if($keyword){
            $orang = $this->orangModel->search($keyword);
        }else{
            $orang = $this->orangModel;
        }

        $data =[
            'title' => 'Daftar Orang',
            // 'orang' => $this->orangModel->findAll()
            'orang' => $orang->paginate($totalView, 'orang'),
            'pager' => $this->orangModel->pager,
            'currentPage' => $currentPage,
            'totalView' => $totalView,
        ];


        return view('orang/index',$data);
    }
}