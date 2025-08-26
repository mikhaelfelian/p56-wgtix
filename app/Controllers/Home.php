<?php
/**
 * Home Controller
 * 
 * Created by Mikhael Felian Waskito
 * Created at 2024-01-09
 */

namespace App\Controllers;


class Home extends BaseController
{
    protected $itemModel;

    public function __construct()
    {
    }

    public function index()
    {
        $data = [
            'title'        => 'Dashboard',
            'Pengaturan'   => $this->pengaturan,
            'user'         => $this->ionAuth->user()->row(),
            'isMenuActive' => isMenuActive('dashboard') ? 'active' : ''
        ];

        return view($this->theme->getThemePath() . '/home', $data);
    }
}
