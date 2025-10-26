<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortCodeRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index() {
        return LinkResource::collection(Link::paginate(10));
    }

    public function show(Link $link) {
        return new LinkResource($link);
    }

    public function store(StoreShortCodeRequest $request) {
        $data = $request->validated();        
        $link = Link::createLink($data);

        return (new LinkResource($link))->response()->setStatusCode(201);
    }

    public function update(UpdateLinkRequest $request, Link $link) {
        $data = $request->validated();
        $link->update($data);

        return new LinkResource($link);
    }

    public function destroy(Link $link) {
        $link->delete();
        return response()->noContent();
    }

    public function restore(Link $link) {
        $link->restore();
        return new LinkResource($link);
    }

    public function trashed() {
        return LinkResource::collection(Link::onlyTrashed()->paginate(10));
    }

}
