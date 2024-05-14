<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $paths = [];
        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                $filename = $file->getClientOriginalName();
                $folder = uniqid() . now()->timestamp;
                $paths[] = $file->storeAs('temp/' . $folder, $filename);
            }
        }
        return $paths;
    }

    public function delete(Request $request)
    {
        $filename = json_decode($request->getContent(), true);
        $filename = reset($filename);
        $folderName = explode('/', $filename)[1];
        Storage::deleteDirectory('temp/' . $folderName);
        return response()->noContent();
    }

    public function deleteTemp()
    {
        if (Storage::disk('public')->exists('temp')) {
            Storage::disk('public')->deleteDirectory('temp');
        }

        session()->flash('success', 'Deleted Successfully !');

        return redirect()->back();
    }
}
