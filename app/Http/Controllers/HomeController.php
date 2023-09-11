<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Presentation;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $devices = Device::all();
        $presentation = Presentation::all()->keyBy('id')->toArray();
        return view('home', ['devices' => $devices, 'presentation' => $presentation]);
    }
}
