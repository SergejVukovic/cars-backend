<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\DestroyPostRequest;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Requests\Post\ViewAnyPostRequest;
use App\Http\Requests\Post\ViewPostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $posts = (new Post)
            ->with(['category', 'attributes', 'user', 'images']);
        if($request->get('category')) {
            $posts->where('category_id', $request->get('category'));
        }
        $perPage = $request->get('perPage') ?? 20;

        return response()->json($posts->paginate($perPage));
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
        $validated['slug'] = $this->makeSlugFromTitle($validated['title']);
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
        $post_id = $request->route('post');
        $post = (new Post)->with(['category', 'attributes', 'user', 'images']);
        if(is_numeric($post_id)) {
           return response()->json($post->find($post_id));
        }
        return response()->json($post->firstWhere('slug', $post_id));
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
        $post = (new Post)->with(['attributes', 'user', 'images', 'category'])
            ->find($request->route('post'));

        if(isset($validated['title'])) {
            $validated['slug'] = $this->makeSlugFromTitle($validated['title']);
        }

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
        $env = App::environment();
        $post = (new Post)->find($request->route('post'));
        $folder_path = "{$env}/{$post->id}";
        Storage::disk('s3')->deleteDirectory($folder_path);
        $post->delete();
        return response()->json($post);
    }

    /**
     * Helper method to generate post slugs
     *
     * @param $title
     * @return string
     */
    private function makeSlugFromTitle($title): string
    {
        $slug = Str::slug($title);

        $count = (new Post)->whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
