<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'age'
    ];

    /**
     * @param array $data
     * @return array
     */
    public static function getData(array $data): array
    {
        return [
            'first_name' => $data['name']['first'],
            'last_name' => $data['name']['last'],
            'email' => $data['email'],
            'age' => $data['dob']['age']
        ];
    }
}
