# PDFEngine Laravel

PDFEngine is a Laravel-based application that uses Spatie's [Browsershot](https://github.com/spatie/browsershot) (powered by Puppeteer and Headless Chromium) to quickly generate PDF documents from live URLs or raw HTML content.

## Features

- **Generate PDF from a URL:** Render public web pages to PDF format.
- **Generate PDF from raw HTML:** Send raw HTML strings and get beautifully rendered PDFs.
- **Preview UI:** Provides a web-based interface for previewing the output before initiating the actual download.
- **Direct Download APIs:** Endpoints specifically tailored to return the PDF file directly.
- **Service Ready:** CSRF token verification is disabled for key API endpoints (`/html`, `/pdf/download`), making it easy to integrate with other services over simple HTTP requests.

## Endpoints & Usage Examples

### 1. Direct PDF Download from URL

Generates a PDF from a specified URL and immediately returns the file stream.

**Endpoint:** `GET /pdf/download`

**Parameters:**
- `url` (required): The valid URL of the page you want to convert to PDF.
- `filename` (optional): The custom name for the downloaded file (without the `.pdf` extension).
- `delay` (optional): Delay in milliseconds before saving the PDF (useful for pages with heavy JS rendering or loading animations). Default is `300`.

**Example (cURL):**
```bash
curl -X GET "http://127.0.0.1:8000/pdf/download?url=https://example.com&filename=my-example" --output my-example.pdf
```

---

### 2. Direct PDF Download from HTML

Generates a PDF from a provided raw HTML string and immediately returns the file stream.

**Endpoint:** `POST /pdf/download`

**Parameters:**
- `html` (required): The raw HTML string.
- `filename` (optional): The custom name for the downloaded file (without the `.pdf` extension).
- `delay` (optional): Delay in milliseconds before saving the PDF.

**Example (cURL):**
```bash
curl -X POST http://127.0.0.1:8000/pdf/download \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "html=<h1>Hello World</h1><p>This is a custom PDF generation test.</p>" \
  --output custom.pdf
```

---

### 3. Generate HTML Preview (Interactive UI)

Provides a web-based UI preview of your generated HTML before downloading. The system caches the HTML temporarily and serves a UI with an iframe showing the design, alongside a "Download" button.

**Endpoint:** `POST /html`

**Parameters:**
- `html` (required): Raw HTML string to preview.
- `filename` (optional): Preferred filename if the user decides to download.

**Example (cURL):**
*(Typically you would submit an HTML form to this endpoint rather than cURL, as the response is a web page).*
```bash
curl -X POST http://127.0.0.1:8000/html \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "html=<h1>Preview Me</h1><p>I am a preview.</p>"
```

---

### 4. Provide URL Preview (Interactive UI)

A web UI endpoint that renders an iframe of the target URL with a "Download" button below it.

**Endpoint:** `GET /url` (or `GET /pdf`)

**Parameters:**
- `url` (required): The URL to preview.
- `filename` (optional): Preferred filename if the user decides to download.

**Example:**
Navigate via your web browser to: 
`http://127.0.0.1:8000/url?url=https://laravel.com`

---

## Technical Details

- **Spatie Browsershot:** Ensure that your server environment has Node.js and Puppeteer/Chromium dependencies correctly installed and configured.
- **Internal Configuration:** The controller specifically passes arguments to Chromium like `--disable-web-security`, and configures strict `user-data-dir` and `disk-cache-dir` paths optimized for Linux/www-data environments. 
- **Caching Mechanism:** The HTML preview component relies on Laravel's Cache to safely store incoming HTML payloads for 1 hour. It returns a temporary unguessable ID mapped to the route `GET /html/render/{id}` to securely pipe your text into the iframe.
