<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class FileUploadController extends BaseController
{
   
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpeg,png,pdf|max:2048',
            'folder' => 'required|in:FolderPhVehicule,FolderPhCin,FolderPhPieceJoindre,FolderOffres',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }

        $folder = $request->input('folder');
        $file = $request->file('file');
        $originalFileName = $file->getClientOriginalName();
        $cleanedFileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = "{$folder}/{$cleanedFileName}";
        Storage::disk('public')->put($filePath, file_get_contents($file));
       
        // Automatically generate URLs
        $fileUrl = asset('storage/' . $filePath);
        $downloadUrl = route('download', ['folder' => $folder, 'filename' => $cleanedFileName]);

        return $this->sendResponse([
            'message' => 'File uploaded successfully',
            'file_url' => $fileUrl,
            'download_url' => $downloadUrl
        ], 'File uploaded successfully', 200);
    }


    public function download($folder, $filename)
    {
        if (!in_array($folder, ['FolderPhVehicule', 'FolderPhCin', 'FolderPhPieceJoindre', 'FolderOffres'])) {
            return response()->json(['error' => 'Invalid folder name'], 400);
        }

        $filePath = "{$folder}/{$filename}";

        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $file = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);

        return response($file)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', "attachment; filename=$filename");
    }
    public function remove($folder, $filename)
    {
        if (!in_array($folder, ['FolderPhVehicule', 'FolderPhCin', 'FolderPhPieceJoindre', 'FolderOffres'])) {
            return $this->sendError('Invalid folder name', [], 400);
        }

        $filePath = "{$folder}/{$filename}";

        if (!Storage::disk('public')->exists($filePath)) {
            return $this->sendError('File not found', [], 404);
        }

        Storage::disk('public')->delete($filePath);

        return $this->sendResponse([], 'File removed successfully', 200);
    }
     

}
