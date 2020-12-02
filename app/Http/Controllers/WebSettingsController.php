<?php

namespace App\Http\Controllers;

use App\WebSettings;

use App\Traits\Skooleo;
use Illuminate\Http\Request;

class WebSettingsController extends Controller
{
    use Skooleo;

    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['support', 'ecommStore']]);
    }

    public function index()
    {
        return WebSettings::first();
    }

    public function support()
    {
        return view('live-chat');
    }

    public function ecommStore()
    {
        $ecommStore = WebSettings::first()->ecomm_store;

        return response()->json($this->customResponse("success", "Store link", $ecommStore));
    }
}
