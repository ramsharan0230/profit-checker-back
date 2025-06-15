<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quote Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .section {
            margin-bottom: 20px;
        }

        h2,
        h4 {
            margin-bottom: 10px;
        }

        ul {
            margin: 0;
            padding-left: 20px;
        }

        li {
            margin-bottom: 4px;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>Quote Summary(Date: {{ Carbon\Carbon::now()->toDateTimeString() }})</h2>
    <hr>

    <div class="section">
        <h4><u>Product Breakdown</u></h4>
        <ul>
            @foreach ($items as $item)
            <li>
                <span class="bold">{{ $item['selectedProduct'] }}</span> — Qty: {{ $item['quantity'] }},
                Cost: £{{ $item['cost'] }}, Sell: £{{ $item['sell'] }}
            </li>
            @endforeach
        </ul>
        <ul>
            <hr style="margin: 10px 0; border-top: 1px solid #000; width: 80%;">
            <li class="bold mt-2">
                Total Quantity: {{ $totalQty }},
                Total Cost: £{{ number_format($totalCost, 2) }},
                Total Sell: £{{ number_format($totalSell, 2) }}
            </li>
        </ul>
    </div>

    <div class="section">
        <h4><u>Labor & Overhead</u></h4>
        <p><span class="bold">Labor Hours:</span> {{ $laborHours }}</p>
        <p><span class="bold">Labor Cost per Hour:</span> £{{ $laborCost }}</p>
        <p><span class="bold">Total Labor Cost:</span> £{{ number_format($totalLaborCost, 2) }}</p>
        <p><span class="bold">Fixed Overheads:</span> £{{ number_format($fixedOverheads, 2) }}</p>
        <hr>
        <strong>Total Labor & Overhead: £{{ number_format($laborAndOverhead, 2) }}</strong>
    </div>

    <div class="section">
        <h4><u>Financial Summary</u></h4>
        <p><span class="bold">Total Gross Profit:</span> £{{ number_format($grossProfit, 2) }}</p>
        <p><span class="bold">Margin:</span> {{ number_format($margin, 2) }}%</p>
        <p><span class="bold">Target Margin:</span> {{ number_format($targetMargin, 2) }}%</p>
    </div>

    @if (!empty($aiSuggestion))
    <hr>
    <h4>AI Suggestions</h4>
    <p style="white-space: pre-line;">{{ $aiSuggestion }}</p>
    @endif
</body>

</html>