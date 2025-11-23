<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    // Method ini dipanggil oleh route 'admin.dashboard'
    public function index()
    {
        // Ini akan memuat view di resources/views/admin/dashboard.blade.php
        return view('admin.index');
    }
}
