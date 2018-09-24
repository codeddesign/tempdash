<?php

namespace App\Http\Controllers;

/**
 * Class TransparencyController
 * @package App\Http\Controllers
 */
class TransparencyController extends Controller
{
    /**
     * GET main transparency page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('transparency/index', [
            'page_title' => 'Transparency',
            'sub_title' => 'Dashboard - Transparency'
        ]);
    }
}