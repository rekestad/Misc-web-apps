<?php

namespace App\Http\Controllers\SingAlong;

use App\Http\Controllers\Controller;
use App\Models\SingAlong\Song;
use App\Models\SingAlong\SongBook;
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
 * Class SongBookController
 * @package App\Http\Controllers\SingAlong
 */
class SongBookController extends Controller
{
    private ?User $user;

    public function __construct() {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });

        $this->authorizeResource(SongBook::class, 'songBook');
    }

    /**
     * Display a list of all song books
     *
     * @return Factory|View
     */
    public function index() {
        $songBooks = SongBook::where('user_id', $this->user->id)->get();

        $data = [
            'buttonCreate' => [
                'route' => 'songBooks.create',
                'title' => 'Add new'
            ],
            'title' => 'Song books (' . count($songBooks) . ')',
        ];

        return view('SingAlong.songBooks.index', compact('songBooks'))->with($data);
    }

    /**
     * Show the form for creating a song book
     *
     * @return Application|Factory|View
     */
    public function create() {
        return $this->createEdit();
    }

    /**
     * show the form for creating/updating a song book
     * @param SongBook|null $songBook
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function createEdit(SongBook $songBook = null) {
        $isEdit = !empty($songBook);

        $data = [
            'action' => ($isEdit ? route('songBooks.update', ($songBook->id ?? null)) : route('songBooks.store')),
            'songOptions' => Song::getAsSelectOptions($this->user->id),
            'isEdit' => $isEdit,
            'songBook' => $songBook,
            'songBookSongs' => ($isEdit ? $songBook->getSongs() : null)
        ];

        return view('SingAlong.songBooks.createEdit')->with($data);
    }

    /**
     * Store a newly created song book
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse {
        return $this->storeUpdate($request);
    }

    /**
     * store/update song book
     * @param Request $request
     * @param SongBook|null $songBook
     * @return RedirectResponse
     */
    public function storeUpdate(Request $request, SongBook $songBook = null): RedirectResponse {
        $isNew = empty($songBook);

        $this->validateAttributes($request);

        if ($isNew) {
            $songBook = SongBook::create([
                'song_book_title' => strip_tags($request->get('song_book_title')),
                'song_book_description' => strip_tags($request->get('song_book_description')),
                'url_suffix' => strip_tags($request->get('url_suffix')),
                'user_id' => $this->user->id
            ]);
        } else {
            $songBook->update([
                'song_book_title' => strip_tags($request->get('song_book_title')),
                'song_book_description' => strip_tags($request->get('song_book_description')),
                'url_suffix' => strip_tags($request->get('url_suffix'))
            ]);
        }

        $songBook->syncSongs($request->get('song'));

        return $this->returnToIndex('Song book has been ' . util_createdUpdateTextMsg($isNew));
    }

    /**
     * validate attributes before store/update
     * @param Request $request
     */
    public function validateAttributes(Request $request): void {
        $request->validate([
            'song_book_title' => 'required',
            'url_suffix' => 'required'
        ]);
    }

    /**
     * wrapper for returning to index route
     * @param string $message
     * @param bool $isError
     * @return RedirectResponse
     */
    public function returnToIndex(string $message, bool $isError = false): RedirectResponse {
        return Util::returnToIndexRoute(route('songBooks.index'), $message, $isError);
    }

    /**
     * Display the specified song book
     * @param SongBook $songBook
     * @return Application|Factory|View
     */
    public function show(SongBook $songBook) {
        $data = [
            'songBook' => $songBook,
            'title' => $songBook->song_book_title,
            'songs' => $songBook->songs()->orderBy('pivot_sort_order')->get()
        ];

        return view('SingAlong.songBooks.show')->with($data);
    }

    /**
     * Show the form for editing the specified song book
     *
     * @param SongBook $songBook
     * @return Application|Factory|View
     */
    public function edit(SongBook $songBook) {
        return $this->createEdit($songBook);
    }

    /**
     * Update the specified song book
     *
     * @param Request $request
     * @param SongBook $songBook
     * @return RedirectResponse
     */
    public function update(Request $request, SongBook $songBook): RedirectResponse {
        return $this->storeUpdate($request, $songBook);
    }

    /**
     * Remove the specified song book
     *
     * @param SongBook $songBook
     * @return RedirectResponse
     */
    public function destroy(SongBook $songBook): RedirectResponse {
        try {
            $songBook->delete();
        } catch (Exception $e) {
            return $this->returnToIndex($e->getMessage(), true);
        }

        return $this->returnToIndex('Song book has been deleted');
    }
}
