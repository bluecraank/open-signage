<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::orderBy('created_at', 'desc')->get()->take(100);
        return view('logs.index', compact('logs'));
    }
}
