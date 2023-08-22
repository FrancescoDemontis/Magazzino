<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ProductRequestsExport implements FromCollection
{
    protected $productRequests;

    public function __construct($productRequests)
    {
        $this->productRequests = $productRequests;
    }

    public function collection()
    {
        return $this->productRequests;
    }
}