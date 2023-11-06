<?php

namespace App\Http\Controllers;

use App\Helper\ReportManager;

class ReportController extends Controller
{
    public function index()
    {
        $reportManager = new ReportManager();
        $report = [
            'total_products' => $reportManager->total_products,
            'total_stock' => $reportManager->total_stock,
            'low_stock' => $reportManager->low_stock,
            'buy_value' => $reportManager->buy_stock_price,
            'sale_value' => $reportManager->sale_stock_price,
            'possible_profit' => $reportManager->possible_profit,
            'total_sale' => $reportManager->total_sale,
            'total_sale_today' => $reportManager->total_sale_today,
            'total_purchase' => $reportManager->total_purchase,
            'total_purchase_today' => $reportManager->total_purchase_today,
        ];

        return response()->json($report);
    }
}
