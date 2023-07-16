<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Borrow;

class RevenueChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        $startDate = now()->subMonth()->startOfMonth();
        $endDate = now()->endOfDay();
        $borrows = Borrow::whereBetween('created_at', [$startDate, $endDate])->get();

        $data = $borrows->groupBy(function ($item) {
            return $item->created_at->toDateString();
        })->map(function ($group) {
            return $group->sum('sub_total');
        })->toArray();

        $revenueData = array_values($data);
        // Preprocess the data to convert it to the desired format
        $formattedRevenueData = array_map(function ($value) {
            return 'Rp ' . number_format($value, 0, ',', '.');
        }, $revenueData);

        $revenueChart = $this->chart->lineChart()
            ->setTitle('Revenue')
            ->setSubtitle(date('Y'))
            ->addData('Revenue', $revenueData)
            ->setLabels(convertToRupiah(array_keys($data)));

        return $revenueChart;
    }
}