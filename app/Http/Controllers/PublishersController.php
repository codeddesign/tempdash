<?php

namespace App\Http\Controllers;

/**
 * Class PublishersController
 * @package App\Http\Controllers
 */
class PublishersController extends Controller
{
    public function index() {
        return view('publishers.index', [
            'page_title' => 'Publishers',
            'sub_title' => 'Dashboard - Publishers'
        ]);
    }
}