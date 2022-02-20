<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\DestroyCategoryRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\Category\ViewAnyCategoryRequest;
use App\Http\Requests\Category\ViewCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

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
        $categories = (new Category)->all();
        return response()->json($categories);
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
        $category = (new Category)->find($request->route('category'));
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
}
