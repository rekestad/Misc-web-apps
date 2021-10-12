<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminApp extends Model
{
    protected $table = 'admin_apps';

    public function getNavGroups() {
        return AdminNavGroup::where('app_id',$this->id)
            ->where('is_active',1)
            ->orderBy('sort_order')
            ->get();
    }
}
