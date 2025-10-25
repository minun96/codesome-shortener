<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortCodeRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function index() {
        return Link::paginate(10);
    }

    public function show(Link $link) {
        return response()->json($link, 200);
    }

    public function store(StoreShortCodeRequest $request) {
        $data = $request->validated();
        $shortCode = $data['short_code'] ?? null;

        if (empty($shortCode)) {
            do {$shortCode = Str::random(7);} 
            while (Link::where('short_code', $shortCode)->exists()); // così se è vuoto ripete il ciclo
        }

        $link = Link::create([
            'long_url' => $data['long_url'],
            'short_code' => $shortCode,
        ]);

        return response()->json($link, 201);
    }

    public function update(UpdateLinkRequest $request, Link $link) {
        $data = $request->validated();
        $link->update($data);

        return response()->json($link, 200);
    }

}
