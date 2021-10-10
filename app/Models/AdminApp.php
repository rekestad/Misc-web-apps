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

    /*public static function getCurrentApp() {

        $appId = (
            (\Request::is('foodPlanner*') ? 2 : null) ??
            (\Request::is('lifeManager*') ? 3 : null) ??
            (\Request::is('singAlong*') ? 4 : null) ??
            1 // default app
        );

        return AdminApp::where('id',$appId)->first();
    }*/
}
