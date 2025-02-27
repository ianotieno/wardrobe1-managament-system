<?php

namespace App\Http\Controllers;

use App\Models\ClothingItem;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;


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

        $validated['user_id'] = auth()->id();
        $item = ClothingItem::create($validated);

        return response()->json($item, 201);
    }

    public function update(Request $request, $id)
{
    // Log incoming request details
    \Log::info('Request method:', [$request->method()]);
    \Log::info('Headers:', $request->headers->all());
    \Log::info('Raw input:', ['raw' => file_get_contents('php://input')]);
    \Log::info('Parsed form data:', $request->all());
    \Log::info('Uploaded files:', $request->files->all());

    $item = ClothingItem::findOrFail($id);
    if ($item->user_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    \Log::info('Item before update:', $item->toArray());

    // Try standard parsing first
    $data = [
        'name' => $request->input('name', $item->name),
        'category' => $request->input('category', $item->category),
        'description' => $request->input('description', $item->description),
    ];

    // If standard parsing fails, parse raw input manually
    if (empty($request->all()) && $request->method() === 'PATCH') {
        $rawInput = file_get_contents('php://input');
        if ($rawInput) {
            $boundary = substr($rawInput, 0, strpos($rawInput, "\r\n"));
            $parts = array_slice(explode($boundary, $rawInput), 1);

            foreach ($parts as $part) {
                if (trim($part) === '--') continue;
                preg_match('/Content-Disposition: form-data; name="([^"]+)"(?:; filename="([^"]+)")?.*?\r\n\r\n(.*?)(\r\n|$)/s', $part, $matches);
                if ($matches) {
                    $fieldName = $matches[1];
                    $value = rtrim($matches[3], "\r\n");
                    if (in_array($fieldName, ['name', 'category', 'description'])) {
                        $data[$fieldName] = $value;
                    }
                }
            }
        }
    }

    // Handle image upload (if detected)
    if ($request->hasFile('image')) {
        if ($item->image) {
            \Storage::disk('public')->delete($item->image);
        }
        $data['image'] = $request->file('image')->store('clothing_items', 'public');
    }

    \Log::info('Data to update:', $data);
    $item->fill($data)->save();
    $item->refresh();

    \Log::info('Item after update:', $item->toArray());
    return response()->json($item->toArray());
}
    public function destroy($id)
    {
        $item = ClothingItem::findOrFail($id);
        if ($item->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if ($item->image) {
            \Storage::disk('public')->delete($item->image);
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