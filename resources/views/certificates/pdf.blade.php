<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: #fff;
        }

        .certificate {
            width: 100%;
            min-height: 530px;
            padding: 40px;
            position: relative;
            border: 8px double #4338ca;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
        }

        .certificate::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 2px solid #c7d2fe;
        }

        .header {
            text-align: center;
            padding: 20px 0 10px;
        }

        .header h1 {
            font-size: 36px;
            color: #312e81;
            letter-spacing: 4px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .subheader {
            font-size: 14px;
            color: #6366f1;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .divider {
            width: 200px;
            margin: 15px auto;
            height: 2px;
            background: linear-gradient(90deg, transparent, #6366f1, transparent);
        }

        .body {
            text-align: center;
            padding: 20px 60px;
        }

        .body p {
            font-size: 14px;
            color: #475569;
            line-height: 1.8;
        }

        .name {
            font-size: 32px;
            color: #1e1b4b;
            font-weight: bold;
            margin: 15px 0;
            font-style: italic;
        }

        .details-table {
            margin: 20px auto;
            border-collapse: collapse;
        }

        .details-table td {
            padding: 6px 20px;
            font-size: 13px;
            color: #475569;
        }

        .details-table td:first-child {
            font-weight: bold;
            text-align: right;
            color: #312e81;
        }

        .footer {
            text-align: center;
            padding: 10px 0 20px;
        }

        .footer .cert-number {
            font-size: 11px;
            color: #94a3b8;
            letter-spacing: 1px;
        }

        .footer .date {
            font-size: 12px;
            color: #64748b;
            margin-top: 5px;
        }

        .seal {
            display: inline-block;
            width: 60px;
            height: 60px;
            border: 3px solid #4338ca;
            border-radius: 50%;
            line-height: 54px;
            text-align: center;
            font-size: 10px;
            color: #4338ca;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="certificate">
        <div class="header">
            <h1>Certificate</h1>
            <p class="subheader">of Achievement</p>
        </div>

        <div class="divider"></div>

        <div class="body">
            <p>This is to certify that</p>
            <p class="name">{{ $certificate->user->name ?? 'Student Name' }}</p>
            <p>has successfully completed</p>
            <p style="font-size: 20px; color: #4338ca; font-weight: bold; margin: 10px 0;">
                {{ $certificate->round->name ?? 'Round Name' }}</p>

            <table class="details-table">
                <tr>
                    <td>Class:</td>
                    <td>{{ $certificate->user->class ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Group:</td>
                    <td>{{ $certificate->group ? $certificate->group->name : ($certificate->user->group ?? 'N/A') }}</td>
                </tr>
                <tr>
                    <td>Certificate No:</td>
                    <td>{{ $certificate->certificate_number }}</td>
                </tr>
            </table>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <div class="seal">VERIFIED</div>
            <p class="date">Issued on {{ $certificate->issued_at->format('F d, Y') }}</p>
            <p class="cert-number">{{ $certificate->certificate_number }}</p>
        </div>
    </div>
</body>

</html>