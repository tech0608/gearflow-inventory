<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q', '');
        $action = $request->get('action', 'all');

        $logs = ActivityLog::when($q, function ($query) use ($q) {
                    $query->where('description', 'like', "%$q%")
                          ->orWhere('nama_user', 'like', "%$q%");
                })
                ->when($action !== 'all', function ($query) use ($action) {
                    $query->where('action', strtoupper($action));
                })
                ->orderByDesc('created_at')->orderByDesc('id')
                ->paginate(15)->withQueryString();

        return view('activity-log.index', compact('logs', 'q', 'action'));
    }
}
