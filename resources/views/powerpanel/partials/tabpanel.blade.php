@if(Config::get('Constant.DEFAULT_FAVORITE') == 'Y' || (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) || Config::get('Constant.DEFAULT_DRAFT') == 'Y' || Config::get('Constant.DEFAULT_TRASH') == 'Y')
<!-- Nav tabs -->
<ul class="nav nav-tabs nav-tabs-custom border-0" role="tablist">


    {{-- All Tab --}}
    <li class="nav-item">
        <a class="nav-link active" href="#menu1" title="All" data-bs-toggle="tab" data-toggle="tab" role="tab" aria-selected="false" id="MenuItem1"><i class="ri-database-2-line fs-21"></i> </a>
    </li>


    {{-- Favorite Tab --}}
    @if(in_array('favoriteTotalRecords',$tabarray))
        @if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y')
        <li><a data-bs-toggle="tab" data-toggle="tab" href="#"  id="" value="F"></a></li>
        @endif
        <li class="nav-item">
            <a class="nav-link" href="#menu5" title="Favorite" data-bs-toggle="tab" data-toggle="tab" role="tab" aria-selected="false" value="F" id="MenuItem5">
                <input type="hidden" id="HidMenuItem5" name="HidMenuItem5" value="F">
                <i class="ri-bookmark-3-line fs-21"></i>
                @if(isset($favoriteTotalRecords) && $favoriteTotalRecords > 0)
                    <span class="pulse-danger"></span>
                @endif
            </a>
        </li>
    @endif


    {{-- Approval tab --}}
    @if(in_array('approvalTotalRecords',$tabarray))
        @if($userIsAdmin && (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" data-toggle="tab" title="Waiting to be approved" href="#menu2" role="tab" aria-selected="false" id="MenuItem2" value="A">
                    <input type="hidden" id="HidMenuItem2" name="HidMenuItem2" value="A">
                    <i class="ri-user-follow-line fs-21"></i>
                    @if(isset($approvalTotalRecords) && $approvalTotalRecords > 0)
                        <span class="pulse-danger"></span>
                    @endif
                </a>
            </li>
        @endif
    @endif


    {{-- Draft Tab --}}
    @if(in_array('draftTotalRecords',$tabarray))
        @if (Config::get('Constant.DEFAULT_DRAFT') == 'Y')
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-toggle="tab" title="Draft" href="#menu3" role="tab" aria-selected="false" id="MenuItem3" value="D">
                <input type="hidden" id="HidMenuItem3" name="HidMenuItem3" value="D">
                <i class="ri-file-text-line fs-21"></i>
                @if(isset($draftTotalRecords) && $draftTotalRecords > 0)
                    <span class="pulse-danger"></span>
                @endif
            </a>
        </li>
        @endif
    @endif


    {{-- Trash Tab --}}
    @if($userIsAdmin)
        @if(in_array('trashTotalRecords',$tabarray))
            @if (Config::get('Constant.DEFAULT_TRASH') == 'Y')
            <li class="nav-item">
                <a class="nav-link" title="Trash" data-bs-toggle="tab" data-toggle="tab" href="#menu4" role="tab" aria-selected="false" id="MenuItem4" value="T">
                    <input type="hidden" id="HidMenuItem4" name="HidMenuItem4" value="T">
                    <i class="ri-delete-bin-line fs-21"></i>
                    @if(isset($trashTotalRecords) && $trashTotalRecords > 0)
                        <span class="pulse-danger"></span>
                    @endif
                </a>
            </li>
            @endif
        @endif
    @endif
</ul>
@endif