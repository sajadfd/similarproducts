<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function showSimilarProducts(Request $request, $productId)
    {
        // Retrieve the original product using its ID
        $product = Product::find($productId);


        // Check if the similar products are already cached
        $key = 'similar_products_' . $product->id;
        $similarProducts = Cache::get($key);
        if ($similarProducts === null) {
            // Retrieve all products from the database
            $products = Product::all();
            // Retrieve all similar products based on the original product name and popularity
            $similarProducts = $products->filter(function ($p) use ($product) {
                // Calculate the number of common words between the product names
                $commonWords = array_intersect(
                    explode(' ', strtolower($product->name)),
                    explode(' ', strtolower($p->name))
                );
                // Consider at least one common word to be a match
                return count($commonWords) > 0 && $p->id != $product->id;
            })->sortByDesc('frequency')->take(15);
            // If the final list of similar products is less than 15, select random products based on popularity to complete the list
            if ($similarProducts->count() < 15) {
                $randomProducts = $products->reject(function ($p) use ($similarProducts, $product) {
                    // Exclude the original product and the already selected similar products
                    return $p->id == $product->id || $similarProducts->contains('id', $p->id);
                })->sortByDesc('frequency')->take(15 - $similarProducts->count())->shuffle();

                $similarProducts = $similarProducts->merge($randomProducts);
            }
            // Store the similar products in the cache with an expiration time of 1 hour
            Cache::put($key, $similarProducts, 60 * 60);
        }

        // Return the view with the similar products and the original product
        return view('similar_products', compact('similarProducts', 'product'));
    }






    // public function showSimilarProducts(Request $request, $productId)
    // {
    //     // Retrieve the original product using its ID
    //     $product = Product::find($productId);

    //     // Check if the similar products are already cached
    //     $key = 'similar_productss_' . $product->id;
    //     $similarProducts = Cache::get($key);
    //     if ($similarProducts === null) {
    //         // Set the search query and the threshold for the minimum similarity score
    //         $searchQuery = $product->name;
    //         $threshold = 0.8;

    //         // Retrieve the products that match the search query using full-text search
    //         $products = DB::table('products')
    //             ->whereRaw("MATCH(name) AGAINST(? IN BOOLEAN MODE)", [$searchQuery])
    //             ->get();

    //         // Filter products based on fuzzy matching of the product names
    //         $similarProducts = $products->filter(function ($p) use ($product, $threshold) {
    //             // Calculate the Levenshtein distance between two strings
    //             $distance = mb_strlen($product->name) + mb_strlen($p->name) - 2 * similar_text($product->name, $p->name);
    //             $similarity = 1 - $distance / max(mb_strlen($product->name), mb_strlen($p->name));
    //             // Consider products with a similarity score above the threshold to be a match
    //             return $similarity >= $threshold && $p->id != $product->id;
    //         })->sortBy('frequency')->take(15);
    //         // If the final list of similar products is less than 15, select random products based on popularity to complete the list
    //         if ($similarProducts->count() < 15) {
    //             $randomProducts = $products->reject(function ($p) use ($similarProducts, $product) {
    //                 // Exclude the original product and the already selected similar products
    //                 return $p->id == $product->id || $similarProducts->contains('id', $p->id);
    //             })->sortBy('frequency')->take(15 - $similarProducts->count())->shuffle();

    //             $similarProducts = $similarProducts->merge($randomProducts);
    //         }
    //         // Store the similar products in the cache with an expiration time of 1 hour
    //         Cache::put($key, $similarProducts, 60 * 60);
    //     }

    //     // Return the view with the similar products and the original product
    //     return view('similar_products', compact('similarProducts', 'product'));
    // }
}
