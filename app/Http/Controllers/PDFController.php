<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PDFController extends Controller
{
    public function urlPreview(Request $request)
    {
        return $this->preview($request);
    }

    public function htmlPreview(Request $request)
    {
        $request->validate([
            'html' => 'required|string',
        ]);

        $html = $request->input('html');
        $id = Str::uuid()->toString();

        Cache::put('pdf_html_' . $id, $html, now()->addHours(1));

        $queryParams = http_build_query(array_filter([
            'html_id' => $id,
            'delay' => $request->input('delay'),
            'filename' => $request->input('filename'),
        ]));

        $url = route('pdf.html.render', ['id' => $id]);

        return view('preview', compact('url', 'queryParams'));
    }

    public function renderHtml($id)
    {
        $html = Cache::get('pdf_html_' . $id);

        if (!$html) {
            abort(404, 'HTML preview expired or not found.');
        }

        return response($html);
    }

    public function preview(Request $request)
    {
        if ($request->has('url')) {
            $url = urldecode($request->url);

            $queryParams = http_build_query(array_filter([
                'delay' => $request->query('delay'),
                'url' => $request->query('url'),
                'filename' => $request->query('filename'),
            ]));

            return view('preview', compact('url', 'queryParams'));
        }

        abort(404);
    }

    // Accept JSON as function input
    public function generatePDF(Request $request)
    {
        if ($request->has('url') || $request->has('html_id') || $request->has('html')) {

            $fileName = ($request->filename ? $request->filename . '.pdf' : 'app/public/PDF_' . time() . '.pdf');
            $delay = $request->delay ?? 300;

            $browsershot = new Browsershot();
            
            if ($request->has('url')) {
                // $url = $request->url;
                $url = urldecode($request->url);
                $browsershot->setUrl($url);
            } elseif ($request->has('html_id')) {
                $html = Cache::get('pdf_html_' . $request->html_id);
                if (!$html) {
                    return response()->json(['message' => 'HTML expired or not found'], 404);
                }
                $browsershot->setHtml($html);
            } else {
                $browsershot->setHtml($request->html);
            }

            // Browsershot::url($url)
            //     ->format('A4')
            //     ->margins(0, 0, 0, 0)
            //     ->showBackground()
            //     ->delay($delay)
            //     ->savePdf(storage_path($fileName));

            $browsershot
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
