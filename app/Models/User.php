<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Returns the users name
     * @param int $userId
     * @return string
     */
    public static function getName(int $userId): string {
        return User::where('id', $userId)->first()->name;
    }

    /**
     * Food planner: Returns the users HouseholdId
     * @return int
     */
    public function getHouseholdId(): int {
        return DB::table('fp_household_member')
            ->where('user_id', '=', $this->id)
            ->value('household_id');
    }
}
