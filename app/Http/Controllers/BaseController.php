<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * Handle the response
     *
     * @param $data
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */

    public function handleResponse($data, $message, $code = 200)
        {
            return response()->json([
                'message' => $message,
                'code' => $code,
                'data' => $data,
                'meta' => [
                    'total' => $data->total(),
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem()
                ],
                'links' => [
                    'prev' => $data->previousPageUrl(),
                    'next' => $data->nextPageUrl(),
                    'first' => $data->url(1),
                    'last' => $data->url($data->lastPage())
                ]
                ], $code);
        }
    /**
     * Handle the error response
     *
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */

    public function handleResponseError($message, $code = 404)
    {
        return response()->json([
            'message' => $message,
            'code' => $code
        ], $code);
    }

    /**
     * Handle the success response
     *
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */

    public function handleSuccessResponse($message, $code = 200)
    {
        return response()->json([
            'message' => $message,
            'code' => $code
        ], $code);
    }

    /**
     * Handle the response without pagination
     *
     * @param $data
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleResponseNoPagination($data, $message, $code = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ], $code);
    }

    /**
     * Handle the response with pagination
     *
     * @param $data
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleError($message, $code = 404)
    {
        return response()->json([
            'message' => $message,
            'code' => $code
        ], $code);
    }
}





