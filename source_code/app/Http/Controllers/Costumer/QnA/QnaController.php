<?php

namespace App\Http\Controllers\Costumer\QnA;

use App\Http\Controllers\Controller;
use App\Models\Qa;
use Illuminate\Http\Request;

class QnaController extends Controller
{
    public function index()
    {
        $qna = Qa::all();
        return view('customer.QnA.index', compact('qna'));
    } 
}
