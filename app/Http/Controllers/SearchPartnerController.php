<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchPartnerController extends Controller
{
    public function index()
    {
        return view('partners');
    }
}
