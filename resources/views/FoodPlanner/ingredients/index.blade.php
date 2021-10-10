@php
    use App\View\Components\Html\Table\TableHeaderRow;
    use App\View\Components\Html\Table\TableHeaderColumn;
    use App\View\Components\Html\Table\TableRowLink;

    $tableHeaderRow = new TableHeaderRow(
        collect([
            new TableHeaderColumn('Name','w-80'),
            new TableHeaderColumn('Unit','w-10 text-nowrap'),
            new TableHeaderColumn('Category','w-10')
        ]),
        null // class
    );

    $tableRowLinks = collect([
        new TableRowLink('ingredients.edit', 1, false, true)
    ]);
@endphp
@extends('layouts.app')
@section('content')
    <x-Html.Table.Table
        :tableHeaderRow=$tableHeaderRow
        :tableRows=$tableRows
        :tableRowLinks=$tableRowLinks
        :isSearchable=true
    />
@endsection
