<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return view('index');
    }

    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'olx_url' => 'required|url',
            'email' => 'required|email',
        ]);

        $this->productService->subscribeToProduct(
            $request->input('olx_url'),
            $request->input('email')
        );

        return response()->json(['message' => 'Subscription successful']);
    }
}
