<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Exports\BorrowExport;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class BorrowReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $borrows = Borrow::with(['user', 'item'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('borrow_date', [$startDate, $endDate]);
            })
            ->where(function ($query) use ($search) {
                $query->where('borrow_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('borrow_status', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('item', function ($query) use ($search) {
                        $query->where('item_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('item_code', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('username', 'LIKE', '%' . $search . '%')
                            ->orWhere('email', 'LIKE', '%' . $search . '%');
                    });
            })
            ->groupBy('borrows.id')
            ->get();

        return response()->json(['data' => $borrows], 200);
    }

    /**
     * Generate the report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $borrows = Borrow::with(['user', 'item'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('borrow_date', [$startDate, $endDate]);
            })
            ->where(function ($query) use ($search) {
                $query->where('borrow_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('borrow_status', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('item', function ($query) use ($search) {
                        $query->where('item_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('item_code', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('username', 'LIKE', '%' . $search . '%')
                            ->orWhere('email', 'LIKE', '%' . $search . '%');
                    });
            })
            ->groupBy('borrows.id')
            ->get();

        return response()->json(['data' => $borrows]);
    }

    /**
     * Export the report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');
        $type = $request->input('type');

        $query = Borrow::with(['user', 'item']);

        if ($startDate && $endDate) {
            $query->whereBetween('borrow_date', [$startDate, $endDate]);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('borrow_code', 'LIKE', "%$search%")
                    ->orWhere('borrow_status', 'LIKE', "%$search%")
                    ->orWhereHas('item', function ($q) use ($search) {
                        $q->where('item_name', 'LIKE', "%$search%")
                            ->orWhere('item_code', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('username', 'LIKE', "%$search%")
                            ->orWhere('email', 'LIKE', "%$search%");
                    });
            });

            $borrows = $query->get();

            if ($type === 'pdf') {
                $pdf = PDF::loadView('borrow-report.export-pdf', compact('borrows', 'startDate', 'endDate', 'search'));
                $pdf->setPaper('a4', 'landscape');
                $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
                $pdfContent = $pdf->output();

                return response()->streamDownload(function () use ($pdfContent) {
                    echo $pdfContent;
                }, 'borrow-report.pdf');
            } elseif ($type === 'excel') {
                $fileName = 'borrow-report.xlsx';
                return Excel::download(new BorrowExport($borrows, $startDate, $endDate, $search), $fileName);
            }
        }
    }
}
