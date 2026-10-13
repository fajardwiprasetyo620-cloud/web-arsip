<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('action', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%')
                  ->orWhere('created_at', 'LIKE', '%' . $search . '%');
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(50)->withQueryString();

        return view('arsip.logs', compact('logs'));
    }
}
