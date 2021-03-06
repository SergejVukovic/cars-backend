<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\DestroyCategoryRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\Category\ViewAnyCategoryRequest;
use App\Http\Requests\Category\ViewCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ViewAnyCategoryRequest $request
     * @return JsonResponse
     */
    public function index(ViewAnyCategoryRequest $request): JsonResponse
    {
        if($request->get('parent')) {
            return response()->json((new Category())
                ->with('children')
                ->find($request->get('parent')));
        }

        if($request->get('grouped')) {
            return response()->json(
                (new Category())
                    ->with('children')
                    ->whereNull('parent_id')
                    ->get());
        }

        return response()->json((new Category())->whereNull('parent_id')->get());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $this->makeSlugFromTitle($validated['title']);
        $category = (new Category)->create($validated);

        return response()->json($category);
    }


    /**
     * Display the specified resource.
     *
     * @param ViewCategoryRequest $request
     * @return JsonResponse
     */
    public function show(ViewCategoryRequest $request): JsonResponse
    {
        $category = (new Category)->with(['parent', 'children'])->find($request->route('category'));
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCategoryRequest $request
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $category = (new Category)->find($request->route('category'));
        if(isset($validated['title'])) {
            $validated['slug'] = $this->makeSlugFromTitle($validated['title']);
        }
        $category->update($validated);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCategoryRequest $request
     * @return JsonResponse
     */
    public function destroy(DestroyCategoryRequest $request): JsonResponse
    {
        $category = (new Category)->findOrFail($request->route('category'));
        $category->delete();
        return response()->json($category);
    }

    /**
     * Helper method to generate post slugs
     *
     * @param $title
     * @return string
     */
    public function makeSlugFromTitle($title): string
    {
        $slug = Str::slug($title);

        $count = (new Category)->whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
