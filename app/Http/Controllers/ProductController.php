<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function showSimilarProducts(Request $request, $productId)
    {
        $startTime = microtime(true);
        // Retrieve the original product using its ID
        $product = Product::find($productId);
    
        // Check if the similar products are already cached
        $key = 'similar_products_' . $product->id;
        $similarProducts = Cache::get($key);
        if ($similarProducts === null) {
            // Split the product name into words
            $words = explode(' ', strtolower($product->name));
    
            // Use the database search capabilities to find the similar products based on the product name
            // Limit the number of products retrieved from the database to reduce the processing time
            $query = Product::query();
            foreach ($words as $word) {
                $query->orWhere('name', 'LIKE', '%' . $word . '%');
            }
            $query->where('id', '!=', $product->id)->orderByDesc('frequency')->take(15);

            $similarProducts = $query->get();

            // If the final list of similar products is less than 15, select random products based on popularity to complete the list
            if ($similarProducts->count() < 15) {
                $randomProducts = Product::whereNotIn('id', $similarProducts->pluck('id')->push($product->id)->toArray())
                    ->orderByDesc('frequency')
                    ->take(15 - $similarProducts->count())
                    ->get()
                    ->shuffle();
    
                $similarProducts = $similarProducts->merge($randomProducts);
            }
            
            // Store the similar products in the cache with an expiration time of 1 hour
            Cache::put($key, $similarProducts, 60 * 60);
        }
        $endTime = microtime(true);
        $request_time = $endTime - $startTime;
    
        // Return the view with the similar products and the original product
        return view('similar_products', compact('similarProducts', 'product', 'request_time'));
    }
}
