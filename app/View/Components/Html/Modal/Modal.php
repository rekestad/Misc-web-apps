<?php

namespace App\View\Components\Html\Modal;

use Illuminate\View\Component;

class Modal extends Component
{
    public bool $doUseHeader;
    public bool $doVerticallyCenter;
    public ?string $id;
    public ?string $title;

    public function __construct(
        bool $doUseHeader = true,
        bool $doVerticallyCenter = false,
        ?string $id = null,
        ?string $title = null
    ) {
        $this->doUseHeader = $doUseHeader;
        $this->id = $id ?? 'modal-'.uniqid();
        $this->title = $title;
        $this->doVerticallyCenter = $doVerticallyCenter;
    }

    public function render() {
        return view('Components.html.modal.modal');
    }
}
