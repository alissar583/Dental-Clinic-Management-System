<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function upload($file, string $path): string
    {
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($path, $fileName, 'public');
        return 'storage/' . $path;
    }

    public static function delete(string $path): void
    {
        Storage::disk('public')->delete($path);
    }

    public static function replace(UploadedFile $file, string $oldPath, $newPath): string
    {
        self::delete($oldPath);
        return self::upload($file, $newPath);
    }

    public static function generateOid(Model $model)
    {

        $today = date('Ymd');
        $oids = $model::where('oid', 'like', $today . '%')->pluck('oid');
        do {
            $oid = $today . rand(10000, 99999);
        } while ($oids->contains($oid));
        return $oid;
    }
}
