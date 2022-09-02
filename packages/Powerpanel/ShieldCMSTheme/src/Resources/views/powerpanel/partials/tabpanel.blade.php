@if(Config::get('Constant.DEFAULT_FAVORITE') == 'Y' || (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) || Config::get('Constant.DEFAULT_DRAFT') == 'Y' || Config::get('Constant.DEFAULT_TRASH') == 'Y')
<div class="pw_tabs">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#menu1" id="MenuItem1"><i class="icon-layers"></i>All</a></li>
        @if(in_array('favoriteTotalRecords',$tabarray) && $favoriteTotalRecords > 0)
        @if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y')
        <li><a data-toggle="tab" href="#menu5"  id="MenuItem5" value="F"><input type="hidden" id="HidMenuItem5" name="HidMenuItem5" value="F"><i class="ri-star-fill"></i>Favorite <span class='badge' id="span_MenuItem5">{{ $favoriteTotalRecords }}</span></a></li>
        @endif
        @endif
        @if($userIsAdmin && (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null))
        <li><a data-toggle="tab" href="#menu2"  id="MenuItem2" value="A"><input type="hidden" id="HidMenuItem2" name="HidMenuItem2" value="A"><i class="icon-user-following"></i>Waiting to be approved <span class="badge badge-light newcounter"></span></a></li>
        @endif
        @if(in_array('draftTotalRecords',$tabarray) && $draftTotalRecords > 0)
        @if (Config::get('Constant.DEFAULT_DRAFT') == 'Y')
        <li><a data-toggle="tab" href="#menu3"  id="MenuItem3" value="D"><input type="hidden" id="HidMenuItem3" name="HidMenuItem3" value="D"><i class="fa fa-file-o"></i>Draft <span class='badge'>{{ $draftTotalRecords }}</span></a></li>
        @endif
        @endif
        @if($userIsAdmin)
        @if(in_array('trashTotalRecords',$tabarray) && $trashTotalRecords > 0)
        @if (Config::get('Constant.DEFAULT_TRASH') == 'Y')
        <li><a data-toggle="tab" href="#menu4"  id="MenuItem4" value="T"><input type="hidden" id="HidMenuItem4" name="HidMenuItem4" value="T"><i class="fa fa-trash"></i>Trash <span class='badge' id="span_MenuItem4">{{ $trashTotalRecords }}</span></a></li>
        @endif
        @endif
        @endif
    </ul>
</div>
@endif