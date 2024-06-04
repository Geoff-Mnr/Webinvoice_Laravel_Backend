<!DOCTYPE html>
<html>
<head>
    <title>Facture</title>
    <style>
        .content {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .details {
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <h1>Facture</h1>
        </div>
        <div class="details">
            <p><strong>Date :</strong> {{$documenttype -> name }}</p>
            <p><strong>Numéro de facture :</strong> {{$document->reference_number}}</p>
            <p><strong>Client :</strong> {{$customer->company_name}}</p>
            @foreach($products as $product)
            <p><strong>Produit :</strong> {{$product->name}}</p>
            @endforeach
        </div>
    </div>
</body>
</html>