<?php

namespace App\Http\Controllers\SingAlong;

use App\Http\Controllers\Controller;
use App\Models\SingAlong\Song;
use App\Models\User;
use App\Models\Util;
use App\View\Components\Html\Form\Element\SelectOption;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class SongController
 * @package App\Http\Controllers\SingAlong
 */
class SongController extends Controller
{
    private ?User $user;

    public function __construct() {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });

        $this->authorizeResource(Song::class, 'song');
    }

    /**
     * Display a list of all songs
     * @return Factory|View
     */
    public function index() {
        $songs = Song::where('user_id', $this->user->id)->get()->sortBy('song_title');

        $data = [
            'buttonCreate' => [
                'route' => 'songs.create',
                'title' => 'Add new'
            ],
            'title' => 'Songs (' . count($songs) . ')'
        ];

        return view('SingAlong.songs.index', compact('songs'))->with($data);
    }

    /**
     * Show the form for creating a new song
     * @return Application|Factory|View
     */
    public function create() {
        return $this->createEdit();
    }

    /**
     * show create/edit song form
     * @param Song|null $song
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function createEdit(Song $song = null) {
        $isEdit = !empty($song);

        $data = [
            'action' => ($isEdit ? route('songs.update', ($song->id ?? null)) : route('songs.store')),
            'isEdit' => $isEdit,
            'startingNotes' => SelectOption::convertResultSet(Song::getStartingNotes()),
            'capoFrets' => SelectOption::convertResultSet(Song::getCapoFrets()),
            'song' => $song,
            'chordColumnSeparator' => Song::getChordColumnSeparator()
        ];

        return view('SingAlong.songs.createEdit')->with($data);
    }

    /**
     * Store a newly created song
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse {
        return $this->storeUpdate($request);
    }

    /**
     * store/update song
     * @param Request $request
     * @param Song|null $song
     * @return RedirectResponse
     */
    public function storeUpdate(Request $request, Song $song = null): RedirectResponse {
        $this->validateAttributes($request);

        $isNew = empty($song);
        $songTitle = strip_tags($request->get('song_title'));
        $songComposer = util_returnNullIfEmptyString(strip_tags($request->get('song_composer')));
        $songLyrics = util_returnNullIfEmptyString(strip_tags($request->get('song_lyrics')));
        $songChords = util_returnNullIfEmptyString(strip_tags($request->get('song_chords')));
        $startingNote = util_returnNullIfEmptyString(strip_tags($request->get('starting_note')));
        $capoFretNo = util_returnNullIfEmptyString(strip_tags($request->get('capo_fret_no')));
        $userId = $this->user->id;

        if ($isNew) {
            Song::create([
                'song_title' => $songTitle,
                'song_composer' => $songComposer,
                'song_lyrics' => $songLyrics,
                'song_chords' => $songChords,
                'starting_note' => $startingNote,
                'capo_fret_no' => $capoFretNo,
                'user_id' => $userId
            ]);
        } else {
            $song->update([
                'song_title' => $songTitle,
                'song_composer' => $songComposer,
                'song_lyrics' => $songLyrics,
                'song_chords' => $songChords,
                'starting_note' => $startingNote,
                'capo_fret_no' => $capoFretNo
            ]);
        }

        return $this->returnToIndex('Song has been ' . util_createdUpdateTextMsg($isNew));
    }

    public function validateAttributes(Request $request): void {
        $request->validate([
            'song_title' => 'required'
        ]);
    }

    public function returnToIndex($message, $isError = false): RedirectResponse {
        return Util::returnToIndexRoute(route('songs.index'), $message, $isError);
    }

    /**
     * Display the chords for the specified song
     * @param Song $song
     * @return Application|Factory|View
     */
    public function showChords(Song $song) {
        $data = $song->getChordData();
        return view('SingAlong.songs.showChords')->with($data);
    }

    /**
     * Show the form for editing the specified song
     * @param Song $song
     * @return Application|Factory|View
     */
    public function edit(Song $song) {
        return $this->createEdit($song);
    }

    /**
     * Update the specified song
     * @param Request $request
     * @param Song $song
     * @return RedirectResponse
     */
    public function update(Request $request, Song $song): RedirectResponse {
        return $this->storeUpdate($request, $song);
    }

    /**
     * Remove the specified song
     * @param Song $song
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Song $song): RedirectResponse {
        try {
            $song->delete();
        } catch (Exception $e) {
            return $this->returnToIndex($e->getMessage(), true);
        }

        return $this->returnToIndex('Song has been deleted');
    }
}
