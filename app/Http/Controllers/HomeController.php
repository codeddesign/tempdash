<?php

namespace App\Http\Controllers;

use Auth;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * GET home index page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        if ($app_user = Auth::getCurrentUser())
        {
            return view('home.dashboard', [
                'app_user' => $app_user,
                'page_title' => 'Dashboard Overview',
                'sub_title' => 'Dashboard - Overview'
            ]);
        }
        else
        {
            return redirect()->route('auth_login');
        }
    }
}