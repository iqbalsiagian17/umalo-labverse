<?php

namespace App\Http\Controllers\Costumer\Faq;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqCustomerController extends Controller
{
    public function index()
    {
        $faq = Faq::all();
        return view('customer.faq.index', compact('faq'));
    }
}
