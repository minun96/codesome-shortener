<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortCodeRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Link;
use Illuminate\Http\Request;

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
        $link = Link::createLink($data);

        return response()->json($link, 201);
    }

    public function update(UpdateLinkRequest $request, Link $link) {
        $data = $request->validated();
        $link->update($data);

        return response()->json($link, 200);
    }

    public function destroy(Link $link) {
        $link->delete();
        return response()->noContent();
    }

    public function restore(Link $link) {
        $link->restore();
        return response()->json($link, 200);
    }

    public function trashed() {
        return Link::onlyTrashed()->paginate(10);
    }

}
