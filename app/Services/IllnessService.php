<?php

namespace App\Services;

use App\Models\Illness;

/**
 * Class IllnessService.
 */
class IllnessService
{

    public function index()
    {
        $illnesses = Illness::query()->get();
        return $illnesses;
    }

    public function store($data)
    {
        $illness = Illness::query()->create($data);
        return $illness;
    }

    public function update(Illness $illness, $data)
    {
        $illness->update($data);
        return $illness;
    }
}
