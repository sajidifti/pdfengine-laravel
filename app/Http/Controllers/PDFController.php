<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class PDFController extends Controller
{
    public function generatePDF(Request $request)
    {
        if ($request->has('url')) {

            $url = $request->url;
            $fileName = 'app/public/' . time() . '.pdf';
            $delay = $request->delay ?? 5000;

            Browsershot::url($url)
                ->setChromePath('/home/ifti/.cache/puppeteer/chrome/linux-130.0.6723.69/chrome-linux64/chrome')
                ->format('A4')
                ->margins(0, 0, 0, 0)
                ->showBackground()
                ->delay($delay)
                ->savePdf(storage_path($fileName));

                return response()->download(storage_path($fileName));
        }

        // return json response
        return response()->json([
            'message' => 'No URL provided',
        ], 400);

    }
}
