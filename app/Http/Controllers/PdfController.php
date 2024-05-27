<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;
use Str;

class PdfController extends Controller
{
    public function generateQuotePdf(Request $request)
    {
        $htmlContent = $request->input('html');
        $pdfPath = storage_path('app/public/' . Str::random(10) . '.pdf');
        $pdf = Pdf::html($htmlContent)
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot
                    ->setNodeBinary(env('NODE_BINARY_PATH'))
                    ->setNpmBinary(env('NPM_BINARY_PATH'));
            })
            ->format('a4')
            ->save($pdfPath);

        return response()->download($pdfPath, 'page.pdf')->deleteFileAfterSend(false);

    }
}