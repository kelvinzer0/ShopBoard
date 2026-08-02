<?php

namespace App\Http\Controllers;

use App\Models\ShoppingItem;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ShoppingItemController extends Controller
{
    public function index()
    {
        $items = ShoppingItem::orderBy('created_at', 'desc')->get();

        $board = [
            'wishlist'  => $items->where('status', 'wishlist')->values(),
            'ready'     => $items->where('status', 'ready')->values(),
            'completed' => $items->where('status', 'completed')->values(),
        ];

        return Inertia::render('Board', [
            'board' => $board,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'problem'       => 'required|string|max:1000',
            'benefit'       => 'required|string|max:1000',
            'budget'        => 'required|numeric|min:0',
            'purchase_link' => 'nullable|url|max:2048',
        ]);

        $validated['status'] = 'wishlist';

        ShoppingItem::create($validated);

        return Redirect::route('board')->with('success', 'Item added to Wishlist.');
    }

    public function update(Request $request, ShoppingItem $shoppingItem)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'problem'       => 'required|string|max:1000',
            'benefit'       => 'required|string|max:1000',
            'budget'        => 'required|numeric|min:0',
            'purchase_link' => 'nullable|url|max:2048',
        ]);

        $shoppingItem->update($validated);

        return Redirect::route('board')->with('success', 'Item updated.');
    }

    public function updateStatus(Request $request, ShoppingItem $shoppingItem)
    {
        $request->validate([
            'status' => 'required|in:wishlist,ready,completed',
        ]);

        $shoppingItem->update(['status' => $request->status]);

        return back();
    }

    public function destroy(ShoppingItem $shoppingItem)
    {
        $shoppingItem->delete();

        return Redirect::route('board')->with('success', 'Item deleted.');
    }
}
