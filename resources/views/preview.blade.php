<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        #preview-frame {
            height: calc(100% - 60px);
            width: 100%;
            overflow: hidden;
        }

        .preview-frame {
            height: 100%;
            width: 100%;
            border: none;
        }

        .download-button {
            display: inline-block;
            text-align: center;
            font-size: 0.9em;
            padding: 10px 14px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .download-button:hover {
            background-color: #0056b3;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
    </style>
    <script>
        function resizeIframe(obj) {
            obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
        }
    </script>

    <title>PDF Preview | PDFEngine</title>
</head>
<body>

    <div id="preview-frame">
        <iframe sandbox="allow-scripts allow-same-origin" onload="resizeIframe(this)" class="preview-frame" src="{{ $url }}" frameborder="0"></iframe>
    </div>

    <div class="button-container">
        <a href="{{ route('pdf.download') }}?{{ $queryParams }}" class="download-button">Download</a>
    </div>

</body>
</html>
