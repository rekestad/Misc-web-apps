<?php

namespace App\View\Components\SingAlong\Songs;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class AccordionWrapper extends Component
{
    public Collection $songs;
    public bool $isPublicUser;
    public bool $doNumberSongs;
    public ?string $songBookUrl;

    public function __construct (
        Collection $songs,
        bool $isPublicUser,
        bool $doNumberSongs = false,
        ?string $songBookUrl = null
    )
    {
        $this->songs = $songs;
        $this->isPublicUser = $isPublicUser;
        $this->doNumberSongs = $doNumberSongs;
        $this->songBookUrl = $songBookUrl;
    }

    public function render()
    {
        return view('SingAlong.songs.components.accordionWrapper');
    }
}
