<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Borrow;

class BorrowsChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        $startDate = now()->subMonth()->startOfMonth(); // Tanggal mulai sebulan yang lalu
        $endDate = now()->endOfDay(); // Tanggal akhir sebulan yang lalu
        $borrows = Borrow::whereBetween('created_at', [$startDate, $endDate])->get();

        $data = $borrows->groupBy(function ($item) {
            return $item->created_at->toDateString();
        })->map(function ($group) {
            return [
                'pending' => $group->where('borrow_status', 'pending')->count(),
                'borrowed' => $group->where('borrow_status', 'borrowed')->count(),
                'completed' => $group->where('borrow_status', 'completed')->count(),
            ];
        })->toArray();

        $pendingData = [];
        $borrowedData = [];
        $completedData = [];

        foreach ($data as $date => $status) {
            $pendingData[] = $status['pending'];
            $borrowedData[] = $status['borrowed'];
            $completedData[] = $status['completed'];
        }

        return $this->chart->lineChart()
            ->setTitle('Count Of Borrowed')
            ->setSubtitle(date('Y'))
            ->addData('Pending', $pendingData)
            ->addData('Borrowed', $borrowedData)
            ->addData('Completed', $completedData)
            ->setLabels(array_keys($data));
    }
}