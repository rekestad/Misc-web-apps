<?php

namespace App\Http\Controllers\LifeManager;

use App\Http\Controllers\Controller;
use App\Models\LifeManager\ToDo;
use App\Models\LifeManager\ToDoGroup;
use App\Models\User;
use App\Models\Util;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class ToDoGroupController
 * @package App\Http\Controllers\LifeManager
 */
class ToDoGroupController extends Controller
{
    private ?User $user;

    public function __construct() {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });

        // policy
        $this->authorizeResource(ToDoGroup::class, 'toDoGroup');
    }

    /**
     * Show the form for creating a new to do group
     *
     * @return Application|Factory|View
     */
    public function create() {
        return $this->createEdit();
    }

    /**
     * show the form for creating/updating to do group
     * @param ToDoGroup|null $toDoGroup
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function createEdit(ToDoGroup $toDoGroup = null) {
        $isEdit = !empty($toDoGroup);

        $colorOptions = util_css_colorNames_selectOptions();

        $data = [
            'action' => ($isEdit
                ? route('toDoGroups.update', ($toDoGroup->id ?? null))
                : route('toDoGroups.store')),
            'isEdit' => $isEdit,
            'toDoGroup' => $toDoGroup,
            'colorOptions' => $colorOptions,
            'colorBgDefault' => $colorOptions->random()->value,
            'colorTxtDefault' => $colorOptions->random()->value
        ];

        return view('LifeManager.ar_todo_groups.createEdit')->with($data);
    }

    /**
     * Store a newly created to do group
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse {
        $this->validateAttributes($request);

        ToDoGroup::create([
            'group_name' => strip_tags($request->get('group_name')),
            'color_bg' => strip_tags($request->get('color_bg')),
            'color_text' => strip_tags($request->get('color_text')),
            'sort_order' => strip_tags($request->get('sort_order')),
            'start_expanded' => util_returnValueIfEmpty($request->get('start_expanded'), 0),
            'user_id' => $this->user->id
        ]);

        return redirect()->route('toDos.index')->with('success', 'New group added');
    }

    /**
     * validate attributes before insert/update
     * @param Request $request
     */
    public function validateAttributes(Request $request): void {
        $request->validate([
            'group_name' => 'required',
            'color_bg' => 'required',
            'color_text' => 'required',
            'sort_order' => 'required|integer'
        ]);
    }

    /**
     * Show the form for editing the specified to do group
     * @param ToDoGroup $toDoGroup
     * @return Application|Factory|View
     */
    public function edit(ToDoGroup $toDoGroup) {
        return $this->createEdit($toDoGroup);
    }

    /**
     * Update the specified to do group
     * @param Request $request
     * @param ToDoGroup $toDoGroup
     * @return RedirectResponse
     */
    public function update(Request $request, ToDoGroup $toDoGroup): RedirectResponse {
        $this->validateAttributes($request);

        $toDoGroup->update([
            'group_name' => strip_tags($request->get('group_name')),
            'color_bg' => strip_tags($request->get('color_bg')),
            'color_text' => strip_tags($request->get('color_text')),
            'sort_order' => strip_tags($request->get('sort_order')),
            'start_expanded' => util_returnValueIfEmpty($request->get('start_expanded'), 0)
        ]);

        return redirect()->route('toDos.index')
            ->with('success', 'Group has been updated')
            ->with('groupIdSelected', $toDoGroup->id);
    }

    /**
     * Remove the specified to do group
     * @param ToDoGroup $toDoGroup
     * @return RedirectResponse
     */
    public function destroy(ToDoGroup $toDoGroup): RedirectResponse {
        try {
            ToDo::where('group_id', $toDoGroup->id)->delete();
            $toDoGroup->delete();
        } catch (Exception $e) {
        }
        return redirect()->route('toDos.index')->with('success', 'Group has been deleted');
    }

    public function deleteChecked(ToDoGroup $toDoGroup): RedirectResponse {
        try {
            ToDo::where([
                'group_id' => $toDoGroup->id,
                'is_checked' => 1
            ])->delete();
        } catch (Exception $e) {
        }

        return redirect()->route('toDos.index', $toDoGroup->id)
            ->with('success', 'Checked items for "' . $toDoGroup->group_name . '" has been cleared');
    }

    /**
     * returns index route with success or error message
     * @param ToDoGroup $toDoGroup
     * @param string $message
     * @param bool $isError
     * @return RedirectResponse
     */
    public function returnToIndex(ToDoGroup $toDoGroup, string $message, bool $isError = false): RedirectResponse {
        return Util::returnToIndexRoute(route('toDos.index', $toDoGroup->id), $message, $isError);
    }
}
