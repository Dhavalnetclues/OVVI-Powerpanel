@if (Config::get('Constant.DEFAULT_CONTENTLOCK') == 'Y')
@php
$link = $_SERVER["REQUEST_URI"];
$link_array = explode('/',$link);
$page = end($link_array);
if(isset($pagedata->id) && $pagedata->chrLock != 'Y'){
@endphp
<a title="Click here to lock." data-bs-toggle="tooltip" data-bs-placement="bottom" class="add_category add_lock_icon" onclick="GetLockData('<?php echo Request::segment(3); ?>', '<?php echo auth()->user()->id; ?>', '<?php echo Config::get('Constant.MODULE.ID'); ?>', '<?php echo Request::segment(2); ?>')" >
    <i class="ri-lock-unlock-line"></i>
</a>
@php
}else{
if(isset($pagedata->id) && $pagedata->chrLock == 'Y'){
if (auth()->user()->id != $pagedata->LockUserID) {
$lockedUserData = App\User::getRecordById($pagedata->LockUserID,true);
$lockedUserName = 'someone';
if(!empty($lockedUserData)){
$lockedUserName = $lockedUserData->name;
}
if($userIsAdmin){
@endphp
<a title="This record has been locked by {{ $lockedUserName }}, Click here to unlock." data-bs-toggle="tooltip" data-bs-placement="bottom" class="add_category add_lock_icon" onclick="GetUnLockData('<?php echo Request::segment(3); ?>', '<?php echo auth()->user()->id; ?>', '<?php echo Config::get('Constant.MODULE.ID'); ?>', '<?php echo Request::segment(2); ?>')" >
    <i class="ri-lock-2-line"></i>
</a>
@php
}else{
@endphp
@php

@endphp
<a title="This record has been locked by {{ $lockedUserName }}." data-bs-toggle="tooltip" data-bs-placement="bottom" class="add_category add_lock_icon" href='javascript:;' >
    <i class="ri-lock-2-line"></i>
</a>
@php
}
}else{
@endphp
<a title="Click here to unlock." class="add_category add_lock_icon" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="GetUnLockData('<?php echo Request::segment(3); ?>', '<?php echo auth()->user()->id; ?>', '<?php echo Config::get('Constant.MODULE.ID'); ?>', '<?php echo Request::segment(2); ?>')" >
    <i class="ri-lock-2-line"></i>
</a>
@php
}
}
}
@endphp
@endif