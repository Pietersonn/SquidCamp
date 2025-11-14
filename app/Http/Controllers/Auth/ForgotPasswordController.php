<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller // Nama Class diubah
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    // Lokasi view diubah
    return view('auth.forgot-password', ['pageConfigs' => $pageConfigs]);
  }
}
