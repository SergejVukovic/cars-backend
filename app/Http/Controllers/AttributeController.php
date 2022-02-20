<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attribute\DestroyAttributeRequest;
use App\Http\Requests\Attribute\StoreAttributeRequest;
use App\Http\Requests\Attribute\UpdateAttributeRequest;
use App\Http\Requests\Attribute\ViewAnyAttributeRequest;
use App\Http\Requests\Attribute\ViewAttributeRequest;
use App\Models\Attribute;
use Illuminate\Http\JsonResponse;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ViewAnyAttributeRequest $request
     * @return JsonResponse
     */
    public function index(ViewAnyAttributeRequest $request): JsonResponse
    {
        $attributes = (new Attribute)->all();
        return response()->json($attributes);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAttributeRequest $request
     * @return JsonResponse
     */
    public function store(StoreAttributeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $attribute = (new Attribute)->create($validated);

        return response()->json($attribute);
    }


    /**
     * Display the specified resource.
     *
     * @param ViewAttributeRequest $request
     * @return JsonResponse
     */
    public function show(ViewAttributeRequest $request): JsonResponse
    {
        $attribute = (new Attribute)->find($request->route('attribute'));
        return response()->json($attribute);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAttributeRequest $request
     * @return JsonResponse
     */
    public function update(UpdateAttributeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $attribute = (new Attribute)->find($request->route('attribute'));
        $attribute->update($validated);

        return response()->json($attribute);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyAttributeRequest $request
     * @return JsonResponse
     */
    public function destroy(DestroyAttributeRequest $request): JsonResponse
    {
        $attribute = (new Attribute)->findOrFail($request->route('attribute'));
        $attribute->delete();
        return response()->json($attribute);
    }
}
