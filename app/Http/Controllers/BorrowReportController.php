<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrow;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\BorrowExport;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\DB;

class BorrowReportController extends Controller
{
    public function index()
    {
        $borrows = Borrow::query();
        $sum_of_sub_total = 0;
        return view('borrow-report.index', compact('borrows'));
    }

    public function generateReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $borrows = Borrow::selectRaw("borrows.*,
                        CONCAT(users.first_name, ' ', users.last_name) AS user_full_name,
                        users.*,
                        items.*,
                        SUM(borrows.sub_total) AS revenue")
            ->join('users', 'borrows.user_id', '=', 'users.id')
            ->join('items', 'borrows.item_id', '=', 'items.id')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('borrows.borrow_date', [$startDate, $endDate]);
            })
            ->where(function ($query) use ($search) {
                $query->where('borrows.borrow_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('borrows.borrow_status', 'LIKE', '%' . $search . '%')
                    ->orWhere('items.item_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('items.item_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.username', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.email', 'LIKE', '%' . $search . '%');
            })
            ->groupBy('borrows.id')
            ->get();

        return view('borrow-report.index', compact('borrows', 'startDate', 'endDate', 'search'));
    }



    public function export(Request $request)
    {
        // dd($request->all());
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');
        $type = $request->input('type');

        $query = Borrow::query();
        // dd($query);
        // Filter tanggal
        if ($startDate && $endDate) {
            $query->whereBetween('borrow_date', [$startDate, $endDate]);
        }

        // Pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('borrow_code', 'LIKE', "%$search%")
                    ->orWhere('borrow_status', 'LIKE', "%$search%")
                    ->orWhereHas('item', function ($q) use ($search) {
                        $q->where('item_name', 'LIKE', "%$search%")
                            ->orWhere('item_code', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%")
                            ->orWhere('email', 'LIKE', "%$search%");
                    });
            });
            // dd($query);

            $borrows = $query->get();

            if ($type === 'pdf') {
                $pdf = FacadePdf::loadView('borrow-report.export-pdf', compact('borrows', 'startDate', 'endDate', 'search'));
                return $pdf->download('borrow-report.pdf');
            } elseif ($type === 'excel') {
                return Excel::download(new BorrowExport($borrows, $startDate, $endDate, $search), 'borrow-report.xlsx');
            }
        }
    }
}
