<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Jobs\PmgiRawMasterJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Serves a finished PMGI raw master CSV over a normal HTTP request.
 *
 * The file runs to ~90 MB, so it must not be returned from a Livewire action
 * (Livewire inlines action downloads into the JSON response payload).
 */
class PmgiRawMasterDownloadController extends Controller
{
    public function __invoke(string $token): StreamedResponse
    {
        // The key embeds the requesting user, so one user can never fetch another's export.
        $status = Cache::get(PmgiRawMasterJob::cacheKey(auth()->user()->USERID, $token));

        abort_if(! $status || ($status['status'] ?? null) !== 'ready', 404, 'Laporan tidak ditemui atau belum siap.');

        $disk = Storage::disk(PmgiRawMasterJob::DISK);

        abort_unless($disk->exists($status['path']), 404, 'Fail laporan sudah tamat tempoh. Sila jana semula.');

        return $disk->download($status['path'], $status['filename'], [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
