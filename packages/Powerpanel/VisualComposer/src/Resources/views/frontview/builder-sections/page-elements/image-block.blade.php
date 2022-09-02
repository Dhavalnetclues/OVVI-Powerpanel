@php 
    $extraClass = (isset($data['extra_class'])?$data['extra_class']:'');
@endphp
<div class="thumbnail-container {{ $extraClass }}" data-thumb="{{ (isset($data['data_width']) && !empty($data['data_width'])?$data['data_width']:'66.66%') }}">
    <div class="thumbnail">
        <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
    </div>
</div>  