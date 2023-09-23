<?php

namespace App\Http\Controllers\ClinicManagement;

use App\Exports\ItemsExport;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddQuantityRequest;
use App\Http\Requests\ItemExportRequest;
use App\Http\Requests\StoreItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\Quantity;
use App\Services\ItemService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function __construct(protected ItemService $itemService)
    {
    }


    public function index(Request $request)
    {
        $request->validate([
            'category_id' => ['exists:categories,id']
        ]);

        $result = $this->itemService->index(request()->get('query'), $request->category_id);
        $data = ItemResource::collection($result)->response()->getData();
        return ResponseHelper::success($data);
    }

    public function store(StoreItemRequest $request)
    {
        $data = $request->validated();
        $result = $this->itemService->store($data);
        if ($result == 'error') return ResponseHelper::error();

        return ResponseHelper::success($result);
    }

    public function addQuantity(Item $item, AddQuantityRequest $request)
    {
        $result = $this->itemService->addQuantity($item, $request->validated());
        return ResponseHelper::success($result);
    }

    public function decreaseItemQuantity(Quantity $quantity)
    {
        $this->itemService->decreaseItemQuantity($quantity);
        return ResponseHelper::success([]);
    }

    public function getItemByQuantity(Quantity $quantity)
    {
        $result = $this->itemService->getItemByQuantity($quantity);
        $data = ItemResource::make($result);
        return ResponseHelper::success($data, null);
    }

    public function export(ItemExportRequest $request)
    {
        $fileName = $request->file_name ? $request->file_name : 'itemsReport';

        Excel::store(
            new ItemsExport($request->category_id, $request->condition, $request->start_date, $request->end_date),
            $fileName . '.xlsx',
            'public',
            \Maatwebsite\Excel\Excel::XLSX
        );

        $path = 'storage/' . $fileName . '.xlsx';

        return ResponseHelper::success(asset($path));
    }

    public function deleteQuantity(Quantity $quantity)
    {
        $quantity->delete();
        return ResponseHelper::success(['data' => []]);
    }
}
