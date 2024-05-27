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
            <p><strong>Client :</strong> {{$name}}</p>
            <p><strong>Produit :</strong> {{$date}}</p>
            <p><strong>Type de document :</strong> {{$invoice_number}}</p>
        </div>
        <div class="footer">
            <p>Merci pour votre achat !</p>
        </div>
    </div>
</body>
</html>