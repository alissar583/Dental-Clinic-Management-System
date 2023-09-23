<?php

namespace App\Http\Controllers\ClinicManagement;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService){}


    public function index()
    {
        $result = $this->categoryService->index(request()->get('query'));
        $data = CategoryResource::collection($result)->response()->getData();
        return ResponseHelper::success($data, null);
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $this->categoryService->store($data);
        return ResponseHelper::success([], null);
    }

    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);
        return ResponseHelper::success([], null);
    }
    
}
