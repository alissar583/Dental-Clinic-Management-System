<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Facades\DB;

/**
 * Class ItemService.
 */
class ItemService
{

    public function index($query, $category_id)
    {
        $items = Item::query()
            ->where('name_' . app()->getLocale(), 'LIKE', '%' . $query . '%')
            ->with('quantities');

        if ($category_id)
            $items = $items->where('category_id', $category_id);

        $items = $items->paginate(20);
        return $items;
    }

    public function store($data)
    {
        try {
            DB::beginTransaction();
            $item = Item::query()->create([
                'name_ar' => $data['name_ar'],
                'name_en' => $data['name_en'],
                'minimum_quantity' => $data['minimum_quantity'],
                'price' => $data['price'],
                'note' => isset($data['note']),
                'category_id' => $data['category_id'],
            ]);
            $quantity = $item->quantities()->create([
                'exp_date' => $data['exp_date'],
                'quantity' => $data['quantity'],
            ]);
            $qr = (new QrService())->generate($quantity->id);
            DB::commit();
            return $qr;
        } catch (\Throwable $th) {
            DB::rollBack();
            return 'error';
        }
    }

    public function addQuantity($item, $data)
    {
        $quantity = $item->quantities()->create([
            'exp_date' => $data['exp_date'],
            'quantity' => $data['quantity'],
        ]);
        $qr = (new QrService())->generate($quantity->id);
        return $qr;
    }

    public function decreaseItemQuantity($quantity)
    {
        $quantity->quantity = $quantity->quantity - 1;
        $quantity->save();
        return true;
    }
    public function getItemByQuantity($quantity)
    {
        return $quantity->item;
    }
}
