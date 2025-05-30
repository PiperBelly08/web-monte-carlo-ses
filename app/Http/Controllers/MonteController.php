<?php

namespace App\Http\Controllers;

use App\Models\Saham;
use App\Models\PorsiSaham;
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
        // dd($request->all());

        $saham = null;
        if ($request->isMethod('get') && $request->nama_saham_selected) {
            $saham = Saham::where('nama_saham', $request->nama_saham_selected)->get();
        } else {
            $saham = Saham::where('nama_saham', 'GOTO.JK')->get();
        }
        
        $porsisaham=PorsiSaham::all();

        $closing_sum = $saham->sum(function ($saham) {
            return floatval(str_replace(',', '', $saham->close));
            });

                foreach ($saham as $sh) {
                    $close = floatval(str_replace(',', '', $sh->close));

                    if ($closing_sum > 0) {
                        $porsi = $close / $closing_sum;
                    } else {
                        $porsi = 0;
                    }

                PorsiSaham::create([
                            'date' => $sh->date,
                            'nama_saham' => $sh->nama_saham,
                            'close' => $close,
                            'porsi' => $porsi,
                        ]);
                    }

        $closingPlucked = $saham->pluck('close');
        $closingSum = $closingPlucked->sum();
        
        $nama_saham = Saham::all()->pluck('nama_saham')->unique();
        // session()->flash('success', 'Porsi berhasil dihitung dan disimpan.');
        return view('monte.index', [
            'saham' => $saham,
            'closing_sum' => $closingSum,
            'nama_saham' => $nama_saham,
            'porsisaham' => $porsisaham,
        ]);
    }


    // public function hitungPorsi()
    // {
    //     $sahams = Saham::all();

    //     // Bersihkan dan total
    //     $closing_sum = $sahams->sum(function ($saham) {
    //         return floatval(str_replace(',', '', $saham->close));
    //     });

    //     foreach ($sahams as $saham) {
    //         $close = floatval(str_replace(',', '', $saham->close));

    //         if ($closing_sum > 0) {
    //             $saham->porsi = $close / $closing_sum;
    //         } else {
    //             $saham->porsi = 0;
    //         }

    //         $saham->save();
    //     }

    //     return redirect()->back()->with('success', 'Porsi berhasil dihitung dan disimpan.');
    // }

    // public function tampilkanSaham(Request $request)
    // {

    // }

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
    public function show($id)
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
    public function edit($id)
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
    public function update(Request $request, $id)
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
    public function destroy($id)
    {
        $saham = Saham::findOrFail($id);
        $saham = $saham->delete();
  
        return redirect()->route('saham.index')->with('success', 'saham deleted successfully');
    }
}
