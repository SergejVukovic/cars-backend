<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostImage\DestroyPostImageRequest;
use App\Http\Requests\PostImage\StorePostImageRequest;
use App\Http\Requests\PostImage\UpdatePostImageRequest;
use App\Http\Requests\PostImage\ViewAnyPostImageRequest;
use App\Http\Requests\PostImage\ViewPostImageRequest;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PostImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ViewAnyPostImageRequest $request
     * @return JsonResponse
     */
    public function index(ViewAnyPostImageRequest $request): JsonResponse
    {
        $post = (new Post)->find($request->route('post'));
        return response()->json($post->images);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StorePostImageRequest $request
     * @return JsonResponse
     */
    public function store(StorePostImageRequest $request): JsonResponse
    {
        $post_id = $request->route('post');
        $post = (new Post)->find($post_id);

        $env = App::environment();
        $folder_path = "{$env}/{$post_id}/images";

        if (!Storage::disk('s3')->exists($folder_path)) {
            Storage::disk('s3')->makeDirectory($folder_path);
        }

        $images = $request->file('images');
        $stored_images = [];

        foreach ($images as $image) {
            $image_name = Str::orderedUuid();
            $image_name_with_extension = "{$image_name}.webp";

            $main_image = Image::make($image->path())->resize(500,500)->encode('webp');


            $compressedMainImageStream = $main_image->stream();
            $compressedProductListingImage = $main_image->resize(250,250)->stream();

            Storage::disk('s3')->put("$folder_path/$image_name_with_extension", $compressedMainImageStream);
            Storage::disk('s3')->put("$folder_path/{$image_name}_250x250.webp", $compressedProductListingImage);

            $stored_images[] = [
                "image_path" => "{$folder_path}/{$image_name_with_extension}",
                "image_url" => env("AWS_CLOUDFRONT") . "$folder_path/$image_name_with_extension",
                "post_id" => $post_id,
                "created_at" => now(),
                "updated_at" => now()
            ];

        }

        (new PostImage)->insert($stored_images);
        $post->refresh();

        return response()->json($post->images);
    }


    /**
     * Display the specified resource.
     *
     * @param ViewPostImageRequest $request
     * @return JsonResponse
     */
    public function show(ViewPostImageRequest $request): JsonResponse
    {
        $image = (new PostImage)->find($request->route('image'));
        return response()->json($image);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePostImageRequest $request
     * @return JsonResponse
     */
    public function update(UpdatePostImageRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $image = (new PostImage)->find($request->route('image'));
        $image->update($validated);

        return response()->json($image);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPostImageRequest $request
     * @return JsonResponse
     */
    public function destroy(DestroyPostImageRequest $request): JsonResponse
    {
        $image = (new PostImage)->find($request->route('image'));
        Storage::disk('s3')->delete($image->image_path);
        Storage::disk('s3')->delete(str_replace('.webp', '_250x250.webp',$image->image_path));
        $image->delete();

        return response()->json($image);
    }
}
