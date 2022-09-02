{{-- Old Code --}}
{{-- <div class="cms n-mt-15"> 
    <a class="ac-btn ac-btn-primary ac-small n-mt-25" href="{{ $data['content'] }}"  target='{{ $data['target'] }}' title="{{ $data['title'] }}">{{ $data['title'] }}</a>
</div> --}}

{{-- New code --}}
<div class="cms n-mt-15"> 
    <a class="{{ isset($data['extclass']) ? ($data['extclass']) : "" }}" href="{{ $data['content'] }}"  target='{{ $data['target'] }}' title="{{ $data['title'] }}">{{ $data['title'] }}</a>
</div>