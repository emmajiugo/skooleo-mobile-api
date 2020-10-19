<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\WebSettings;

class WebSettingsController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return WebSettings::first();
    }
}
