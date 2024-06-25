<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class TestController extends Controller
{
    public function generatePDF()
    {
       $users = User::with('role')->get();

       $pdf = PDF::loadView('reports.forwarding_letter', compact('users'));

       return $pdf->stream('forwarding_letter.pdf',array('Attachment'=>0));
    }
}
