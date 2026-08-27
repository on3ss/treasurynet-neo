<?php

namespace App\Services;

use App\Models\BillNoSequence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class BillNoSequenceService
{
    /**
     * Default padding length for sequence numbers
     */
    private const DEFAULT_PADDING = 6;

    /**
     * Get the next bill number for a treasury and financial year.
     *
     * @param int $treasuryId
     * @param string $financialYear
     * @param int $paddingLength
     * @return string
     * @throws Exception
     */
    public function getNextBillNo(
        int $treasuryId,
        string $financialYear,
        int $paddingLength = self::DEFAULT_PADDING
    ): string {
        try {
            return DB::transaction(function () use ($treasuryId, $financialYear, $paddingLength) {
                // Lock the row for update to prevent race conditions
                $sequence = BillNoSequence::where('treasury_id', $treasuryId)
                    ->where('financial_year', $financialYear)
                    ->lockForUpdate()
                    ->first();

                if (!$sequence) {
                    // Create new sequence if it doesn't exist
                    $sequence = BillNoSequence::create([
                        'treasury_id' => $treasuryId,
                        'financial_year' => $financialYear,
                        'last_sequence_no' => 1,
                    ]);
                    $nextSequence = 1;
                } else {
                    // Increment the sequence
                    $nextSequence = $sequence->last_sequence_no + 1;
                    $sequence->update(['last_sequence_no' => $nextSequence]);
                }

                // Return formatted bill number
                return str_pad($nextSequence, $paddingLength, '0', STR_PAD_LEFT);
            });
        } catch (Exception $e) {
            Log::error('Failed to generate bill number', [
                'treasury_id' => $treasuryId,
                'financial_year' => $financialYear,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get multiple next bill numbers at once.
     *
     * @param int $treasuryId
     * @param string $financialYear
     * @param int $count
     * @param int $paddingLength
     * @return array
     */
    public function getNextBillNos(
        int $treasuryId,
        string $financialYear,
        int $count,
        int $paddingLength = self::DEFAULT_PADDING
    ): array {
        if ($count < 1) {
            throw new \InvalidArgumentException('Count must be at least 1');
        }

        return DB::transaction(function () use ($treasuryId, $financialYear, $count, $paddingLength) {
            $sequence = BillNoSequence::where('treasury_id', $treasuryId)
                ->where('financial_year', $financialYear)
                ->lockForUpdate()
                ->first();

            if (!$sequence) {
                $sequence = BillNoSequence::create([
                    'treasury_id' => $treasuryId,
                    'financial_year' => $financialYear,
                    'last_sequence_no' => $count,
                ]);
                $startSequence = 1;
            } else {
                $startSequence = $sequence->last_sequence_no + 1;
                $endSequence = $startSequence + $count - 1;
                $sequence->update(['last_sequence_no' => $endSequence]);
            }

            // Generate all bill numbers
            $billNos = [];
            for ($i = $startSequence; $i <= $endSequence; $i++) {
                $billNos[] = str_pad($i, $paddingLength, '0', STR_PAD_LEFT);
            }

            return $billNos;
        });
    }

    /**
     * Get current sequence information without incrementing.
     *
     * @param int $treasuryId
     * @param string $financialYear
     * @return BillNoSequence|null
     */
    public function getCurrentSequence(int $treasuryId, string $financialYear): ?BillNoSequence
    {
        return BillNoSequence::where('treasury_id', $treasuryId)
            ->where('financial_year', $financialYear)
            ->first();
    }

    /**
     * Get current sequence number without incrementing.
     *
     * @param int $treasuryId
     * @param string $financialYear
     * @return int
     */
    public function getCurrentSequenceNo(int $treasuryId, string $financialYear): int
    {
        $sequence = $this->getCurrentSequence($treasuryId, $financialYear);
        return $sequence ? $sequence->last_sequence_no : 0;
    }

    /**
     * Preview next bill number without incrementing.
     *
     * @param int $treasuryId
     * @param string $financialYear
     * @param int $paddingLength
     * @return string
     */
    public function previewNextBillNo(
        int $treasuryId,
        string $financialYear,
        int $paddingLength = self::DEFAULT_PADDING
    ): string {
        $currentSequence = $this->getCurrentSequenceNo($treasuryId, $financialYear);
        $nextSequence = $currentSequence + 1;

        return str_pad($nextSequence, $paddingLength, '0', STR_PAD_LEFT);
    }

    /**
     * Reset sequence for a treasury and financial year.
     *
     * @param int $treasuryId
     * @param string $financialYear
     * @param int $startFrom
     * @return bool
     */
    public function resetSequence(int $treasuryId, string $financialYear, int $startFrom = 0): bool
    {
        try {
            return DB::transaction(function () use ($treasuryId, $financialYear, $startFrom) {
                $sequence = BillNoSequence::where('treasury_id', $treasuryId)
                    ->where('financial_year', $financialYear)
                    ->lockForUpdate()
                    ->first();

                if ($sequence) {
                    $sequence->update(['last_sequence_no' => $startFrom]);
                } else {
                    BillNoSequence::create([
                        'treasury_id' => $treasuryId,
                        'financial_year' => $financialYear,
                        'last_sequence_no' => $startFrom,
                    ]);
                }

                return true;
            });
        } catch (Exception $e) {
            Log::error('Failed to reset bill number sequence', [
                'treasury_id' => $treasuryId,
                'financial_year' => $financialYear,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Set sequence to a specific number.
     *
     * @param int $treasuryId
     * @param string $financialYear
     * @param int $sequenceNo
     * @return bool
     */
    public function setSequence(int $treasuryId, string $financialYear, int $sequenceNo): bool
    {
        try {
            return DB::transaction(function () use ($treasuryId, $financialYear, $sequenceNo) {
                $sequence = BillNoSequence::where('treasury_id', $treasuryId)
                    ->where('financial_year', $financialYear)
                    ->lockForUpdate()
                    ->first();

                if ($sequence) {
                    $sequence->update(['last_sequence_no' => $sequenceNo]);
                } else {
                    BillNoSequence::create([
                        'treasury_id' => $treasuryId,
                        'financial_year' => $financialYear,
                        'last_sequence_no' => $sequenceNo,
                    ]);
                }

                return true;
            });
        } catch (Exception $e) {
            Log::error('Failed to set bill number sequence', [
                'treasury_id' => $treasuryId,
                'financial_year' => $financialYear,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get all sequences for a treasury.
     *
     * @param int $treasuryId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSequencesForTreasury(int $treasuryId)
    {
        return BillNoSequence::where('treasury_id', $treasuryId)
            ->orderBy('financial_year', 'desc')
            ->get();
    }

    /**
     * Get sequences for a specific financial year.
     *
     * @param string $financialYear
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSequencesForFinancialYear(string $financialYear)
    {
        return BillNoSequence::where('financial_year', $financialYear)
            ->with('treasury')
            ->orderBy('treasury_id')
            ->get();
    }

    /**
     * Validate financial year format.
     *
     * @param string $financialYear
     * @return bool
     */
    public function isValidFinancialYear(string $financialYear): bool
    {
        return preg_match('/^\d{4}-\d{4}$/', $financialYear) === 1;
    }

    /**
     * Delete sequence for a treasury and financial year.
     *
     * @param int $treasuryId
     * @param string $financialYear
     * @return bool
     */
    public function deleteSequence(int $treasuryId, string $financialYear): bool
    {
        try {
            return BillNoSequence::where('treasury_id', $treasuryId)
                ->where('financial_year', $financialYear)
                ->delete() > 0;
        } catch (Exception $e) {
            Log::error('Failed to delete bill number sequence', [
                'treasury_id' => $treasuryId,
                'financial_year' => $financialYear,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Check if sequence exists for treasury and financial year.
     *
     * @param int $treasuryId
     * @param string $financialYear
     * @return bool
     */
    public function sequenceExists(int $treasuryId, string $financialYear): bool
    {
        return BillNoSequence::where('treasury_id', $treasuryId)
            ->where('financial_year', $financialYear)
            ->exists();
    }

    /**
     * Get statistics for sequences.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'total_sequences' => BillNoSequence::count(),
            'total_treasuries' => BillNoSequence::distinct('treasury_id')->count(),
            'total_financial_years' => BillNoSequence::distinct('financial_year')->count(),
            'max_sequence' => BillNoSequence::max('last_sequence_no'),
            'avg_sequence' => round(BillNoSequence::avg('last_sequence_no'), 2),
            'recent_sequences' => BillNoSequence::latest('updated_at')
                ->take(10)
                ->get(['treasury_id', 'financial_year', 'last_sequence_no', 'updated_at']),
        ];
    }
}