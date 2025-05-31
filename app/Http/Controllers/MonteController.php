<?php

namespace App\Http\Controllers;

use App\Models\Saham;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;

class MonteController extends Controller
{
    /**
     * Export selected saham data to a PDF file.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportPdf(Request $request)
    {
        $saham = $request->isMethod('post') && $request->nama_saham_selected_export
            ? Saham::where('nama_saham', $request->nama_saham_selected_export)
                ->get(['id', 'date', 'nama_saham', 'close'])
                ->sortBy('date', SORT_ASC)
            : Saham::where('nama_saham', Saham::first()->nama_saham)->get()
                ->get(['id', 'date', 'nama_saham', 'close'])
                ->sortBy('date', SORT_ASC);

        // Calculate closingSum for all selected saham data
        $closingSum = $saham->sum('close');

        // Initialize processed as a collection
        $processed = collect();

        // Initialize cumulative and related variables OUTSIDE the loop for a continuous cumulative sum
        $cumulative = 0;

        if ($closingSum == 0) {
            // Handle the case where closingSum is zero (e.g., return empty, throw error)
            // For now, let's just return an empty collection if sum is zero.
            return $processed;
        }

        // Single loop is sufficient for a continuous cumulative calculation
        foreach ($saham as $index => $currentData) {
            $probabilities = $currentData->close / $closingSum;

            // The interval start is the cumulative value before adding the current probability.
            // For the very first item (index 0), intervalStart will be 0.
            $intervalStart = $cumulative;

            // If it's not the very first item, we can add a tiny increment to intervalStart
            // to ensure strict non-overlap, though this is usually for specific display/use cases.
            // Be careful with this, as it can make intervals slightly misaligned.
            // A common approach is [start, end) where end is start of next.
            if ($index > 0) { // Apply for all values after the first one
                $intervalStart += 0.0001; // Or PHP_FLOAT_EPSILON for higher precision
            }

            // Update the cumulative sum
            $cumulative += $probabilities;

            // The interval end is the cumulative value after adding the current probability
            $intervalEnd = $cumulative;

            $processed->push((object)[
                'id' => $currentData->id,
                'date' => $currentData->date,
                'nama_saham' => $currentData->nama_saham,
                'close' => $currentData->close,
                'probabilities' => number_format($probabilities, 4),
                'cumulative' => number_format($cumulative, 4),
                'interval_start' => number_format($intervalStart, 4),
                'interval_end' => number_format($intervalEnd, 4),
            ]);
        }

        $saham = $processed;
        $id = $request->nama_saham_selected_export;

        $pdf = Pdf::loadView('monte.pdf', compact('saham', 'id'));
        return $pdf->download(
            "monte_saham_" . strtolower(trim(preg_replace('/\s+/', '_', $id))) . "_" . time() . ".pdf"
        );
    }

    /**
     * Generates a sequence of pseudo-random numbers using the Mixed Congruential Method (MCM).
     * Formula: X_n+1 = (a * X_n + c) mod m
     *
     * @param int $seed The initial value (X_0).
     * @param int $count The number of random numbers to generate.
     * @param int $multiplier The multiplier 'a'.
     * @param int $increment The increment 'c'.
     * @param int $modulus The modulus 'm'.
     * @return Collection A collection of pseudo-random numbers between 0 (inclusive) and 1 (exclusive).
     */
    public function generateRandomNumbersMCM(
        int $seed,
        int $count,
        int $multiplier,
        int $increment,
        int $modulus
    ): Collection {
        $randomNumbers = collect();
        $currentX = $seed;

        for ($i = 0; $i < $count; $i++) {
            // Calculate the next number in the sequence
            $currentX = ($multiplier * $currentX + $increment) % $modulus;

            // Normalize the number to be between 0 and 1
            // Use (float) to ensure floating-point division
            $normalizedRandom = (float)$currentX / $modulus;

            $randomNumbers->push($normalizedRandom);
        }

        return $randomNumbers;
    }

