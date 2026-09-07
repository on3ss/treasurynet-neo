<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sanction Order</title>
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

        .sanction-info {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .sanction-info h4 {
            margin: 0 0 10px;
            color: #1a1a1a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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

        .conditions {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #fff;
        }

        .conditions h4 {
            margin: 0 0 10px;
            color: #1a1a1a;
        }

        .conditions ol {
            margin: 0;
            padding-left: 20px;
        }

        .conditions li {
            margin-bottom: 5px;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            margin-left: 50px;
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
        <h1>SANCTION ORDER</h1>
        <h3>Government of {{ $state_name ?? 'State' }}</h3>
        <p>Finance Department</p>
    </div>

    <div class="sanction-info">
        <h4>Sanction Details</h4>
        <table>
            <tr>
                <td>Sanction Order No</td>
                <td>{{ $sanction_order_no }}</td>
                <td>Date</td>
                <td>{{ $sanction_date }}</td>
            </tr>
            <tr>
                <td>Bill Number</td>
                <td>{{ $bill_number }}</td>
                <td>Financial Year</td>
                <td>{{ $financial_year }}</td>
            </tr>
            <tr>
                <td>Treasury</td>
                <td>{{ $treasury_name }}</td>
                <td>DDO</td>
                <td>{{ $ddo_name }}</td>
            </tr>
            <tr>
                <td>Sanctioned Amount</td>
                <td>₹ {{ number_format($sanctioned_amount, 2) }}</td>
                <td>Purpose</td>
                <td>{{ $purpose }}</td>
            </tr>
        </table>
    </div>

    <div class="conditions">
        <h4>Terms and Conditions</h4>
        <ol>
            <li>The sanction is subject to the availability of funds under the relevant head of account.</li>
            <li>The expenditure should be incurred strictly for the purpose for which it is sanctioned.</li>
            <li>All supporting documents and vouchers must be preserved for audit purposes.</li>
            <li>Any unspent balance shall be surrendered to the Government immediately.</li>
            <li>This sanction is valid for the current financial year only.</li>
        </ol>
    </div>

    <div class="signature">
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Authorized Signatory</p>
            <p>Finance Department</p>
        </div>
    </div>

    <div class="footer">
        Generated on {{ date('d-m-Y H:i:s') }} | This is a computer generated sanction order
    </div>
</body>

</html>
