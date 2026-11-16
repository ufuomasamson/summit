<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Serve branding and profile photos through Laravel.
 *
 * Hardened Nginx/aaPanel configs often deny direct HTTP access to /storage/*.
 * Routing images through /media/... avoids that while still blocking KYC uploads/.
 */
class MediaController extends Controller
{
    /**
     * @param  string  $folder  branding|photos
     * @param  string  $file    basename only
     */
    public function show(string $folder, string $file): BinaryFileResponse
    {
        $folder = basename(str_replace('\\', '/', $folder));
        $file = basename(str_replace('\\', '/', urldecode($file)));

        if (!in_array($folder, ['branding', 'photos'], true)) {
            abort(404);
        }

        // Disallow path tricks; allow common uploaded filename characters
        if ($file === '' || $file === '.' || $file === '..' || strpos($file, "\0") !== false) {
            abort(404);
        }

        $candidates = [];

        if ($folder === 'branding') {
            $candidates[] = public_path('branding/' . $file);
            $candidates[] = storage_path('app/public/branding/' . $file);
            $candidates[] = storage_path('app/public/photos/' . $file);
            $candidates[] = storage_path('app/public/' . $file);
        } else {
            $candidates[] = storage_path('app/public/photos/' . $file);
            $candidates[] = storage_path('app/public/' . $file);
            $candidates[] = public_path('branding/' . $file);
        }

        foreach ($candidates as $absolute) {
            $realBase = realpath(dirname($absolute));
            $allowedBases = array_filter([
                realpath(public_path('branding')),
                realpath(storage_path('app/public')),
                realpath(storage_path('app/public/photos')),
                realpath(storage_path('app/public/branding')),
            ]);

            if ($realBase === false || !is_file($absolute)) {
                continue;
            }

            $allowed = false;
            foreach ($allowedBases as $base) {
                if ($base && strpos($realBase, $base) === 0) {
                    $allowed = true;
                    break;
                }
            }

            if (!$allowed) {
                continue;
            }

            return response()->file($absolute, [
                'Cache-Control' => 'public, max-age=604800',
            ]);
        }

        abort(404);
    }
}
