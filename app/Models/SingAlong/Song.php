<?php

namespace App\Models\SingAlong;

use App\View\Components\Html\Form\Element\SelectOption;
use App\View\Components\Html\Form\Element\SelectOptionGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class Song
 * @package App\Models\SingAlong
 */
class Song extends Model
{
    use SoftDeletes;

    private static string $chordColumnSeparator = '###';

    protected $table = 'sa_songs';
    protected $fillable = [
        'song_title',
        'song_composer',
        'song_lyrics',
        'song_chords',
        'sheet_file_url',
        'starting_note',
        'capo_fret_no',
        'user_id'
    ];

    /**
     * capo fret options
     * @return object[]
     */
    public static function getCapoFrets(): array {
        return array(
            (object)['value' => '1st'],
            (object)['value' => '2nd'],
            (object)['value' => '3rd'],
            (object)['value' => '4th'],
            (object)['value' => '5th'],
            (object)['value' => '6th'],
            (object)['value' => '7th'],
            (object)['value' => '8th'],
            (object)['value' => '9th'],
            (object)['value' => '10th'],
            (object)['value' => '11th'],
            (object)['value' => '12th']
        );
    }

    /**
     * starting note options
     * @return object[]
     */
    public static function getStartingNotes(): array {
        return array(
            (object)['value' => 'Tonic'],
            (object)['value' => 'Second'],
            (object)['value' => 'Third'],
            (object)['value' => 'Fourth'],
            (object)['value' => 'Fifth'],
            (object)['value' => 'Sixth'],
            (object)['value' => 'Seventh']
        );
    }

    /**
     * @return string
     */
    public static function getChordColumnSeparator(): string {
        return self::$chordColumnSeparator;
    }
    /**
     * get songs for the Select option
     * @return Collection
     */
    public static function getAsSelectOptions($userId): Collection {
        return
            SelectOption::convertResultSet(DB::select('
            SELECT
                SO.value,
                SO.label,
                :defaultGroup AS optionGroup
            FROM
                view_songOptions SO
            WHERE
                SO.user_id = :userId
            ORDER BY
                SO.label
            ',
                array(
                    'defaultGroup' => SelectOptionGroup::$defaultGroup,
                    'userId' => $userId
                )
            ));
    }

    /**
     * prepare and return chord data for the song
     * @return array
     */
    public function getChordData(): array {
        $startingNote = $this->starting_note;
        $capoFretNo = $this->capo_fret_no;
        $columnSeparator = Song::$chordColumnSeparator;
        $title = (!empty($this->song_composer) ? $this->song_composer . ' - ' : '') . $this->song_title;

        // Split chord text into columns
        $cols = explode($columnSeparator, $this->song_chords);

        // Determine size of Boostrap column
        $colsCount = count($cols);
        $divSize = NULL;

        switch ($colsCount) {
            case 1:
                $divSize = '12';
                break;
            case 2:
                $divSize = '6';
                break;
            default:
                $divSize = '4';
                break;
        }

        // Return column array
        return compact('title', 'cols', 'divSize', 'startingNote', 'capoFretNo');
    }
}