    /**
     * Calculates the Mean Absolute Percentage Error (MAPE).
     * MAPE = (1/n) * Sum(|(Actual - Forecast) / Actual|) * 100%
     *
     * @param Collection|array $actualValues A collection or array of actual values.
     * @param Collection|array $forecastValues A collection or array of forecast values.
     * @return float The calculated MAPE value, or 0.0 if no valid data points or sum is zero.
     * @throws \InvalidArgumentException If the input arrays/collections have different counts.
     */
    public function calculateMAPE(
        Collection|array $actualValues,
        Collection|array $forecastValues
    ): float {
        $actualValues = collect($actualValues);
        $forecastValues = collect($forecastValues);

        if ($actualValues->count() !== $forecastValues->count()) {
            throw new \InvalidArgumentException("Actual and forecast value counts must be equal.");
        }

        if ($actualValues->isEmpty()) {
            return 0.0; // No data to calculate MAPE
        }

        $sumAbsolutePercentageError = 0;
        $validDataPoints = 0;

        foreach ($actualValues as $index => $actual) {
            $forecast = $forecastValues->get($index);

            // Ensure actual value is not zero to avoid division by zero
            if ($actual != 0) {
                $percentageError = abs(($actual - $forecast) / $actual);
                $sumAbsolutePercentageError += $percentageError;
                $validDataPoints++;
            }
            // If actual is 0, this data point is typically excluded from MAPE calculation
            // or handled as a special case depending on business rules.
            // Here, we simply skip it.
        }

        if ($validDataPoints === 0) {
            return 0.0; // Avoid division by zero if all actual values were zero
        }

        // Calculate MAPE and multiply by 100 for percentage
        $mape = ($sumAbsolutePercentageError / $validDataPoints) * 100;

        return round($mape, 2); // Round to 2 decimal places for readability
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nama_saham = Saham::all()->pluck('nama_saham')->unique();
        return view('monte.index', compact('nama_saham'));
    }

    public function showData(Request $request, string $id)
    {
        if ($request->isMethod('post')) {
            $id = $request->nama_saham_selected;

        }

        // Ensure $saham is a Collection and sorted by date
        $saham = Saham::where('nama_saham', $id)
            ->get(['id', 'date', 'nama_saham', 'close'])
            ->sortBy('date', SORT_ASC);

        // Calculate closingSum for all selected saham data
        $closingSum = $saham->sum('close');

        // Initialize processed as a collection
        $processed = collect();

        // Initialize cumulative and related variables OUTSIDE the loop for a continuous cumulative sum
        $cumulative = 0;

        if ($closingSum == 0) {
            // Handle the case where closingSum is zero (e.g., return empty, throw error)
            // For now, let's just return an empty collection if sum is zero.
            return $processed;
        }

        // Single loop is sufficient for a continuous cumulative calculation
        foreach ($saham as $index => $currentData) {
            $probabilities = $currentData->close / $closingSum;

            // The interval start is the cumulative value before adding the current probability.
            // For the very first item (index 0), intervalStart will be 0.
            $intervalStart = $cumulative;

            // If it's not the very first item, we can add a tiny increment to intervalStart
            // to ensure strict non-overlap, though this is usually for specific display/use cases.
            // Be careful with this, as it can make intervals slightly misaligned.
            // A common approach is [start, end) where end is start of next.
            if ($index > 0) { // Apply for all values after the first one
                $intervalStart += 0.0001; // Or PHP_FLOAT_EPSILON for higher precision
            }

            // Update the cumulative sum
            $cumulative += $probabilities;

            // The interval end is the cumulative value after adding the current probability
            $intervalEnd = $cumulative;

            $processed->push((object)[
                'id' => $currentData->id,
                'date' => $currentData->date,
                'nama_saham' => $currentData->nama_saham,
                'close' => $currentData->close,
                'probabilities' => number_format($probabilities, 4),
                'cumulative' => number_format($cumulative, 4),
                'interval_start' => number_format($intervalStart, 4),
                'interval_end' => number_format($intervalEnd, 4),
            ]);
        }

        $saham = $processed;
        $nama_saham = Saham::all()->pluck('nama_saham')->unique();

        return view('monte.show', compact('saham', 'nama_saham', 'id'));
    }
}
