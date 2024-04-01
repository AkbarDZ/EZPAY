<?php

namespace App\Exports;

use App\Models\Sales;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
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
                return [
                    'ID' => $sale->id,
                    'Sales Date' => $sale->sales_date,
                    'Total Price' => $sale->total_price,
                    'Customer Name' => $sale->customer->cust_name, // Access customer name through the relationship
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
            // Add more headings as needed
        ];
    }
}
