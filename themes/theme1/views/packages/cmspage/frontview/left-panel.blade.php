@if($sector == 'ict')
    @include('cmspage::frontview.left-panel.ict')
@elseif($sector == 'energy')
    @include('cmspage::frontview.left-panel.energy')
@elseif($sector == 'fuel')
    @include('cmspage::frontview.left-panel.fuel')
@elseif($sector == 'water')
    @include('cmspage::frontview.left-panel.water')
@elseif(isset($aboutUsMenuShow) && $aboutUsMenuShow == true)
    @include('cmspage::frontview.left-panel.about_us')
@elseif(Request::segment(1) == "consumer" 
        || Request::segment(1) == "how-to-make-a-complaint" 
        || Request::segment(1) == "complaints"
        || Request::segment(1) == "on-line-complaint-form")

    @include('cmspage::frontview.left-panel.consumer_info')

@endif
