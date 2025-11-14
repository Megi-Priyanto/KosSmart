<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        return view('admin.billing.index');
    }

    public function create()
    {
        return view('admin.billing.create');
    }

    public function store(Request $request)
    {
        // logika menyimpan tagihan
    }
}
