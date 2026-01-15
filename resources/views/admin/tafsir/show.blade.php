<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $tafsir->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Arabic Font -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background: #f5f7fa;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #ffffff;
            padding: 32px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        h1 {
            color: #034E7A;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .meta {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 24px;
        }

        .meta span {
            margin-right: 16px;
        }

        .tafsir-content {
            font-size: 16px;
            line-height: 1.8;
        }

        .tafsir-content p {
            margin-bottom: 1rem;
        }

        .tafsir-content strong {
            color: #034E7A;
        }

        /* Arabic text */
        .tafsir-content p:lang(ar),
        .tafsir-content span:lang(ar) {
            font-family: "Amiri", serif;
            direction: rtl;
            text-align: center;
            font-size: 20px;
            color: #0f172a;
            margin: 20px 0;
        }

        /* Headings inside TinyMCE */
        .tafsir-content h2,
        .tafsir-content h3 {
            color: #034E7A;
            margin-top: 24px;
        }

        footer {
            text-align: center;
            font-size: 13px;
            color: #9ca3af;
            margin: 30px 0;
        }
    </style>
</head>
<body>

    <div class="container">

        <h1>{{ $tafsir->title }}</h1>

        <div class="meta">
            <span><strong>Language:</strong> {{ ucfirst($tafsir->language) }}</span>
            <span><strong>Type:</strong> {{ commonPostTypeOptions()[$tafsir->post_type] ?? '-' }}</span>
            <span><strong>Published:</strong> {{ $tafsir->created_at->format('d M Y') }}</span>
        </div>

        @if($tafsir->content)
            <div class="tafsir-content">
                {!! nl2br(e($tafsir->content)) !!}
            </div>
        @endif

        @if($tafsir->tafsir_html_content)
            <div class="tafsir-content">
                {!! $tafsir->tafsir_html_content !!}
            </div>
        @endif

    </div>

    <footer>
        © {{ date('Y') }} {{ config('app.name') }}
    </footer>

</body>
</html>
