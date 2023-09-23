<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class ResponseHelper
{
    public static function success($data = [], $service = null, $message = 'success', $status = 200)
    {
        $response = array(
            'success' => true,
            'message' => $message,
            'data' => $data
        );

        if ($service == 1) {
            return $response;
        }
        if ($service == null || $service == false) {
            if ($data instanceof ResourceCollection || $data instanceof Collection) {
                $response['data'] =  response()->json(array('data' => $response['data']))->getData();
            }
        }
        return response()->json($response, $status);
    }


    public static function created($data = [], $message = 'created'): JsonResponse
    {
        return self::success($data, $message, null, 201);
    }

    public static function updated($data = [], $message = 'updated'): JsonResponse
    {
        return self::success($data, $message, null, 200);
    }

    public static function deleted($message = 'deleted'): JsonResponse
    {
        return self::success([], null, $message, 200);
    }

    public static function error($service = null, $message = 'error', $status = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($service == 1) {
            return $response;
        }

        return response()->json($response, $status);
    }

    public static function paginate($data)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;
        $currentItems = $data->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginator = new LengthAwarePaginator($currentItems, count($data), $perPage, $currentPage, ['path' => LengthAwarePaginator::resolveCurrentPath()]);

        return $paginator;
    }

    public static function convertDate($date, $time)
    {
        $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $time);
        $formattedDateTime = $dateTime->format('D M d Y H:i:s \G\M\TO (e:O)');
        return $formattedDateTime;
    }
}
