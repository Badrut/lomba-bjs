<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        return response()->json(ActivityLog::latest()->paginate(20));
    }

    public function show($id)
    {
        return response()->json(ActivityLog::findOrFail($id));
    }
}

