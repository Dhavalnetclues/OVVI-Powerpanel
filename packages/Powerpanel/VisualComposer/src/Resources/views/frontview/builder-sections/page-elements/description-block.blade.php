@if(isset($data['extclass']) && !empty($data['extclass']))
    <div class="{{ $data['extclass'] }}">
@endif
        {!! $data['content'] !!}
@if(isset($data['extclass']) && !empty($data['extclass']))
    </div>    
@endif
