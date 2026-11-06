<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\DatabaseBackupService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function download(DatabaseBackupService $backupService): BinaryFileResponse
    {
        $path = $backupService->backup();
        $fileName = basename($path);

        ActivityLog::catat('BACKUP', 'mengunduh cadangan database sistem: '.$fileName);

        return response()->download($path, $fileName, [
            'Content-Type' => 'application/sql',
        ]);
    }
}
