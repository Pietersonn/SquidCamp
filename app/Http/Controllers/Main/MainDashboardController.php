<?php

namespace App\Http\Controllers\main; // Namespace BARU

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainDashboardController extends Controller // Nama Class BARU
{
  public function index()
  {
    // Lokasi view BARU
    return view('main.dashboard');
  }
}
