@if(!empty($searchResults) && count($searchResults) > 0)
@foreach($searchResults as $result)
@php

if($result->moduleTitle == 'Contact Us Lead'){
$url = url('/powerpanel/'.$result->varModuleName.'?id='.$result->id);
}else if($result->moduleTitle == 'Feedback Lead'){
$url = url('/powerpanel/'.$result->varModuleName.'?id='.$result->id);
}else if($result->moduleTitle == 'Form Builder Lead'){
$url = url('/powerpanel/'.$result->varModuleName.'?id='.$result->id);
}else{
$url = url('/powerpanel/'.$result->varModuleName.'/'.$result->id.'/edit');
}
@endphp
<li>
    <a href="{{ $url }}" title="{{ $result->term.' ('.$result->moduleTitle.')' }}">
        {{ $result->term }} <span> ( {{ $result->moduleTitle }} ) </span>
    </a>
</li>
@endforeach
@endif