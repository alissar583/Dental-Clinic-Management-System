<?php

namespace App\Services;

use App\Models\Category;

/**
 * Class CategoryService.
 */
class CategoryService
{
    public function store($data)
    {
        $clinicId = auth()->user()?->adminClinic->id;
        $category = Category::query()->create([
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'],
            'clinic_id' => $clinicId
        ]);
        return true;
    }

    public function index($query = null)
    {
        $categories = Category::query()
        ->where('name_'. app()->getLocale(), 'LIKE', '%'. $query . '%')
        ->with('items')
        ->paginate(10);
        return $categories;
    }

    public function delete($category)
    {
        $category->delete();
        return true;
    }
}
