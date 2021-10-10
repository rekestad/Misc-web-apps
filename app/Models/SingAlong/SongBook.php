<?php

namespace App\Models\SingAlong;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class SongBook
 * @package App\Models\SingAlong
 */
class SongBook extends Model
{
    use SoftDeletes;

    protected $table = 'sa_song_books';
    protected $fillable = [
        'song_book_title',
        'song_book_description',
        'user_id',
        'url_suffix'
    ];

    /**
     * sync songs connected to song book
     * @param $songs
     */
    public function syncSongs($songs): void {
        $songsToSync = array();
        $sortOrder = 1; // to not have gaps in sort order

        foreach ($songs as $k => $v) {
            if (!empty($v['song_id'])) {
                $songsToSync[$v['song_id']] = array('sort_order' => $sortOrder);
                $sortOrder++;
            }
        }

        $this->songs()->sync($songsToSync);
    }

    /**
     * Eloquent ORM many-to-many relation to Song
     * @return BelongsToMany
     */
    public function songs(): BelongsToMany {
        return $this
            ->belongsToMany('App\Models\SingAlong\Song', 'sa_song_book_song')
            ->withPivot('sort_order')
            ->orderBy('sa_song_book_song.sort_order');
    }

    /**
     * returns all songs connected to song book
     * @return Collection
     */
    public function getSongs(): Collection {
        return (
        DB::table('sa_song_book_song AS SBS')
            ->select(
                'S.id',
                'SBS.sort_order',
                'S.song_title',
                'S.song_composer'
            )
            ->join('sa_songs AS S', 'S.id', '=', 'SBS.song_id')
            ->where('SBS.song_book_id', '=', $this->id)
            ->orderBy('SBS.sort_order')
            ->get()
        );
    }
}
