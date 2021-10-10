{{--{{dd(get_defined_vars())}}--}}
@php
    $columnClass = array();
    $tableId = 'table-'.uniqid();
    $rowLinkDataAttributes = null;
@endphp
@if($isSearchable)
    <input class="form-control mt-3 mb-3" id="searchInput" type="text" placeholder="Search..">
@endif
<div class="table-responsive-md">
    <table id="{{$tableId}}" class="table table-striped table-sm tableFit">
        @if(!empty($tableHeaderRow))
        <thead>
                <tr>
                    @if(!empty($tableRowLinks))
                        <th colspan="{{$tableRowLinks->count()}}" class="fit"></th>
                    @endif
                    @foreach($tableHeaderRow->tableHeaderColumns as $th)
                        @php($columnClass[] = $th->class)
                        <th class="{{$th->class}}">
                            {{$th->title}}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
        @foreach($tableRows as $tr)
            <?php $rowLinkDataAttributes = $tr->rowLinkDataAttributes;

            /*echo '<pre>';
            print_r($rowLinkDataAttributes);
            echo '</pre>';*/
            ?>

            <tr class="align-middle {{$tr->class ?? null}}">
                @if(!empty($tableRowLinks))
                    <td class="fit">
                        @foreach($tableRowLinks->sortBy('sortOrder') as $trl)
                                @if($trl->isShow)
                                    {{-- TO BE IMPLEMENTED --}}
                                @elseif($trl->isEdit && $tr->isEditable)
                                    <x-Html.Link.Edit
                                        linkStyle="{{$trl->linkStyle}}"
                                        linkClassAppend="{{$trl->class}}"
                                        route="{{!empty($trl->route) ? route($trl->route, $tr->id) : ''}}"
                                        :dataAttributes=$rowLinkDataAttributes
                                    />
                                @elseif($trl->isDelete && $tr->isDeletable)
                                    <x-Html.Link.Delete
                                        linkStyle="{{$trl->linkStyle}}"
                                        linkClassAppend="{{$trl->class}}"
                                        route="{{!empty($trl->route) ? route($trl->route, $tr->id) : ''}}"
                                        :dataAttributes=$rowLinkDataAttributes
                                    />
                                @else
                                    <x-Html.Link.Link
                                        linkStyle="{{$trl->linkStyle}}"
                                        linkClassAppend="{{$trl->class}}"
                                        route="{{!empty($trl->route) ? route($trl->route, $tr->id) : ''}}"
                                        icon="{{$trl->icon}}"
                                        :dataAttributes=$rowLinkDataAttributes
                                    />
                                @endif
                        @endforeach
                    </td>
                @endif
                @foreach($tr->tableColumns as $td)
                    <td class="{{$columnClass[$loop->index] ?? ''}}">
                        {!! $td  !!}
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($isSearchable)
    <script>
        $(document).ready(function(){
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#{{$tableId}} tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@endif

