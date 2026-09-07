<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Payment Voucher</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header h3 {
            margin: 5px 0 0;
            color: #666;
        }

        .voucher-info {
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        td:first-child {
            font-weight: bold;
            background-color: #f5f5f5;
            width: 200px;
        }

        .payee-section {
            margin-top: 20px;
        }

        .payee-section h4 {
            background-color: #e9ecef;
            padding: 8px;
            margin: 0;
            border: 1px solid #ddd;
        }

        .payee-section table {
            margin-top: 0;
        }

        .payee-section th {
            background-color: #f8f9fa;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .payee-section td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .amount-in-words {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            font-style: italic;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
            width: 200px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>PAYMENT VOUCHER</h1>
        <h3>{{ $treasury_name }}</h3>
        <p>Voucher No: {{ $voucher_no }}</p>
    </div>

    <div class="voucher-info">
        <table>
            <tr>
                <td>Voucher Date</td>
                <td>{{ $voucher_date }}</td>
                <td>Bill Number</td>
                <td>{{ $bill_number }}</td>
            </tr>
            <tr>
                <td>Financial Year</td>
                <td>{{ $financial_year }}</td>
                <td>Treasury</td>
                <td>{{ $treasury_name }}</td>
            </tr>
            <tr>
                <td>DDO</td>
                <td>{{ $ddo_name }}</td>
                <td>Payment Type</td>
                <td>{{ $payment_type }}</td>
            </tr>
        </table>
    </div>

    <div class="payee-section">
        <h4>Payment Details</h4>
        <table>
            <thead>
                <tr>
                    <th>Reg Code</th>
                    <th>Payee Name</th>
                    <th>Amount (₹)</th>
                    <th>IFSC Code</th>
                    <th>Account Number</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payees as $payee)
                    <tr>
                        <td>{{ $payee['reg_code'] }}</td>
                        <td>{{ $payee['name'] }}</td>
                        <td>{{ number_format($payee['amount'], 2) }}</td>
                        <td>{{ $payee['ifsc'] }}</td>
                        <td>{{ $payee['account_no'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="amount-in-words">
        <strong>Amount in Words:</strong> {{ $amount_in_words }}
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Prepared By</p>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Checked By</p>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Approved By</p>
        </div>
    </div>

    <div class="footer">
        Generated on {{ date('d-m-Y H:i:s') }} | This is a computer generated voucher
    </div>
</body>

</html>
