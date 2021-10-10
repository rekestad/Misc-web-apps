<?php /** @noinspection PhpComposerExtensionStubsInspection */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class AdminNavItem extends Model
{
    protected $table = 'view_adminAppNavGroupNavItem';

    public function getBreadcrumbs() {
        $breadcrumbs =
            json_decode(DB::select('
                    SELECT
                        B.nav_item_breadcrumb
                    FROM
                        view_adminNavItemBreadcrumb B
                    WHERE
                        B.nav_item_id = :id
                ',
                array(
                    'id' => $this->nav_item_id
                )
            )[0]->nav_item_breadcrumb);

        return array_filter(
            $breadcrumbs,
            function($item) {
                return
                    !empty($item);
            }
        );
    }
}
