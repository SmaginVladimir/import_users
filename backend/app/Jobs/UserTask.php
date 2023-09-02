<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UserTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $users;
    private string $uniqid;
    public int $countCreateUser;
    public int $countUpdateUser;

    /**
     * UserTask constructor.
     * @param array $users
     * @param string $uniqid
     */
    public function __construct(array $users, string $uniqid)
    {
        $this->users = $users;
        $this->uniqid = $uniqid;
        $this->countCreateUser = 0;
        $this->countUpdateUser = 0;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $usersChunk = array_chunk($this->users, 800);
        for ($i = 0; $i < count($usersChunk); $i++) {
            $this->userSeparation($usersChunk[$i]);
            sleep(10);
        }
        $this->saveCache();
    }


    private function userSeparation($users)
    {
        for ($i = 0; $i < count($users); $i++) {
            $userData = User::getData($users[$i]);
            $user = User::where('first_name', $userData['first_name'])->where('last_name', $userData['last_name'])->first();
            if ($user) {
                $user->update([
                    'email' => $userData['email'],
                    'age' => $userData['age'],
                ]);
                $this->countUpdateUser++;
            } else {
                User::create($userData);
                $this->countCreateUser++;
            }
        }
    }

    private function saveCache()
    {
        $resultUsers = User::count();
        Cache::put('task_user_' . $this->uniqid, [
            'sum' => $resultUsers,
            'create' => $this->countCreateUser,
            'update' => $this->countUpdateUser
        ], now()->addMinutes(5));
    }
}
