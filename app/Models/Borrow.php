<?php

namespace App\Models;

use App\Events\BorrowCreated;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\BorrowNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrow extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dispatchesEvents = [
        'created' => BorrowCreated::class,
    ];

    /**
     * Baris Code Berikut Di Gunakan Untuk
     * Melakukan Query Pada Halaman
     * Dashboard
     */

    public static function getCurrentWeekData()
    {
        // Mendapatkan tanggal awal dan akhir dari minggu ini
        $currentWeekStart = date('Y-m-d', strtotime('this week'));
        $currentWeekEnd = date('Y-m-d', strtotime('this week +6 days'));

        // Menghitung data untuk minggu ini
        $currentWeekData = self::whereBetween('updated_at', [$currentWeekStart, $currentWeekEnd])->sum('sub_total');
        // dd($currentWeekStart, $currentWeekEnd, $currentWeekData);
        return $currentWeekData;
    }

    public static function getPreviousWeekData()
    {
        // Mendapatkan tanggal awal dan akhir dari minggu sebelumnya
        $previousWeekStart = date('Y-m-d', strtotime('last week'));
        $previousWeekEnd = date('Y-m-d', strtotime('last week +6 days'));

        // Menghitung data untuk minggu sebelumnya
        $previousWeekData = self::whereBetween('updated_at', [$previousWeekStart, $previousWeekEnd])->sum('sub_total');
        // dd($previousWeekStart, $previousWeekEnd, $previousWeekData);

        return $previousWeekData;
    }

    public static function getCurrentDayData()
    {
        $currentDate = date('Y-m-d');

        // Menghitung data untuk hari ini
        $currentDayData = self::whereDate('updated_at', $currentDate)->sum('sub_total');

        return $currentDayData;
    }
    /**
     * Baris Code Ini Digunakan Untuk
     * Sistem Peminjaman yang Di Akses
     * Oleh Role Admin Dan Operator
     */

    public static function getAll()
    {
        return DB::table('borrows')
            ->join('users', 'borrows.user_id', '=', 'users.id')
            ->join('items', 'borrows.item_id', '=', 'items.id')
            ->select('borrows.*', 'items.item_name', 'items.item_code', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"))
            ->get();
    }

    public static function findId($id)
    {
        DB::select('select * from items where id = ?', [$id]);
    }

    public static function createBorrow($request)
    {
        $borrow = new Borrow();
        $borrow->borrow_date = $request->input('borrow_date');
        $borrow->return_date = $request->input('return_date');
        $borrow->item_id = $request->input('item_id');
        $borrow->user_id = $request->input('user_id');
        $borrow->borrow_quantity = $request->input('borrow_quantity');
        $borrow->late_fee = 0;
        $borrow->total_rental_price = 0;
        $borrow->borrow_status = 'borrowed';
        $borrow->sub_total = 0;
        $borrow->save();
        // dd($borrow->borrow_code);
        return $borrow;
    }

    public function calculateLateFee($borrow)
    {
        $dueDate = $this->return_date;
        $actualReturnDate = date('Y-m-d');
        $daysLate = $this->calculateDaysLate($actualReturnDate, $dueDate);

        if ($actualReturnDate < $dueDate) {
            $daysLate = 0;
        }

        $lateFee = 0;
        $lateFeePerDay = $this->item->late_fee_per_day;

        if ($daysLate > 0) {
            $lateFee = $daysLate * $lateFeePerDay * $borrow->borrow_quantity;
        }

        return $lateFee;
    }

    private function calculateDaysLate($date1, $date2)
    {
        $daysLate = DB::select("SELECT DATEDIFF(?, ?) AS daysLate", [$date1, $date2])[0]->daysLate;
        return $daysLate;
    }


    public static function destroy($id)
    {
        DB::delete('DELETE FROM borrows WHERE id = ?', [$id]);
    }

    public function calculateTotalRentalPrice($borrow)
    {
        $borrowDate = $this->borrow_date;
        $returnDate = $this->return_date;
        $actualReturnDate = date('Y-m-d');
        $daysLate = 0;

        if ($actualReturnDate <= $returnDate) {
            $totalDays = $this->calculateDaysDiff($actualReturnDate, $borrowDate);
        } else {
            $totalDays = $this->calculateDaysDiff($returnDate, $borrowDate);
        }

        $rentalPrice = $this->item->rental_price;
        $totalRentalPrice = $totalDays * $rentalPrice * $borrow->borrow_quantity;

        return $totalRentalPrice;
    }

    private function calculateDaysDiff($date1, $date2)
    {
        $daysDiff = DB::select("SELECT DATEDIFF(?, ?) AS daysDiff", [$date1, $date2])[0]->daysDiff + 1;
        return $daysDiff;
    }


    public function calculateSubTotal()
    {
        $sub_total = $this->late_fee + $this->total_rental_price;

        return $sub_total;
    }

    public static function returnItem($id)
    {
        $borrow = Borrow::find($id);
        if (!$borrow) {
            return redirect()->back()->with('error', 'Data not found');
        }

        $item = Item::find($borrow->item_id);
        $item->quantity += $borrow->borrow_quantity;
        $item->update();

        $borrow->total_rental_price = $borrow->calculateTotalRentalPrice($borrow);
        $borrow->late_fee = $borrow->calculateLateFee($borrow);
        $borrow->sub_total = $borrow->calculateSubTotal();
        $borrow->borrow_status = 'completed';
        $borrow->update();

        $borrow->user->notify(new BorrowNotification($borrow));
    }

    /**
     * Fungsi berikut Digunakan Oleh Role
     * Peminjam Untuk Melakukan
     * Proses Pengajuan
     * Peminjaman
     */

    public static function submitBorrowRequest($request)
    {
        $borrow = new Borrow();
        $borrow->verification_code_for_borrow_request = $request->input('uniqid');
        $borrow->borrow_date = $request->input('borrow_date');
        $borrow->return_date = $request->input('return_date');
        $borrow->item_id = $request->input('item_id');
        $borrow->user_id = $request->input('user_id');
        $borrow->borrow_quantity = $request->input('borrow_quantity');
        $borrow->late_fee = 0;
        $borrow->total_rental_price = 0;
        $borrow->borrow_status = 'pending';
        $borrow->sub_total = 0;
        $borrow->save();
        return $borrow;
    }

    /**
     * Fungsi Berikut Digunakan Oleh Role Admin
     * Untuk Melakukan Proses Verifikasi
     * Yang Di Ajukan Role Borrower
     */

    public static function verifySubmitBorrowRequest($request, $borrow_code)
    {
        $borrow = Borrow::where('borrow_code', '=', $borrow_code)->first();
        $borrow->borrow_date = $request->borrow_date;
        $borrow->return_date = $request->input('return_date');
        $borrow->item_id = $request->input('item_id');
        $borrow->user_id = $request->input('user_id');
        $borrow->borrow_quantity = $request->input('borrow_quantity');
        $borrow->late_fee = 0;
        $borrow->total_rental_price = 0;
        $borrow->borrow_status = 'borrowed';
        $borrow->sub_total = 0;
        $borrow->save();

        return $borrow;
    }

    public static function rejectBorrowRequest($id)
    {
        $borrow = Borrow::find($id);
        if (!$borrow) {
            return redirect()->back()->with('error', 'Data not found');
        }

        $borrow->total_rental_price = 0;
        $borrow->late_fee = 0;
        $borrow->sub_total = 0;
        $borrow->borrow_status = substr('rejected', 0, 8);
        $borrow->update();

        $borrow->user->notify(new BorrowNotification($borrow));
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
