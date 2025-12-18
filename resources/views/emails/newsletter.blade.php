<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $newsletter->title }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #BC1D24 0%, #8B0000 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            padding: 40px 30px;
        }
        .newsletter-title {
            font-size: 24px;
            font-weight: bold;
            color: #BC1D24;
            margin-bottom: 20px;
            border-bottom: 3px solid #BC1D24;
            padding-bottom: 10px;
        }
        .newsletter-content {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
        }
        .newsletter-content h2 {
            color: #BC1D24;
            font-size: 20px;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .newsletter-content h3 {
            color: #333;
            font-size: 18px;
            margin-top: 25px;
            margin-bottom: 12px;
        }
        .newsletter-content p {
            margin-bottom: 15px;
        }
        .newsletter-content ul, .newsletter-content ol {
            margin-bottom: 15px;
            padding-left: 20px;
        }
        .newsletter-content li {
            margin-bottom: 8px;
        }
        .newsletter-content blockquote {
            border-left: 4px solid #BC1D24;
            padding-left: 20px;
            margin: 20px 0;
            font-style: italic;
            color: #666;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer-content {
            color: #666;
            font-size: 14px;
        }
        .unsubscribe-link {
            color: #BC1D24;
            text-decoration: none;
            font-weight: bold;
        }
        .unsubscribe-link:hover {
            text-decoration: underline;
        }
        .brand {
            font-size: 18px;
            font-weight: bold;
            color: #BC1D24;
            margin-bottom: 10px;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #BC1D24;
            text-decoration: none;
            font-weight: bold;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
            }
            .header, .content, .footer {
                padding: 20px 15px !important;
            }
            .header h1 {
                font-size: 24px !important;
            }
            .newsletter-title {
                font-size: 20px !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="brand">TUNAS MOTOR</div>
            <h1>Newsletter</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h1 class="newsletter-title">{{ $newsletter->title }}</h1>

            @if($newsletter->excerpt)
            <p style="font-size: 18px; color: #666; font-style: italic; margin-bottom: 30px;">
                {{ $newsletter->excerpt }}
            </p>
            @endif

            <div class="newsletter-content">
                {!! $newsletter->content !!}
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <p><strong>Tunas Motor</strong></p>
                <p>+(62) 1234 5678 90 | info@tunasmotor.com | Cirebon, Indonesia</p>

                <div class="social-links">
                    <a href="#">Facebook</a> |
                    <a href="#">Instagram</a> |
                    <a href="#">Twitter</a>
                </div>

                <p style="margin-top: 20px;">
                    Anda menerima email ini karena berlangganan newsletter Tunas Motor.<br>
                    <a href="{{ $unsubscribeUrl }}" class="unsubscribe-link">Berhenti berlangganan</a>
                </p>

                <p style="margin-top: 20px; font-size: 12px; color: #999;">
                    &copy; 2025 Tunas Motor. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>