<?php


namespace App\Services;


use App\Jobs\UserTask;
use Illuminate\Support\Facades\Cache;

class UserService
{


    /**
     * @param array $data
     * @return string
     */
    public function addedUsers(array $data): string
    {
        $uniqid = uniqid();
        UserTask::dispatch($data, $uniqid);
        return $uniqid;
    }


    /**
     * @param string $uniqid
     * @return mixed
     */
    public function getInfoUsers(string $uniqid): mixed
    {
        $cache = Cache::get('task_user_' . $uniqid);
        if ($cache)
            return $cache;
        return false;
    }
}
