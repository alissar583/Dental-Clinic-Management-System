<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Matrix\Decomposition\QR;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Class QrService.
 */
class QrService
{
    public function generate($data)
    {
        $qrCode = QrCode::size(200)
            ->format('png')
            ->generate($data);

        $filename = uniqid('qr-code-') . '.png'; // Generate a unique filename

        $url = Storage::disk('public')->put('qrs/' . $filename, $qrCode, 'public');

        $path = 'storage/qrs/' . $filename;

        return asset($path);
    }
}
