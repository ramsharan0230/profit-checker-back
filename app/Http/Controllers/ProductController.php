<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function fetchProducts(Request $request): JsonResponse
    {
        $payload = ProductResource::collection(Product::all());
        return ApiResponse::success($payload, "Products fetched successfully.", Response::HTTP_OK);
    }
    
}
