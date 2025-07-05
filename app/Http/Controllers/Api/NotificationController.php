<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->latest()->get();
        return response()->json($notifications);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'tipe' => 'required|in:Info,Peringatan,Promosi,Transaksi,Sistem',
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string',
            'link' => 'nullable|url',
            'is_read' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $notification = Notification::create($validator->validated());
        return response()->json($notification, 201);
    }

    public function show($id)
    {
        $notification = Notification::with('user')->findOrFail($id);
        return response()->json($notification);
    }

    public function update(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|exists:users,id',
            'tipe' => 'sometimes|required|in:Info,Peringatan,Promosi,Transaksi,Sistem',
            'judul' => 'sometimes|required|string|max:255',
            'pesan' => 'sometimes|required|string',
            'link' => 'nullable|url',
            'is_read' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $notification->update($validator->validated());
        return response()->json($notification);
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json(['message' => 'Notifikasi berhasil dihapus.']);
    }
}
