@if(!empty($searchResults) && count($searchResults) > 0)
@foreach($searchResults as $result)
@php
$url = url('/powerpanel/'.$result->varModuleName.'/');
@endphp
<div class="py-4">
	<h5 class="mb-2"><a href="{{ $url }}" target="_blank">{{ $result->term }}</a> @if(!empty($result->moduleTitle))<span>({{ ucfirst($result->moduleTitle) }})</span>@endif</h5>
	<p class="text-muted mb-0">{!! trim(substr(	strip_tags($result->info), 0, 275)); !!}</p>
</div>
<div class="border border-dashed"></div>
@endforeach
@endif