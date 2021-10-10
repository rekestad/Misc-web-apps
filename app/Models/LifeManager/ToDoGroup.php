<?php

namespace App\Models\LifeManager;

use App\View\Components\Html\Form\Element\SelectOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class ToDoGroup
 * @package App\Models\LifeManager
 */
class ToDoGroup extends Model
{
    use SoftDeletes;

    protected $table = 'lm_todo_groups';
    protected $fillable = [
        'group_name',
        'color_bg',
        'color_text',
        'sort_order',
        'user_id',
        'start_expanded'
    ];

    /**
     * returns all to do groups for the select component
     * @param $userId
     * @return Collection
     */
    public static function getAsSelectOptions($userId): Collection {
        return (
        SelectOption::convertResultSet(
            DB::select('
                    SELECT
                        G.id AS value,
                        G.group_name AS label
                    FROM
                        lm_todo_groups G
                    WHERE
                        G.deleted_at IS NULL AND
                        G.user_id = :userId
                    ORDER BY
                        G.group_name
                    ',
                array(
                    'userId' => $userId
                )
            )
        )
        );
    }

    /**
     * returns group name and number of items
     * @return string
     */
    public function getTitle(): string {
        return $this->group_name . ' (' . count($this->getItems()) . ')';
    }

    /**
     * returns all to do items connected to the group
     * @return array
     */
    public function getItems(): array {
        return DB::select('
                    SELECT
                        T.id,
                        T.item_name,
                        T.date_deadline,
                        T.is_urgent,
                        T.priority_order,
                        T.is_checked,
                        DATEDIFF(CURDATE(),T.created_at) AS age_in_days
                    FROM
                        lm_todo_items T
                    WHERE
                        T.group_id = :groupId AND
                        T.deleted_at IS NULL
                    ORDER BY
                        T.is_urgent DESC,
                        IF(T.priority_order IS NULL, 1, 0),
                        T.priority_order,
                        T.item_name
                ',
            array(
                'groupId' => $this->id
            ));
    }
}
