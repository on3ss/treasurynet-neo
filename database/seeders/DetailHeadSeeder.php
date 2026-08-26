<?php

namespace Database\Seeders;

use App\Models\DetailHead;
use Illuminate\Database\Seeder;

class DetailHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $detailHeads = [
            // [head, description, head_type, deductions_array]
            ['00', 'No Entry', 'DC', ['AG', 'Salary', 'TDS']],
            ['01', 'Salaries and Sumptuary', 'AC', ['AG', 'Salary']],
            ['02', 'Wages', 'AC', ['AG', 'Salary', 'TDS']],
            ['03', 'Overtime Allowance', 'DC', []],
            ['04', 'Pensions / Gratuities', 'AC', ['AG', 'Salary']],
            ['05', 'Payment for Professional & Special Services', 'DC', ['TDS']],
            ['06', 'Medical Treatment', 'AC', ['AG', 'Salary']],
            ['07', 'Post Matric Scholarship and Stipend', 'DC', []],
            ['08', 'Interest Receipt', 'DC', []],
            ['10', 'Deduct / Refund', 'DC', ['AG', 'Salary']],
            ['11', 'Travelling Expenses', 'AC', ['TDS']],
            ['12', 'Foreign Travel Expenses', 'AC', ['AG', 'Salary', 'TDS']],
            ['13', 'Office Expenses', 'AC', ['TDS']],
            ['14', 'Rents, Rates and Taxes', 'AC', ['TDS']],
            ['15', 'Royalty', 'DC', ['TDS']],
            ['16', 'Publications', 'DC', ['TDS']],
            ['20', 'Other Administrative Expenses', 'AC', ['TDS']],
            ['21', 'Supplies and Materials', 'AC', ['TDS']],
            ['22', 'Arms and Ammunitions', 'AC', ['AG', 'Salary', 'TDS']],
            ['23', 'Cost of Ration', 'AC', ['AG', 'Salary', 'TDS']],
            ['24', 'P. O. L.', 'AC', ['AG', 'Salary', 'TDS']],
            ['25', 'Clothing and Tentage', 'AC', ['AG', 'Salary', 'TDS']],
            ['26', 'Advertising and Publicity', 'AC', ['TDS']],
            ['27', 'Minor Works / Maintenance', 'AC', ['TDS']],
            ['28', 'Professional Services', 'AC', ['AG', 'Salary', 'TDS']],
            ['29', 'Repair & Maintenance', 'NA', []],
            ['30', 'Other Contractual Services', 'NA', []],
            ['31', 'Grants-in-Aid General (Salary)', 'AC', ['AG', 'Salary', 'TDS']],
            ['32', 'Contribution', 'NA', ['AG']],
            ['33', 'Subsidies', 'AC', ['AG', 'Salary', 'TDS']],
            ['34', 'Scholarships and Stipends', 'AC', ['AG']],
            ['35', 'GR Creation for Capital Assets', 'AC', ['AG']],
            ['36', 'Grant-in-Aid (Non-Salary)', 'AC', ['AG']],
            ['41', 'Secret Service Expenditure', 'NA', []],
            ['42', 'Lumpsum Provision', 'NA', ['Salary']],
            ['43', 'Suspense', 'NA', ['Salary']],
            ['44', 'Exchange Variation', 'NA', []],
            ['45', 'Interests', 'NA', ['AG', 'Salary']],
            ['46', 'Central State Transfer of Resources', 'NA', []],
            ['49', 'Other Revenue Expenditure', 'NA', []],
            ['50', 'Compensation, Other Charges', 'AC', ['AG', 'Salary', 'TDS']],
            ['51', 'Motor Vehicles', 'NA', ['AG', 'Salary']],
            ['52', 'Machinery Equipment / Tools', 'AC', ['AG', 'Salary', 'TDS']],
            ['53', 'Major Works, Lands & Building', 'AC', ['TDS']],
            ['54', 'Investments', 'AC', []],
            ['55', 'Loans', 'AC', []],
            ['56', 'Re-payment of Borrowing', 'NA', []],
            ['60', 'Other Capital Expenditures', 'NA', []],
            ['61', 'Depreciation', 'NA', []],
            ['63', 'Treasury Remittance', 'NA', []],
            ['64', 'Write Off / Losses', 'NA', []],
            ['70', 'Deduct Recoveries', 'NA', []],
            ['94', 'Permanent Advance', 'NA', []],
            ['95', 'Deposit', 'DC', ['Salary']],
            ['96', 'Refund', 'DC', []],
            ['97', 'G P F', 'NA', []],
            ['98', 'Add Amount Transferred from CSS', 'NA', []],
            ['99', 'Deduct Amount Transferred to State', 'NA', []],
        ];

        foreach ($detailHeads as $data) {
            DetailHead::updateOrCreate(
                ['code' => $data[0]],
                [
                    'code' => $data[0],
                    'description' => $data[1],
                    'head_type' => $data[2],
                    'deduction_applicability' => $data[3],
                ]
            );
        }

        $this->command->info('Successfully seeded ' . count($detailHeads) . ' detail heads.');
    }
}