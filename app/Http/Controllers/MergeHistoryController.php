<?php

namespace App\Http\Controllers;

use App\Models\MergeHistory;
use Illuminate\Http\Request;

class MergeHistoryController extends Controller
{
    public function index()
    {
        $history = MergeHistory::with(['masterContact', 'mergedContact'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('merge-history.index', compact('history'));
    }
}



