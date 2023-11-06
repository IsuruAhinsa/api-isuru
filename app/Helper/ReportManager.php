<?php

namespace App\Helper;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportManager
{
    private Collection $products;
    private Collection $orders;
    public int $total_products = 0;
    public int $total_stock = 0;
    public int $low_stock = 0;
    public float $buy_stock_price = 0;
    public float $sale_stock_price = 0;
    public float $possible_profit = 0;
    public float $total_sale = 0;
    public float $total_sale_today = 0;
    public float $total_purchase = 0;
    public float $total_purchase_today = 0;
    public const LOW_STOCK_ALERT = 5;

    public function __construct()
    {
        $this->getProducts();
        $this->setTotalProduct();
        $this->calculateStock();
        $this->findLowStock();
        $this->calculateBuyingStockPrice();
        $this->calculateSellingStockPrice();
        $this->calculatePossibleProfit();
        $this->getOrders();
        $this->calculateTotalSale();
        $this->calculateTotalSaleToday();
        $this->calculateTotalPurchase();
        $this->calculateTotalPurchaseToday();
    }

    private function getProducts()
    {
        $this->products = (new Product())->getProducts();
    }

    private function setTotalProduct()
    {
        $this->total_products = count($this->products);
    }

    private function calculateStock()
    {
        $this->total_stock = $this->products->sum('stock');
    }

    private function findLowStock()
    {
        $this->low_stock = $this->products->where('stock', '<=', self::LOW_STOCK_ALERT)->count();
    }

    private function calculateBuyingStockPrice()
    {
        foreach ($this->products as $product) {
            $this->buy_stock_price += ($product->cost * $product->stock);
        }
    }

    private function calculateSellingStockPrice()
    {
        foreach ($this->products as $product) {
            $this->sale_stock_price += ($product->price * $product->stock);
        }
    }

    private function calculatePossibleProfit()
    {
        $this->possible_profit = $this->sale_stock_price - $this->buy_stock_price;
    }

    private function getOrders()
    {
        $this->orders = (new Order())->getOrders();
    }

    private function calculateTotalSale()
    {
        $this->total_sale = $this->orders->sum('total');
    }

    private function calculateTotalSaleToday()
    {
        $this->total_sale_today = $this->orders
            ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
            ->sum('total');
    }

    private function calculateTotalPurchase()
    {
        $this->total_purchase = $this->buy_stock_price;
    }

    private function calculateTotalPurchaseToday()
    {
        $products_buy_today = $this->products
            ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()]);

        foreach ($products_buy_today as $product) {
            $this->total_purchase_today += ($product->cost * $product->stock);
        }
    }
}
