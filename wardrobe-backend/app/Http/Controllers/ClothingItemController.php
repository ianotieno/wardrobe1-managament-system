<?php

namespace App\Http\Controllers;

use App\Models\ClothingItem; // Import the model
use Illuminate\Http\Request;

class ClothingItemController extends Controller
{
    public function index()
    {
        $items = auth()->user()->clothingItems;
        return response()->json($items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category,
                'description' => $item->description,
                'image' => $item->image,
            ];
        }));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:tops,bottoms,shoes',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('clothing_items', 'public');
            $validated['image'] = $path;
        }

        $validated['user_id'] = auth()->id(); // Link to authenticated user

        $item = ClothingItem::create($validated); // Use the imported model

        return response()->json($item, 201);
    }

    public function destroy($id)
    {
        $item = ClothingItem::findOrFail($id);
        if ($item->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $item->delete();
        return response()->json(null, 204);
    }
    public function logout(Request $request)
{
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Logged out']);
}
}