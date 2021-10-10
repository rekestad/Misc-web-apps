<?php

namespace App\Http\Controllers;

use App\Models\AdminApp;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $data = [
            'title' => 'Apps',
            'apps'  => AdminApp::where([
                            ['is_home_app','=',0],
                            ['is_active','=',1],
                            ['is_development','=',0]
                        ])->orderBy('sort_order')->get()
        ];

        return view('home')->with($data);
    }
}
