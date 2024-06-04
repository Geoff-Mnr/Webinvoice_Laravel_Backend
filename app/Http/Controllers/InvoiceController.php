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
    public function generateInvoice($documentId)
    {
        $document = Document::with(['customer', 'products', 'documenttype'])->findOrFail($documentId);
        $data = [
        'document' => $document,
        'customer' => $document->customer,
        'products' => $document->products,
        'documenttype' => $document->documenttype,
        'reference_number' => $document->reference_number,
        'name' => $document->customer->company_name, 
    ];

    $pdf = PDF::loadView('invoice', $data);
    return $pdf->download('invoice.pdf');
    }
}