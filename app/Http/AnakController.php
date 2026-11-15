<?php

namespace App\Http\Controllers;

use App\Models\Anak;
use Illuminate\Http\Request;

class AnakController extends Controller
{
    public function index()
    {
        $anak = Anak::with('wali')->get(); // Mengambil data anak beserta wali
        return view('anak.index', compact('anak'));
    }
}
