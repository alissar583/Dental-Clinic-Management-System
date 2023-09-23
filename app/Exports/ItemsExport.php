<?php

namespace App\Exports;

use App\Models\Item;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $category_id;
    protected $condition;
    protected $start_date;
    protected $end_date;

    public function __construct(
        $category_id,
        $condition,
        $start_date,
        $end_date
    ) {
        $this->category_id = $category_id;
        $this->condition = $condition;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $now = Carbon::now();

        $start = $now->startOfWeek(Carbon::SATURDAY)->format('Y-m-d');
        $end = $now->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');

        $result = Item::query()
            ->select([
                'c.name_' . app()->getLocale() . ' as categoryName',
                'items.name_' . app()->getLocale() . ' as itemName', 'items.price', 'items.minimum_quantity',
                'q.quantity', 'q.exp_date'
            ])
            ->join('categories as c', 'c.id', 'items.category_id')
            ->when($this->category_id, function ($q) {
                $q->where('c.id', $this->category_id);
            })
            ->join('quantities as q', 'q.item_id', 'items.id');

        switch ($this->condition) {
            case 'daily':
                $result = $result
                    ->whereDate('q.exp_date', now()->format('Y-m-d'));
                break;
            case 'weekly':
                $result = $result
                    ->whereBetween('q.exp_date', [$start,  $end]);
                break;
            case 'monthly':
                $result = $result
                    ->whereMonth('q.exp_date', $now->month);
                break;
            case 'yearly':
                $result = $result
                    ->whereYear('q.exp_date', $now->year);
                break;
        }

        if ($this->start_date && $this->end_date) {
            $result = $result
                ->whereBetween('q.exp_date', [$this->start_date,  $this->end_date]);
        } elseif ($start = $this->start_date) {
            $result = $result
                ->whereDate('q.exp_date', $start);
        }

        return $result->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Category Name", "Item Name", "Price", "Minimum Quantity", "Available Quantity", "Expired Date"];
    }
}
