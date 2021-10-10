<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SongBook
 * @package App\Models\SingAlong
 */
class DevLog extends Model
{
    use SoftDeletes;

    protected $table = 'dev_log';
    protected $fillable = [
        'log_message'
    ];

    public static function log(string $logMessage) {
        DevLog::create([
            'log_message' => strip_tags($logMessage)
        ]);
    }
}
