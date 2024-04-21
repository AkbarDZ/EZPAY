<?php

namespace App\Exports;

use App\Models\Sales;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        // Eager load the customer relationship and filter sales data based on date range
        return Sales::with('customer')->whereBetween('sales_date', [$this->startDate, $this->endDate])->get()
            ->map(function ($sale) {
                $formattedPrice = 'Rp ' . number_format($sale->total_price, 0, ',', '.');

                return [
                    $sale->id,
                    $sale->sales_date, // Format date as desired
                    $formattedPrice, // Formatted total price in Indonesian format
                    $sale->customer->cust_name, // Access customer name through the relationship
                    $sale->user->name,
                    // Add more fields as needed
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Sales Date',
            'Total Price',
            'Customer Name',
            'Cashier Name',
            // Add more headings as needed
        ];
    }
}


