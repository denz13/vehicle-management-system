<?php

namespace App\Http\Controllers\profilemanagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileManagementController extends Controller
{
    //
    public function index()
    {
        return view('profile-management.profile-management');
    }
}
