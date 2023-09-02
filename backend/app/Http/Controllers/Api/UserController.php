<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{

    protected UserService $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    /**
     * @return JsonResponse
     */
    public function addedUsers(): JsonResponse
    {
        $response = Http::get('https://randomuser.me/api/?results=5000');
        if ($response == 'Not Found')
            return response()->json(['message' => 'Not found'], 404);
        $userTask = $this->userService->addedUsers((array)$response['results']);
        return response()->json(['message' => 'Success', 'userTask' => $userTask]);
    }


    /**
     * @param string $uniqid
     * @return JsonResponse
     */
    public function infoUsers(string $uniqid): JsonResponse
    {
        return response()->json(['result' => $this->userService->getInfoUsers($uniqid)]);
    }
}
