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
        // Filter sales data based on date range
        return Sales::whereBetween('sales_date', [$this->startDate, $this->endDate])->get();
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
