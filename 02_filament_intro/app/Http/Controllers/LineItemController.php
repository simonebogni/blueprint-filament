<?php

namespace App\Http\Controllers;

use App\Http\Requests\LineItemStoreRequest;
use App\Http\Requests\LineItemUpdateRequest;
use App\Models\LineItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LineItemController extends Controller
{
    public function index(Request $request): View
    {
        $lineItems = LineItem::all();

        return view('lineItem.index', compact('lineItems'));
    }

    public function create(Request $request): View
    {
        return view('lineItem.create');
    }

    public function store(LineItemStoreRequest $request): RedirectResponse
    {
        $lineItem = LineItem::create($request->validated());

        $request->session()->flash('lineItem.id', $lineItem->id);

        return redirect()->route('lineItem.index');
    }

    public function show(Request $request, LineItem $lineItem): View
    {
        return view('lineItem.show', compact('lineItem'));
    }

    public function edit(Request $request, LineItem $lineItem): View
    {
        return view('lineItem.edit', compact('lineItem'));
    }

    public function update(LineItemUpdateRequest $request, LineItem $lineItem): RedirectResponse
    {
        $lineItem->update($request->validated());

        $request->session()->flash('lineItem.id', $lineItem->id);

        return redirect()->route('lineItem.index');
    }

    public function destroy(Request $request, LineItem $lineItem): RedirectResponse
    {
        $lineItem->delete();

        return redirect()->route('lineItem.index');
    }
}
