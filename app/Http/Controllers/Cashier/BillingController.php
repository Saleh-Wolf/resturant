<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    //
    public function index()
    {
         return view('cashier.dashboard');
    }
}
