<?php

namespace App\Http\Controllers;

use App\Models\Saham;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;
// use PhpOffice\PhpSpreadsheet\IOFactory;

class SahamController extends Controller
{
    public function clear(Request $request)
    {
        Saham::truncate();

        return redirect()->route('saham.index')->with('success', 'Semua data berhasil dihapus!');
    }

    public function exportPdf()
    {
        $saham = Saham::all();
        $pdf = Pdf::loadView('saham.pdf', compact('saham'));
        return $pdf->download('saham.pdf');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file');
        // Buat folder public/uploads jika belum ada
        $destinationPath = public_path('uploads');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Simpan file ke folder uploads dengan nama unik
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($destinationPath, $filename);

        // Path lengkap file
        $fullPath = $destinationPath . '/' . $filename;
        $filetype = pathinfo(trim($fullPath));
        // dd($fullPath);
        if ($filetype['extension'] == 'csv') {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Csv');
        }
        else if ($filetype['extension'] == 'xlsx') {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        }
        else if ($filetype['extension'] == 'xls') {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
        }

        $spreadsheet = $reader->load(trim($fullPath));
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        // dd($rows[1][1]);

        $namaSaham = $rows[1][1];

        // Lewati 2 baris pertama (header ganda)
        foreach (array_slice($rows, 3) as $row) {
            if (!empty($row[0])) {
                Saham::create([
                    'date' => date('Y-m-d', strtotime($row[0])),
                    'nama_saham' => $namaSaham,
                    'close'   => $row[1],
                    'high'    => $row[2],
                    'low'     => $row[3],
                    'open'    => $row[4],
                    'volume'  => $row[5],
                ]);
            }
        }

        return redirect()->route('saham.index')->with('success', 'Data saham berhasil diimpor!');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $saham = Saham::orderBy('date', 'ASC')->get();

        return view('saham.index', compact('saham'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('saham.create');
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

        return redirect()->route('saham.index')->with('success', 'saham added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Saham  $saham
     * @return \Illuminate\Http\Response
     */
    public function show(Saham $saham)
    {
        return view('saham.show', compact('saham'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Saham  $saham
     * @return \Illuminate\Http\Response
     */
    public function edit(Saham $saham)
    {
        return view('saham.edit', compact('saham'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Saham  $saham
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Saham $saham)
    {
        $saham->update($request->all());

        return redirect()->route('saham.index')->with('success', 'saham updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Saham  $saham
     * @return \Illuminate\Http\Response
     */
    public function destroy(Saham $saham)
    {
        $saham = $saham->delete();

        return redirect()->route('saham.index')->with('success', 'saham deleted successfully');
    }
}
