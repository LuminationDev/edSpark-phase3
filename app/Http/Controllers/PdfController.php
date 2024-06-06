<?php

namespace App\Http\Controllers;

use App\Models\Quote;
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

        $quoteId = $request->input('quote_id');

        $pdfFileName = Str::random(10) . '.pdf';
        $pdfPath = storage_path('app/public/quotes/' . $pdfFileName);
        $pdf = Pdf::html($htmlContent)
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot
                    ->scale(0.68)
                    ->setOption('title', 'Classroom quote')
                    ->setNodeBinary(env('NODE_BINARY_PATH'))
                    ->setNpmBinary(env('NPM_BINARY_PATH'))
                    ->addChromiumArguments(['no-sandbox'])
                    ->timeout(100000);

            })
            ->format('a4')
            ->orientation('Landscape')
            ->save($pdfPath);


        // Get the URL for the PDF file
        $pdfUrl = asset('storage/quotes/' . $pdfFileName);

        // Update the Quote model with the PDF URL
        Quote::find($quoteId)->update(['pdf_url' => $pdfUrl]);

        return response()->download($pdfPath, 'page.pdf')->deleteFileAfterSend(false);

    }
}