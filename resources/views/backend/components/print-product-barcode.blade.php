<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Barcodes</title>
    <style>
        .container {
            text-align: center;
            width: 100%;
            margin: auto;
        }
        .row {
            width: 100%;
            overflow: hidden;
        }
        .col {
            display: inline-block;
            width: 30%;
            margin: 5px 0px;
      
            padding: 5px;
            box-sizing: border-box;
        }
        img {
            max-width: 80%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            @foreach($barcodes as $index => $item)
                <div class="col">
                    <p>{{ $item['name'] }} - Rp.{{ number_format($item['selling_price'], 0, ',', '.') }}</p>
                    <img src="data:image/png;base64,{{ $item['barcode'] }}" alt="Barcode">
                    <p>{{ $item['code'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
