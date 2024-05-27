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
        $serializedHtml = $request->input('html');
        $htmlContent = json_decode($serializedHtml, true)['html'];

        $pdfFileName = Str::random(10) . '.pdf';
        $pdfPath = storage_path('app/public/' . $pdfFileName);
        $pdf = Pdf::html($htmlContent)
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot
                    ->setNodeBinary(env('NODE_BINARY_PATH'))
                    ->setNpmBinary(env('NPM_BINARY_PATH'))
                    ->timeout(100000);
            })
            ->format('a4')
            ->orientation('Landscape')
            ->save($pdfPath);

        return response()->download($pdfPath, 'page.pdf')->deleteFileAfterSend(false);

    }
}