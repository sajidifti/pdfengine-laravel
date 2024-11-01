<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class PDFController extends Controller
{
    public function generatePDF(Request $request)
    {
        if ($request->has('url')) {

            // $url = $request->url;
            $url = urldecode($request->url);
            $fileName = 'app/public/PDF_' . time() . '.pdf';
            $delay = $request->delay ?? 300;

            // Browsershot::url($url)
            //     ->format('A4')
            //     ->margins(0, 0, 0, 0)
            //     ->showBackground()
            //     ->delay($delay)
            //     ->savePdf(storage_path($fileName));

            Browsershot::url($url)
                ->setOption('args', ['--disable-web-security'])
                ->ignoreHttpsErrors()
                ->noSandbox()
                ->addChromiumArguments([
                    'lang' => "en-US,en;q=0.9",
                    'hide-scrollbars',
                    'enable-font-antialiasing',
                    'force-device-scale-factor' => 1,
                    'font-render-hinting' => 'none',
                    'user-data-dir' => '/home/www-data/user-data',
                    'disk-cache-dir' => '/home/www-data/user-data/Default/Cache',
                ])
                ->setChromePath("/home/www-data/.cache/puppeteer/chrome/linux-130.0.6723.69/chrome-linux64/chrome")
                ->newHeadless()
                ->format('A4')
                ->margins(0, 0, 0, 0)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->delay($delay)
                ->savePdf(storage_path($fileName));

            return response()->download(storage_path($fileName))->deleteFileAfterSend(true);
        }

        return response()->json([
            'message' => 'No URL provided',
        ], 400);

    }
}
