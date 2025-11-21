<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Product Details - {{ $product->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background: #f5f5f5;
            padding: 0;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 50px;
            text-align: center;
            margin-bottom: 0;
        }

        .page-header h1 {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .page-header .subtitle {
            font-size: 16px;
            opacity: 0.95;
            font-weight: 300;
        }

        .badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 10px;
            font-weight: 500;
        }

        .container {
            background: white;
            padding: 50px;
        }

        .section {
            margin-bottom: 35px;
            padding-bottom: 25px;
            border-bottom: 2px solid #e5e7eb;
        }

        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .section-label {
            font-weight: 700;
            color: #667eea;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 10px;
            display: block;
        }

        .section-value {
            font-size: 20px;
            color: #1f2937;
            line-height: 1.6;
            font-weight: 400;
        }

        .price-box {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
        }

        .price-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .price-value {
            font-size: 48px;
            font-weight: bold;
        }

        .stock-badge {
            display: inline-block;
            padding: 10px 24px;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 25px;
            font-weight: bold;
            font-size: 18px;
        }

        .info-box {
            background: #f9fafb;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            margin: 30px 0;
        }

        .info-box h3 {
            color: #667eea;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .info-row {
            margin-bottom: 8px;
            font-size: 13px;
            color: #6b7280;
            line-height: 1.8;
        }

        .info-row strong {
            color: #374151;
            font-weight: 600;
        }

        .footer {
            text-align: center;
            padding: 30px 50px;
            background: #f9fafb;
            border-top: 3px solid #667eea;
            margin-top: 40px;
        }

        .footer p {
            color: #6b7280;
            font-size: 11px;
            line-height: 1.8;
            margin: 5px 0;
        }

        .footer .copyright {
            font-weight: 600;
            color: #374151;
        }
    </style>
</head>

<body>
    <!-- Page Header -->
    <div class="page-header">
        <h1>{{ config('app.name', 'Laravel') }}</h1>
        <div class="subtitle">Product Details Report</div>
        <div class="badge">OFFICIAL DOCUMENT</div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Product ID -->
        <div class="section">
            <span class="section-label">Product ID</span>
            <div class="section-value">#{{ $product->id }}</div>
        </div>

        <!-- Product Name -->
        <div class="section">
            <span class="section-label">Product Name</span>
            <div class="section-value">{{ $product->name }}</div>
        </div>

        <!-- Description -->
        <div class="section">
            <span class="section-label">Description</span>
            <div class="section-value">
                {{ $product->description ?? 'No description available' }}
            </div>
        </div>

        <!-- Price Box -->
        <div class="price-box">
            <div class="price-label">PRODUCT PRICE</div>
            <div class="price-value">${{ number_format($product->price, 2) }}</div>
        </div>

        <!-- Stock -->
        <div class="section">
            <span class="section-label">Stock Quantity</span>
            <div class="section-value">
                <span class="stock-badge">{{ $product->stock }} units available</span>
            </div>
        </div>

        <!-- Report Info Box -->
        <div class="info-box">
            <h3>Report Information</h3>
            <div class="info-row">
                <strong>Generated On:</strong> {{ now()->format('F d, Y - h:i A') }}
            </div>
            <div class="info-row">
                <strong>Generated By:</strong> {{ auth()->user()->name }}
            </div>
            <div class="info-row">
                <strong>Email:</strong> {{ auth()->user()->email }}
            </div>
            <div class="info-row">
                <strong>User Role:</strong> {{ auth()->user()->roles->pluck('name')->first() ?? 'No Role' }}
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p class="copyright">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>For any queries, please contact our support team.</p>
    </div>
</body>

</html>
