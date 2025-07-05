<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    public function index()
    {
        return response()->json(
            Document::with(['nasabah', 'pembiayaan', 'uploader'])->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'nullable|exists:nasabahs,id',
            'pembiayaan_id' => 'nullable|exists:pembiayaans,id',
            'jenis_dokumen' => 'required|string|max:100',
            'file' => 'required|file|max:10240', // maks 10MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        $document = Document::create([
            'nasabah_id' => $request->nasabah_id,
            'pembiayaan_id' => $request->pembiayaan_id,
            'jenis_dokumen' => $request->jenis_dokumen,
            'nama_file_asli' => $file->getClientOriginalName(),
            'path_file' => $path,
            'mime_type' => $file->getClientMimeType(),
            'ukuran_file_bytes' => $file->getSize(),
            'diupload_oleh_user_id' => $request->user()->id,
        ]);

        return response()->json($document, 201);
    }

    public function show($id)
    {
        $document = Document::with(['nasabah', 'pembiayaan', 'uploader'])->findOrFail($id);
        return response()->json($document);
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        // Debug log untuk melihat isi request
        Log::info('Update Request Data', [
            'input' => $request->all(),
            'has_file' => $request->hasFile('file'),
        ]);

        $validator = Validator::make($request->all(), [
            'jenis_dokumen' => 'sometimes|string|max:100',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updateData = [];

        if ($request->has('jenis_dokumen')) {
            $updateData['jenis_dokumen'] = $request->input('jenis_dokumen');
        }

        if ($request->hasFile('file')) {
            if ($document->path_file && Storage::disk('public')->exists($document->path_file)) {
                Storage::disk('public')->delete($document->path_file);
            }

            $file = $request->file('file');
            $path = $file->store('documents', 'public');

            $updateData = array_merge($updateData, [
                'nama_file_asli' => $file->getClientOriginalName(),
                'path_file' => $path,
                'mime_type' => $file->getClientMimeType(),
                'ukuran_file_bytes' => $file->getSize(),
            ]);
        }

        if (!empty($updateData)) {
            $document->update($updateData);
        }

        return response()->json($document->fresh());
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        if ($document->path_file && Storage::disk('public')->exists($document->path_file)) {
            Storage::disk('public')->delete($document->path_file);
        }

        $document->delete();

        return response()->json(['message' => 'Dokumen berhasil dihapus.']);
    }
}
