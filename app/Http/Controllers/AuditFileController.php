<?php

namespace App\Http\Controllers;

use App\Models\AuditReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuditFileController extends Controller
{
    //
    public function show(AuditReports $audit)
    {
        if (!Storage::disk('local')->exists($audit->lhp_document_path)) {
            abort(404, 'file_not_found');
        }
    }
}
