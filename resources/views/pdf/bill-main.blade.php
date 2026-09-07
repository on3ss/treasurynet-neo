<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bill Document</title>
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
            color: #1a1a1a;
        }

        .header h3 {
            margin: 5px 0 0;
            font-size: 16px;
            color: #666;
        }

        .bill-info {
            margin-bottom: 20px;
        }

        .bill-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .bill-info td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .bill-info td:first-child {
            font-weight: bold;
            background-color: #f5f5f5;
            width: 200px;
        }

        .section {
            margin-top: 20px;
        }

        .section h4 {
            background-color: #e9ecef;
            padding: 8px;
            margin: 0;
            border: 1px solid #ddd;
        }

        .section table {
            width: 100%;
            border-collapse: collapse;
        }

        .section th,
        .section td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .section th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .total-row {
            font-weight: bold;
            background-color: #e9ecef;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
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
        <h1>GOVERNMENT BILL</h1>
        <h3>{{ $treasury_name }}</h3>
        <p>Financial Year: {{ $financial_year }}</p>
    </div>

    <div class="bill-info">
        <table>
            <tr>
                <td>Bill Number</td>
                <td>{{ $bill_number }}</td>
                <td>Bill Date</td>
                <td>{{ $bill_date }}</td>
            </tr>
            <tr>
                <td>Treasury</td>
                <td>{{ $treasury_name }}</td>
                <td>DDO</td>
                <td>{{ $ddo_name }}</td>
            </tr>
            <tr>
                <td>Payment Scheme</td>
                <td>{{ $payment_scheme_name }}</td>
                <td>Detail Head</td>
                <td>{{ $detail_head_name }}</td>
            </tr>
            <tr>
                <td>Bill Type</td>
                <td>{{ $bill_type_name }}</td>
                <td>Form</td>
                <td>{{ $form_name }}</td>
            </tr>
            <tr>
                <td>Source</td>
                <td>{{ $source }}</td>
                <td>Category</td>
                <td>{{ $category }}</td>
            </tr>
            <tr>
                <td>Net Amount</td>
                <td>₹ {{ number_format($net_amount, 2) }}</td>
                <td>Gross Amount</td>
                <td>₹ {{ number_format($gross_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Payment Type</td>
                <td>{{ $payment_type }}</td>
                <td>Urgent</td>
                <td>{{ $is_urgent ? 'Yes' : 'No' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h4>Gross Details</h4>
        <table>
            <thead>
                <tr>
                    <th>Sub Detail Head</th>
                    <th>Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gross_entries as $gross)
                    <tr>
                        <td>{{ $gross['sub_detail_head_name'] }}</td>
                        <td>{{ number_format($gross['amount'], 2) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Gross</td>
                    <td>₹ {{ number_format($gross_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if (count($deductions) > 0)
        <div class="section">
            <h4>Deductions</h4>
            <table>
                <thead>
                    <tr>
                        <th>Receipt Scheme</th>
                        <th>Amount (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deductions as $deduction)
                        <tr>
                            <td>{{ $deduction['receipt_scheme_name'] }}</td>
                            <td>{{ number_format($deduction['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>Total Deductions</td>
                        <td>₹ {{ number_format($total_deductions, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    <div class="section">
        <h4>Payee Details</h4>
        <table>
            <thead>
                <tr>
                    <th>Reg Code</th>
                    <th>Name</th>
                    <th>Amount (₹)</th>
                    <th>IFSC</th>
                    <th>Account No</th>
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
                <tr class="total-row">
                    <td colspan="2">Net Payable Amount</td>
                    <td>₹ {{ number_format($net_amount, 2) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Drawing and Disbursing Officer</p>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Treasury Officer</p>
        </div>
    </div>

    <div class="footer">
        Generated on {{ date('d-m-Y H:i:s') }} | This is a computer generated document
    </div>
</body>

</html>
