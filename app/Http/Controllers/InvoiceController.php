<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Product;
use App\Models\Documenttype;

class InvoiceController extends Controller
{
    public function generateInvoice(Request $request)
    {
        $data = [
            'name' => 'John Doe',
            'date' => '2021-09-01',
            'invoice_number' => 'INV-0001',
        ];

        $pdf = PDF::loadView('invoice', $data);
        return $pdf->download('invoice.pdf');
    }
}