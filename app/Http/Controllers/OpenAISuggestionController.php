<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OpenAISuggestionController extends Controller
{
    public function __construct(protected OpenAIService $openAIService){}

    public function suggestion(Request $request)
    {
        $response = $this->openAIService->generateSuggestion(
            $request->input('selectedItems'),
            $request->input('allProducts', []),
            $request->input('grossProfit'),
            $request->input('margin'),
            $request->input('targetMargin')
        );

        return ApiResponse::success($response, "Suggestion fetched successfully", Response::HTTP_OK);
    }
}
