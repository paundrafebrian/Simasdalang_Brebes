<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KanbanController extends Controller
{
    //
    public function index()
    {
       

        // Kirim data ke tampilan admin
        return view('kanban.index', compact('kanban'));
    }
}
