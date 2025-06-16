<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService
{
    public function generateSuggestion(
        array $selectedItems,
        array $allProducts,
        float $grossProfit,
        float $margin,
        float $targetMargin
    ): string {
        $model = config('openai.api_model');
        $selectedSummary = collect($selectedItems)->map(function ($item) {
            return "- {$item['selectedProduct']} | Qty: {$item['quantity']} | Cost: £{$item['cost']} | Sell: £{$item['sell']}";
        })->implode("\n");

        $availableSummary = collect($allProducts)->map(function ($product) {
            $mpn = $product['mpn'] ?? 'N/A';
            $sku = $product['sku'] ?? 'N/A';
            return "- {$product['name']} (MPN: {$mpn}, SKU: {$sku}) | Cost: £{$product['trade_price']} | Sell: £{$product['retail_price']}";
        })->implode("\n");

        $userPrompt = <<<EOT
You are a profitability assistant helping AV dealers improve their quotes.

Here is the current quote:
$selectedSummary

Gross Profit: £{$grossProfit}
Margin: {$margin}%
Target Margin: {$targetMargin}%

Here is the full product catalog:
$availableSummary

Please suggest ways to improve the quote:
- Could we replace any item with better profit margins?
- Should we increase quantities or selling prices?
- Recommend pricing or substitution strategies to meet the target margin.
EOT;

        $response = OpenAI::chat()->create([
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an AV business profitability expert. Suggest optimal pricing or substitutions to improve quote profit margins.'
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt
                ]
            ],
        ]);

        return $response['choices'][0]['message']['content'] ?? 'No suggestion generated.';
    }
}
 