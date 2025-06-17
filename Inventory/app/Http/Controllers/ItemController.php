<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
{
    $items = Item::all();
    return view('items.index', compact('items'));
}

public function store(Request $request)
{
    $item = Item::create($request->all());
    return response()->json($item);
}

public function update(Request $request, Item $item)
{
    $item->update($request->all());
    return response()->json($item);
}

public function destroy(Item $item)
{
    $item->delete();
    return response()->json(['success' => true]);
}

}
