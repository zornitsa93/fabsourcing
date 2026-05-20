<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function dashboard()
    {
        return redirect()->route('pages.index');
        return view('admin.dashboard');
    }

}
