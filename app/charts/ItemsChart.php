<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Item;

class ItemsChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {

        $items = Item::get();
        $good = $items->where('condition', 'good')->sum('quantity');
        $fair = $items->where('condition', 'fair')->sum('quantity');
        $bad = $items->where('condition', 'bad')->sum('quantity');
        // dd($data);
        return $this->chart->pieChart()
            ->setTitle('Count Of Users By Role')
            ->setSubtitle(date('Y'))
            ->addData([$good, $fair, $bad])
            ->setLabels(['Baik', 'Sedang', 'Rusak']);
    }
}
