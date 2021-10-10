<?php

namespace App\Http\Controllers\SingAlong;

use App\Http\Controllers\Controller;
use App\Models\SingAlong\SongBook;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Class PublicSongBookController
 * @package App\Http\Controllers\SingAlong
 */
class PublicSongBookController extends Controller
{
    /**
     * show public(!) song book
     * @param $songBookUrl
     * @return Application|Factory|View
     */
    public function show($songBookUrl) {
        $songBook = SongBook::where('url_suffix', $songBookUrl)->first();

        if (empty($songBook)) {
            abort(404);
        }

        $data = [
            'songBook' => $songBook,
            'songBookUrl' => $songBookUrl,
            'title' => $songBook->song_book_title,
            'songs' => $songBook->songs()->orderBy('pivot_sort_order')->get()
        ];

        return view('SingAlong.songBooks.showPublic')->with($data);
    }

    /**
     * show chords for public(!) song
     * @param $songBookUrl
     * @param $songNo
     * @return Application|Factory|View
     */
    public function showChords($songBookUrl, $songNo) {
        $songBook = SongBook::where('url_suffix', $songBookUrl)->first();

        if (empty($songBook)) {
            abort(404);
        }

        $song = $songBook->songs()->where('sort_order', $songNo)->first();

        if (empty($song)) {
            abort(404);
        }

        $data = $song->getChordData();

        if (empty($data)) {
            abort(404);
        }

        return view('SingAlong.songs.showChords')->with($data);
    }
}
