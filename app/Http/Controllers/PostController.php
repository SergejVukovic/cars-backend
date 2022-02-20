<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\DestroyPostRequest;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Requests\Post\ViewAnyPostRequest;
use App\Http\Requests\Post\ViewPostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ViewAnyPostRequest $request
     * @return JsonResponse
     */
    public function index(ViewAnyPostRequest $request): JsonResponse
    {
        //TODO Add filtering by title, attributes, category
        $posts = (new Post)
            ->with(['category', 'attributes', 'user'])
            ->get();
        return response()->json($posts);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StorePostRequest $request
     * @return JsonResponse
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $post = (new Post)->create($validated);

        if(isset($validated['attributes'])) {
            $post->attributes()->sync($validated['attributes']);
        }

        $post->attributes;

        return response()->json($post);
    }


    /**
     * Display the specified resource.
     *
     * @param ViewPostRequest $request
     * @return JsonResponse
     */
    public function show(ViewPostRequest $request): JsonResponse
    {
        $post = (new Post)->with(['category', 'attributes', 'user'])
            ->find($request->route('post'));
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePostRequest $request
     * @return JsonResponse
     */
    public function update(UpdatePostRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $post = (new Post)->with('attributes')->find($request->route('post'));
        $post->update($validated);

        if(isset($validated['attributes'])) {
            $post->attributes()->sync($validated['attributes']);
        }

        $post->refresh();

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPostRequest $request
     * @return JsonResponse
     */
    public function destroy(DestroyPostRequest $request): JsonResponse
    {
        $post = (new Post)->findOrFail($request->route('post'));
        $post->delete();
        return response()->json($post);
    }
}
