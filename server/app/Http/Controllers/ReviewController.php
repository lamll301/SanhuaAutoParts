<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller
{
    public function getByProductSlug(string $slug, Request $request) {
        $product = Product::where('slug', $slug)->firstOrFail();

        $reviews = Review::with([
            'user:id,name,avatar_id',
            'user.avatar:id,path',
            'images:id,path,review_id',
        ])->where('product_id', $product->id)
        ->select('id', 'user_id', 'product_id', 'rating', 'comment', 'updated_at')
        ->orderBy('created_at', 'desc');

        return $this->getListResponse($reviews, $request, [], [], 10);
    }
    public function store(Request $request) {
        Review::create($request->all());
        return response()->json(['message' => 'success'], 201);
    }
    public function update(Request $request, string $id) {
        $review = Review::findOrFail($id);
        $review->update($request->all());
        return response()->json(['message' => 'success'], 200);
    }
    public function destroy(string $id) {
        $review = Review::findOrFail($id);
        $review->delete();
        return response()->json(['message' => 'success'], 200);
    }
}
