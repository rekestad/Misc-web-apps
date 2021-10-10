<?php

namespace App\Http\Controllers\LifeManager;

use App\Http\Controllers\Controller;
use App\Models\LifeManager\ToDo;
use App\Models\LifeManager\ToDoGroup;
use App\Models\User;
use App\Models\Util;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class ToDoController
 * @package App\Http\Controllers\LifeManager
 */
class ToDoController extends Controller
{
    private ?User $user;

    public function __construct() {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });

        // policy
        $this->authorizeResource(ToDo::class, 'toDo');
    }

    /**
     * Display a listing of to do items
     *
     * @param ToDoGroup $toDoGroup
     * @return Application|Factory|View
     */
    public function index(ToDoGroup $toDoGroup) {
        $toDoGroups = ToDoGroup::where('user_id', $this->user->id)->get();

        $data = [
            'title' => 'To Do',
            'groupIdSelected' => $toDoGroup->id,
            'buttonCreate' => [
                'route' => 'toDoGroups.create',
                'title' => 'Add group'
            ],
        ];

        return view('LifeManager.ar_todos.index', compact('toDoGroups'))->with($data);
    }

    /**
     * Show the form for creating a new to do item
     *
     * @param ToDoGroup $toDoGroup
     * @return Application|Factory|View
     */
    public function create(ToDoGroup $toDoGroup) {
        return $this->createEdit(null, $toDoGroup);
    }

    /**
     * show the form for creating/editing to do item
     * @param ToDo|null $toDo
     * @param ToDoGroup|null $toDoGroup
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function createEdit(ToDo $toDo = null, ToDoGroup $toDoGroup = null) {
        $isEdit = !empty($toDo);

        $data = [
            'action' => (
            $isEdit
                ? route('toDos.update', ($toDo->id ?? null))
                : route('toDos.store')
            ),
            'isEdit' => $isEdit,
            'toDo' => $toDo,
            'toDoGroupOptions' => ToDoGroup::getAsSelectOptions($this->user->id),
            'groupIdSelected' => $toDo->group_id ?? $toDoGroup->id
        ];

        return view('LifeManager.ar_todos.createEdit')->with($data);
    }

    /**
     * Show the form for editing a to do item
     *
     * @param ToDo $toDo
     * @return Application|Factory|View
     */
    public function edit(ToDo $toDo) {
        return $this->createEdit($toDo);
    }

    /**
     * Store a newly created to do item
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse {
        return $this->storeUpdate($request);
    }

    /**
     * store/update a to do item
     * @param Request $request
     * @param ToDo|null $toDo
     * @return RedirectResponse
     */
    public function storeUpdate(Request $request, ToDo $toDo = null): RedirectResponse {
        $isNew = empty($toDo);
        $this->validateAttributes($request);

        $groupId = strip_tags($request->get('group_id'));
        $itemName = strip_tags($request->get('item_name'));
        $priorityOrder = util_returnValueIfEmpty($request->get('priority_order'));
        $isUrgent = util_returnValueIfEmpty($request->get('is_urgent'), 0);
        $dateDeadline = util_returnValueIfEmpty($request->get('date_deadline'));

        if ($isNew) {
            ToDo::create([
                'item_name' => $itemName,
                'group_id' => $groupId,
                'priority_order' => $priorityOrder,
                'is_urgent' => $isUrgent,
                'date_deadline' => $dateDeadline,
                'user_id' => $this->user->id,
                'is_checked' => 0
            ]);
        } else {
            $toDo->update([
                'item_name' => $itemName,
                'group_id' => $groupId,
                'priority_order' => $priorityOrder,
                'is_urgent' => $isUrgent,
                'date_deadline' => $dateDeadline,
            ]);
        }

        return $this->returnToIndex('Item has been ' . util_createdUpdateTextMsg($isNew), $groupId);
    }

    /**
     * validate attributes before store/update
     * @param Request $request
     */
    public function validateAttributes(Request $request): void {
        $request->validate([
            'item_name' => 'required',
            'date_deadline' => 'nullable|date|after_or_equal:today'
        ]);
    }

    /**
     * return to index route wrapper function
     * @param string $message
     * @param int|null $groupIdExpanded
     * @param false $isError
     * @return RedirectResponse
     */
    public function returnToIndex(string $message, int $groupIdExpanded = null, bool $isError = false): RedirectResponse {
        return Util::returnToIndexRoute(route('toDos.index', $groupIdExpanded), $message, $isError);
    }

    /**
     * Update the specified to do item.
     * @param Request $request
     * @param ToDo $toDo
     * @return RedirectResponse
     */
    public function update(Request $request, ToDo $toDo): RedirectResponse {
        return $this->storeUpdate($request, $toDo);
    }

    /**
     * check/uncheck item (ajax request)
     * @param Request $request
     * @return JsonResponse
     */
    public function checkUncheck(Request $request): JsonResponse {
        $toDoId = strip_tags($request->get('id'));
        $setIsCheckedTo = (strip_tags($request->get('setIsCheckedTo')) == true ? 1 : 0);
        $isSuccess = true;
        $debugMsg = null;

        $toDo = ToDo::where('id', $toDoId)
            ->where('user_id', $this->user->id)
            ->first();

        if (!empty($toDo)) {
            $toDo->update(['is_checked' => $setIsCheckedTo]);
        } else {
            $isSuccess = false;
        }

        return response()->json([
            'isSuccess' => $isSuccess,
            'message' => $debugMsg ?? $toDo->id
        ]);
    }
}
