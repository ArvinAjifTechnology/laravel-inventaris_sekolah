<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\User;

class UsersChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        $users = User::get();
            $admin = $users->where('role', 'admin')->count();
            $operator = $users->where('role', 'operator')->count();
            $borrower = $users->where('role', 'borrower')->count();
        // dd($data);
        return $this->chart->pieChart()
            ->setTitle('Count Of Users By Role')
            ->setSubtitle(date('Y'))
            ->addData([$admin, $operator, $borrower])
            ->setLabels(['Admin', 'Operator', 'Borrower']);
    }
}