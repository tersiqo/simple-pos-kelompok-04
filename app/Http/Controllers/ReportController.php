<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportExport;
use App\Jobs\ProcessProductImport;
use App\Models\Category;
use App\Models\ImportError;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Imports\ProductImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
    [$from, $to] = $this->resolveDateRange($request);
    $categoryId = $request->query('category_id');
    $query = $this->filteredQuery($from, $to, $categoryId);
    
    return view('reports.index', [
        'totalPenjualan'  => $query->sum('total'),
        'jumlahTransaksi' => $query->count(),
        'from'            => $from->toDateString(),
        'to'              => $to->toDateString(),
        'categories'      => Category::orderBy('name')->get(),
        'categoryId'      => $categoryId,
    ]);
    }
    public function exportCsv(Request $request): StreamedResponse
    {
        [$from, $to] = $this->resolveDateRange($request);
        $categoryId = $request->query('category_id');
        $query = $this->filteredQuery($from, $to, $categoryId);
        
        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Tanggal', 'Kasir', 'Total']);
            foreach ($query->lazyById() as $t) {
                fputcsv($out, [$t->id, $t->created_at, $t->user->name, $t->total]);
            }
            fclose($out);
        }, 'laporan-penjualan.csv');
    }

public function exportExcel(Request $request): BinaryFileResponse
    {
        [$from, $to] = $this->resolveDateRange($request);
        $categoryId = $request->query('category_id');
        
        return Excel::download(
            new SalesReportExport($this->filteredQuery($from, $to, $categoryId)),
            'laporan-penjualan.xlsx',
        );
    }

public function exportPdf(Request $request): Response
    {
        [$from, $to] = $this->resolveDateRange($request);
        $categoryId = $request->query('category_id');
        
        $pdf = Pdf::loadView('reports.pdf', [
            'totalPenjualan'  => $this->filteredQuery($from, $to, $categoryId)->sum('total'),
            'jumlahTransaksi' => $this->filteredQuery($from, $to, $categoryId)->count(),
            'topProducts'     => $this->topProducts($from, $to), // <-- Kode fitur temanmu tetap utuh
            'transactions'    => $this->filteredQuery($from, $to, $categoryId)->latest()->limit(50)->get(),
            'from'            => $from->toDateString(),
            'to'              => $to->toDateString(),
        ]);
        
        return $pdf->download('laporan-penjualan.pdf');
    }

    public function importForm(): View
    {
        return view('reports.import', [
            'importErrors' => ImportError::orderBy('row_number')->get(),
        ]);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt'],
        ]);
        
        $path = $request->file('csv')->store('imports');
        ProcessProductImport::dispatch($path);
        
        return back()->with('success', 'Import sedang diproses di background.');
    }

    private function resolveDateRange(Request $request): array
    {
        $from = $request->date('from')?->startOfDay() ?? now()->startOfMonth();
        $to = $request->date('to')?->endOfDay() ?? now()->endOfDay();
        return [$from, $to];
    }

    private function filteredQuery($from, $to, $categoryId = null): Builder
    {
    $query = Transaction::with('user')
        ->whereBetween('created_at', [$from, $to]);

    if (!empty($categoryId)) {
        $query->whereHas('details.product', function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        });
    }

    return $query;
    }

    private function topProducts($from, $to, int $limit = 10)
    {
        return TransactionDetail::query()
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$from, $to])
            ->select(
                'products.name',
                DB::raw('SUM(transaction_details.qty) as total_qty'),
                DB::raw('SUM(transaction_details.subtotal) as total_subtotal')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->orderByDesc('total_subtotal')
            ->limit($limit)
            ->get();
    }

    public function importExcel(Request $request): RedirectResponse
    {
        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        ImportError::query()->delete();

        $import = new ProductImport();

        Excel::import($import, $request->file('csv'));

        foreach ($import->failures() as $failure) {
            ImportError::create([
                'row_number' => $failure->row(),
                'error_message' => implode(' ', $failure->errors()),
            ]);
        }

        $jumlahGagal = $import->failures()->count();

        return back()->with(
            'success',
            "Import Laravel Excel selesai. {$jumlahGagal} baris gagal divalidasi."
        );
    }
}