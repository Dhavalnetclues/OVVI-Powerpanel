@if($sector == 'ict')
    @include('cmspage::frontview..ict')
@elseif($sector == 'energy')
    @include('cmspage::frontview..energy')
@elseif($sector == 'fuel')
    @include('cmspage::frontview..fuel')
@elseif($sector == 'water')
    @include('cmspage::frontview..water')
@elseif(Request::segment(1) == "consumer" 
        || Request::segment(1) == "how-to-make-a-complaint" 
        || Request::segment(1) == "complaints"
        || Request::segment(1) == "on-line-complaint-form")

    @include('cmspage::frontview..consumer_info')
@else
    @include('cmspage::frontview..about_us')
@endif
