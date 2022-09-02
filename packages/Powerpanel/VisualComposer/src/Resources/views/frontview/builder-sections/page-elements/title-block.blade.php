{{-- <h2 class="{{ isset($data['extra_class'])?$data['extra_class']:'' }}">
    {{ $data['title'] }}
</h2> --}}
@if($data['headingtype'] == 'h1')
    <h1 class="{{ isset($data['extra_class'])?$data['extra_class']:'' }}">
        {{ $data['title'] }}
    </h1>
@elseif($data['headingtype'] == 'h2')
    <h2 class="{{ isset($data['extra_class'])?$data['extra_class']:'' }}">
        {{ $data['title'] }}
    </h2>
@elseif($data['headingtype'] == 'h3')
    <h3 class="{{ isset($data['extra_class'])?$data['extra_class']:'' }}">
        {{ $data['title'] }}
    </h3>
@elseif($data['headingtype'] == 'h4')
    <h4 class="{{ isset($data['extra_class'])?$data['extra_class']:'' }}">
        {{ $data['title'] }}
    </h4>
@elseif($data['headingtype'] == 'h5')
    <h5 class="{{ isset($data['extra_class'])?$data['extra_class']:'' }}">
        {{ $data['title'] }}
    </h5>
@elseif($data['headingtype'] == 'h6')
    <h6 class="{{ isset($data['extra_class'])?$data['extra_class']:'' }}">
        {{ $data['title'] }}
    </h6>
@elseif($data['headingtype'] == '')
    <div class="{{ isset($data['extra_class'])?$data['extra_class']:'' }}">
        {{ $data['title'] }}
    </div>
@endif