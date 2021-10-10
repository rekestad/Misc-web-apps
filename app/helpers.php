<?php

use Carbon\Carbon;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use \App\View\Components\Html\Form\Element\SelectOption;

function util_formatArrayAsOptionList($optionArray) {
    $optionHtml = null;

    for ($i = 0; $i < count($optionArray); $i++) {
        $optionHtml .= '<option value="' . $optionArray[$i][0] . '">' . $optionArray[$i][1] . '</option>' . PHP_EOL;
    }

    return $optionHtml;
}

function util_getWeekDayNames($isAbbreviations = false) {
    $result = null;

    if ($isAbbreviations == false) {
        $result = array(
            "", // so that [1] = Monday
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
            "Sunday"
        );
    } else {
        $result = array(
            "", // so that [1] = Monday
            "Mon",
            "Tue",
            "Wed",
            "Thu",
            "Fri",
            "Sat",
            "Sun"
        );
    }

    return $result;
}

function util_calendar($date = null) {
    // set $date to today if none specified
    $date = !empty($date) ? $date : Carbon::now()->format('Y-m-d');

    return DB::table('calendar_table')
        ->select(
            'date',
            'day_no',
            'month_no',
            'month_name',
            'day_name',
            'week_no',
            'day_of_week',
            'year'
        )
        ->where('date', $date)
        ->first();
}

function util_css_colorNames() {
    return array(
        'AliceBlue', 'AntiqueWhite', 'Aqua', 'Aquamarine', 'Azure', 'Beige', 'Bisque', 'Black', 'BlanchedAlmond', 'Blue', 'BlueViolet', 'Brown', 'BurlyWood', 'CadetBlue', 'Chartreuse', 'Chocolate', 'Coral', 'CornflowerBlue', 'Cornsilk', 'Crimson', 'Cyan', 'DarkBlue', 'DarkCyan', 'DarkGoldenRod', 'DarkGray', 'DarkGrey', 'DarkGreen', 'DarkKhaki', 'DarkMagenta', 'DarkOliveGreen', 'Darkorange', 'DarkOrchid', 'DarkRed', 'DarkSalmon', 'DarkSeaGreen', 'DarkSlateBlue', 'DarkSlateGray', 'DarkSlateGrey', 'DarkTurquoise', 'DarkViolet', 'DeepPink', 'DeepSkyBlue', 'DimGray', 'DimGrey', 'DodgerBlue', 'FireBrick', 'FloralWhite', 'ForestGreen', 'Fuchsia', 'Gainsboro', 'GhostWhite', 'Gold', 'GoldenRod', 'Gray', 'Grey', 'Green', 'GreenYellow', 'HoneyDew', 'HotPink', 'IndianRed', 'Indigo', 'Ivory', 'Khaki', 'Lavender', 'LavenderBlush', 'LawnGreen', 'LemonChiffon', 'LightBlue', 'LightCoral', 'LightCyan', 'LightGoldenRodYellow', 'LightGray', 'LightGrey', 'LightGreen', 'LightPink', 'LightSalmon', 'LightSeaGreen', 'LightSkyBlue', 'LightSlateGray', 'LightSlateGrey', 'LightSteelBlue', 'LightYellow', 'Lime', 'LimeGreen', 'Linen', 'Magenta', 'Maroon', 'MediumAquaMarine', 'MediumBlue', 'MediumOrchid', 'MediumPurple', 'MediumSeaGreen', 'MediumSlateBlue', 'MediumSpringGreen', 'MediumTurquoise', 'MediumVioletRed', 'MidnightBlue', 'MintCream', 'MistyRose', 'Moccasin', 'NavajoWhite', 'Navy', 'OldLace', 'Olive', 'OliveDrab', 'Orange', 'OrangeRed', 'Orchid', 'PaleGoldenRod', 'PaleGreen', 'PaleTurquoise', 'PaleVioletRed', 'PapayaWhip', 'PeachPuff', 'Peru', 'Pink', 'Plum', 'PowderBlue', 'Purple', 'Red', 'RosyBrown', 'RoyalBlue', 'SaddleBrown', 'Salmon', 'SandyBrown', 'SeaGreen', 'SeaShell', 'Sienna', 'Silver', 'SkyBlue', 'SlateBlue', 'SlateGray', 'SlateGrey', 'Snow', 'SpringGreen', 'SteelBlue', 'Tan', 'Teal', 'Thistle', 'Tomato', 'Turquoise', 'Violet', 'Wheat', 'White', 'WhiteSmoke', 'Yellow', 'YellowGreen'
    );
}

function util_css_colorNames_selectOptions(): Collection {
    $options = array();

    foreach(util_css_colorNames() as $cn) {
        $options[] = (object)['value' => $cn,'label' => $cn,'style'=>'background-color: '.$cn];
    };

    return SelectOption::convertResultSet($options);
}

function util_returnValueIfEmpty($value, $returnValue = null) {
    $value = strip_tags($value);
    return !empty($value) ? $value : $returnValue;
}

function util_standardPolicyResponse($user, $obj): Response {
    return $user->id === $obj->user_id
        ? Response::allow()
        : Response::deny('Unauthorized action');
}

function util_formatAsStyleTag($style): string {
    return (!empty($style) ? ' style="' . $style . '" ' : '');
}

function util_formatAsClassTag($class): string {
    return (!empty($class) ? ' class="' . $class . '" ' : '');
}

function util_mysqlDatetimeFormat($formatRequest): string {

    $formatReturn = null;

    switch ($formatRequest) {
        case "D/M HH:MM":
            $formatReturn = '%e/%c %H:%i';
            break;
        case "YY-MM-DD HH:MM":
            $formatReturn = '%y-%m-%d %H:%i';
            break;
        case "YY-M-D HH:MM":
            $formatReturn = '%y-%c-%e %H:%i';
            break;
        case "D/M-Y HH:MM":
            $formatReturn = '%e/%c-%y %H:%i';
            break;
        default:
            $formatReturn = '%Y-%m-%d %H:%i';
    }

    return $formatReturn;
}

function util_routeIsActive($currentRoute, $routeToMatch): bool {
    return (strtok($currentRoute, '.') === strtok($routeToMatch, '.'));
}

function util_createdUpdateTextMsg(bool $isNew): string {
    return ($isNew ? 'created' : 'updated');
}

function util_ajaxResponseStatus_success(): string {
    return 'success';
}

function util_ajaxResponseStatus_failed(): string {
    return 'failed';
}

function util_returnNullIfEmptyString(string $value): ?string {
    return !empty($value) ? $value : null;
}
