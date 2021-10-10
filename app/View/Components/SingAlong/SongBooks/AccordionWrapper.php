<?php

namespace App\View\Components\SingAlong\SongBooks;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class AccordionWrapper extends Component
{
    public Collection $songBooks;

    public function __construct (
        $songBooks
    )
    {
        $this->songBooks = $songBooks;
    }

    public function render()
    {
        return view('SingAlong.songBooks.components.accordionWrapper');
    }
}
