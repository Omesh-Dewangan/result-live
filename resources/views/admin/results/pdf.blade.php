<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Marksheet Export</title>
    <style>
        /* DOMPDF RESET & COMPATIBILITY LAYER */
        @page { 
            margin: 0; 
            size: A4 portrait;
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            margin: 0; 
            padding: 0; 
            background: #ffffff;
            color: #1a1a1a;
            font-size: 11px;
        }

        /* 🖼️ Designer Layout Compatibility */
        .pdf-page { width: 100%; border: none; }
        .result-card {
            width: 100% !important;
            max-width: 100% !important;
            margin: 20px auto !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            box-shadow: none !important; /* Dompdf fails at shadow */
            background: #fff !important;
        }

        /* 🌈 Gradient Replacement (Dompdf fails at gradients) */
        /* If your template uses background: linear-gradient(...), let's force a solid blue header */
        div[style*="linear-gradient"] {
            background-color: #1e40af !important; /* Deep Blue Fallback */
            background-image: none !important;
            color: #ffffff !important;
        }

        /* 📏 Spacing & Utilities */
        .p-3 { padding: 15px !important; }
        .p-4 { padding: 25px !important; }
        .mb-4 { margin-bottom: 20px !important; }
        .text-center { text-align: center !important; }
        .text-end { text-align: right !important; }
        .text-white { color: #ffffff !important; }
        .fw-bold { font-weight: bold !important; }
        .text-uppercase { text-transform: uppercase !important; }
        .rounded-3 { border-radius: 8px !important; }

        /* 📊 Table Adjustments */
        .table { width: 100% !important; border-collapse: collapse !important; border: 1px solid #dee2e6 !important; }
        .table th { background: #f8f9fa !important; padding: 10px !important; border: 1px solid #dee2e6 !important; text-align: center !important; }
        .table td { padding: 10px !important; border: 1px solid #dee2e6 !important; text-align: center !important; }
        .table .text-start { text-align: left !important; }

        /* 🏆 Status Badges */
        .badge { display: inline-block !important; padding: 3px 8px !important; border-radius: 4px !important; font-weight: bold !important; }
        .bg-success { background-color: #d1fae5 !important; color: #065f46 !important; }
        .bg-danger { background-color: #fee2e2 !important; color: #991b1b !important; }

        /* 🖋️ Signature Area */
        .border-top-0 { border-top: 0 !important; }
        .border { border: 1px solid #dee2e6 !important; }

        /* 🔄 Forced Page Breaks */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    @foreach($pages as $html)
        <div class="pdf-page">
            <div style="padding: 30px;">
                {!! $html !!}
            </div>
        </div>
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
