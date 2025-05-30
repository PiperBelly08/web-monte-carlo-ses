<?php

namespace App\Http\Controllers;

use App\Models\Saham;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MonteController extends Controller
{
    public function exportPdf(Request $request)
    {
        $saham = null;
        if ($request->isMethod('post') && $request->nama_saham_selected_export) {
            $saham = Saham::where('nama_saham', $request->nama_saham_selected_export)->get();
        } else {
            $saham = Saham::where('nama_saham', 'GOTO.JK')->get();
        }


        $closingPlucked = $saham->pluck('close');
        $closingSum = $closingPlucked->sum();


        $pdf = Pdf::loadView('monte.pdf', compact('saham', 'closingSum'));
        return $pdf->download('saham.pdf');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Ensure $saham is a Collection and sorted by date
        $saham = Saham::where('nama_saham', 'GOTO.JK')
            ->get(['id', 'date', 'nama_saham', 'close'])
            ->sortBy('date', SORT_ASC);

        // Calculate closingSum for all selected saham data
        $closingSum = $saham->sum('close');

        // Initialize processed as a collection
        $processed = collect();

        // Initialize cumulative and related variables OUTSIDE the loop for a continuous cumulative sum
        $cumulative = 0;
        $previousCumulative = 0; // Keep track for interval start

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

        return view('monte.index', [
            'saham' => $processed,
            'nama_saham' => $saham->pluck('nama_saham')->unique(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Saham::create($request->all());

        return redirect()->route('monte.index')->with('success', 'saham added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $saham = Saham::findOrFail($id);
        return view('monte.show', compact('saham'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        $saham = Saham::findOrFail($id);
        return view('monte.edit', compact('saham'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $saham = Saham::findOrFail($id);
        $saham->update($request->all());

        return redirect()->route('monte.index')->with('success', 'saham updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $saham = Saham::findOrFail($id);
        $saham = $saham->delete();

        return redirect()->route('saham.index')->with('success', 'saham deleted successfully');
    }
}
