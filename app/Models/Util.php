<?php

namespace App\Models;

use Illuminate\Http\RedirectResponse;

/**
 * Class Util
 * @package App\Models
 */
class Util
{
    public static function returnToIndexRoute(string $indexRoute, string $message, bool $isError = false): RedirectResponse {
        $returnType = $isError ? 'error' : 'success';
        return redirect($indexRoute)->with($returnType, $message);
    }
}
