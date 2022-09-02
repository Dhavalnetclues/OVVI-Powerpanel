
@php
    $avoidModules = ['popup','formbuilder','page_template','roles','contact-info'];
    $avoidModulesForLogHistory =['contact-info'];
    $currentUserID = auth()->user()->id;
@endphp

<div class="dropdown">
    <a href="javascript:void(0)" role="button" id="dropdownMenuLink{{ $value->id }}" data-bs-toggle="dropdown" aria-expanded="false" class=""><i class="ri-more-fill"></i></a>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $value->id }}">


        {{-- Edit --}}
        @if ($canedit)
            <li>
                <a class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="left" title="Edit"  href="{{ $module_edit_url }}?tab=P">
                    <i class="ri-pencil-line"></i>&nbsp;&nbsp;Edit
                </a>
            </li>
        @endif



        {{-- Trash --}}
        @if($tabName == "Trash")
            @if ( ($candelete || (isset($chrIsAdmin) && $chrIsAdmin == 'Y')) && (Config::get('Constant.DEFAULT_TRASH') == 'Y'))
                @if($module_type == 'category')
                    @if($hasRecords < 1)
                        <li><a data-bs-toggle="tooltip" data-bs-placement="left" title="Trash" class="dropdown-item red delete" data-controller="{{ $module_name }}" data-alias="{{ $value->id }}" data-tab="T"> <i class="ri-delete-bin-line"></i>&nbsp;&nbsp;Delete</a></li>
                    @endif
                @else
                    <li><a data-bs-toggle="tooltip" data-bs-placement="left" title="Trash" class="dropdown-item red delete" data-controller="{{ $module_name }}" data-alias="{{ $value->id }}" data-tab="T"> <i class="ri-delete-bin-line"></i>&nbsp;&nbsp;Delete</a></li>
                @endif
            @endif
        @else
            @if ( ($candelete || (isset($chrIsAdmin) && $chrIsAdmin == 'Y')) && (Config::get('Constant.DEFAULT_TRASH') == 'Y'))
                @if($module_type == 'category')
                    @if($hasRecords < 1)
                        <li><a data-bs-toggle="tooltip" data-bs-placement="left" title="Trash"  href="javascript:void(0)" onclick="Trashfun('{{ $value->id }}')" class="dropdown-item red" data-tab="P">
                            <i class="ri-delete-bin-line"></i>&nbsp;&nbsp;Trash
                        </a></li>
                    @endif
                @else
                    <li><a data-bs-toggle="tooltip" data-bs-placement="left" title="Trash"  href="javascript:void(0)" onclick="Trashfun('{{ $value->id }}')" class="dropdown-item red" data-tab="P">
                        <i class="ri-delete-bin-line"></i>&nbsp;&nbsp;Trash
                    </a></li>
                @endif
            @endif
        @endif



        {{-- Preview & View --}}
        @if(!in_array($module_name,$avoidModules))
            @if( ($module_type != 'category') && (isset($value->alias) && $value->alias != null) && ($tabName != 'Draft' && $tabName != 'Trash') && ($value->chrLock != 'Y') && (isset($chrIsAdmin) && $chrIsAdmin == 'Y'))
                <li>
                    <a class="dropdown-item without_bg_icon"  href="{{ $viewlink }}"  target="_blank" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $linkviewLable }}" >
                        <i class="ri-eye-line"></i>&nbsp;&nbsp;{{ $linkviewLable }}
                    </a>
                </li>
            @endif
        @endif



        {{-- Duplicate --}}
        @if(!in_array($module_name,$avoidModules))
            @if(($tabName != 'Draft' && $tabName != 'Trash') && ($value->chrLock != 'Y') && (isset($chrIsAdmin) && $chrIsAdmin == 'Y') && (Config::get('Constant.DEFAULT_DUPLICATE') == 'Y'))
                @if (Config::get('Constant.DEFAULT_DUPLICATE') == 'Y')
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="left" title="Duplicate" class='copy-grid dropdown-item' href="javascript:void(0)" onclick="GetCopyPage('{{ $value->id }}')">
                            <i class="ri-file-copy-line"></i>&nbsp;&nbsp;Duplicate
                        </a>
                    </li>
                @endif
            @endif
        @endif



        {{--  Log History , Locked-UnLock --}}
        @if(!in_array($module_name,$avoidModulesForLogHistory))
            @if ($value->chrLock != 'Y')
                @if($tabName != 'Trash')
                    @if (isset($chrIsAdmin) && $chrIsAdmin == 'Y')
                        @if ($canloglist)
                            <li><a data-bs-toggle="tooltip" data-bs-placement="left" title="Log History" class='log-grid dropdown-item' href="{{ $logurl }}"><i class="ri-time-line"></i>&nbsp;&nbsp;Log History</a></li>
                        @endif
                    @else
                        @if ($canloglist)
                            <li><a data-bs-toggle="tooltip" data-bs-placement="left" title="Log History" class='log-grid dropdown-item' href="{{ $logurl }}"><i class="ri-time-line"></i>&nbsp;&nbsp;Log History</a></li>
                        @endif
                    @endif
                @endif
            @else
                @if ($currentUserID != $value->LockUserID)
                    @php $lockedUserData = null @endphp
                    @php $lockedUserName = 'someone' @endphp
                    @if (!empty($lockedUserData))
                        @php $lockedUserName = $lockedUserData->name @endphp
                    @endif
                    @if (isset($chrIsAdmin) && $chrIsAdmin == 'Y')
                        <li><a href="javascript:void(0)" class="star_lock dropdown-item" onclick="GetUnLockData('{{ $value->id,$currentUserID }}' ,'{{ Config::get('Constant.MODULE.ID') }}',1)" data-bs-toggle="tooltip" data-bs-placement="left" title="This record has been locked by ' . {{ $lockedUserName }} . ', Click here to unlock.">
                            <i class="ri-lock-2-line"> </i>&nbsp;&nbsp;UnLock
                        </a></li>
                    @else
                        <li><a href="javascript:void(0)" class="star_lock dropdown-item" data-bs-toggle="tooltip" data-bs-placement="left" title="This record has been locked by ' . {{ $lockedUserName }} . '.">
                            <i class="ri-lock-2-line"></i>&nbsp;&nbsp;Locked
                        </a></li>
                    @endif
                @else
                    <li><a href="javascript:void(0)" class="star_lock dropdown-item" onclick="GetUnLockData('{{ $value->id }}','{{ $currentUserID }}','{{ Config::get('Constant.MODULE.ID') }}',1)" data-bs-toggle="tooltip" data-bs-placement="left" title="Click here to unlock.">
                        <i class="ri-lock-2-line"></i>&nbsp;&nbsp;UnLock
                    </a></li>
                @endif
            @endif
        @endif



        {{--  Rollback --}}
        {{-- @if($tabName != 'Draft' && $tabName != 'Trash')
            @if (File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php'))
                @if ($chrIsAdmin == 'Y' && count($value->child) > 1)
                    <li><a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title='Rollback to previous version' onclick="rollbackToPreviousVersion('{{ $value->id }}')"
                        class="log-grid dropdown-item"><i class="ri-history-line"></i>Rollback</a>
                    </li>
                @endif
            @endif
        @endif --}}



        {{-- Restore --}}
        @if(($tabName == 'Draft' || $tabName == 'Trash') && ($value->chrLock != 'Y') && ($chrIsAdmin == 'Y') && (Config::get('Constant.DEFAULT_TRASH') == 'Y'))
            <li><a data-bs-toggle="tooltip" data-bs-placement="left" title="Restore" href='javascript:void(0)' onclick='Restorefun("{{ $value->id }}","T")' class="dropdown-item">
                <i class="ri-repeat-line"></i>&nbsp;&nbsp;Restore
            </a></li>
        @endif


    <ul>
</div>