@php
    $segment1 = Request::segment(1);
    $segment2 = Request::segment(2);
@endphp

@if($segment1 == '')
    <div class="-banner-item" data-aos="flip-up">
        <div class="container">
            <div class="container-w">
                <div class="row">
                    <div class="col-12">
                        <div class="banner-tabs">

                            @if(isset($data['SectorMenuhtml']))
                                {!! $data['SectorMenuhtml'] !!}
                            @endif

                            @if(isset($data['childArr']) && !empty($data['childArr']))
                            <div class="tab-content" id="myTabContent">
                                @php $i = 0 @endphp
                                @foreach($data['childArr'] as $key => $val)

                                    @php
                                        $varTitle = ucfirst(str_replace(' ','_',$val['varTitle']));
                                        $activeClass = $i == 0 ? 'active' : '';
                                    @endphp

                                    <div class="tab-pane fade show {{$activeClass}}" id="{{$varTitle}}_tab" role="tabpanel" aria-labelledby="{{$varTitle}}-tab">
                                        <div class="link-list">
                                            <ul>
                                                @if(isset($val['children']) && !empty($val['children']))
                                                    @foreach($val['children'] as $c_key => $c_value)

                                                        @php
                                                            $varTitle = ucfirst(str_replace(' ','_',$c_value['varTitle']));
                                                            $varTitle = str_replace('&','and',$varTitle);

                                                            if($c_value['varTitle'] == 'Overview' || $c_value['varTitle'] == 'overview'){
                                                                $href = "#";
                                                            }elseif($c_value['varTitle'] == 'Policy & Regulation' || $c_value['varTitle'] == 'policy & regulation'){
                                                                $href = "#";
                                                            }elseif($c_value['varTitle'] == 'Apply for a new license' || $c_value['varTitle'] == 'Renew a license'){
                                                                $href = "mailto:info@ofreg.ky";
                                                            }else{
                                                                $href = url($c_value['txtPageUrl']);
                                                            }
                                                        @endphp

                                                        <li><a href="{{ $href }}" id="{{$varTitle}}_{{$c_value['txtPageUrl']}}">{{ $c_value['varTitle'] }} <i class="n-icon" data-icon="s-arrow-l"></i></a></li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    @php $i++ @endphp
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="-banner-item" data-aos="flip-up">
        <div class="container">
            <div class="container-w">
                <div class="row">
                    <div class="col-12">
                        <div class="banner-tabs">

                            @if(isset($data['SectorMenuhtml']))
                                {!! $data['SectorMenuhtml'] !!}
                            @endif

                            @if(isset($data['childArr']) && !empty($data['childArr']))
                            <div class="tab-content" id="myTabContent">
                                @php $i = 0 @endphp
                                @foreach($data['childArr'] as $key => $val)

                                    @php
                                        $varTitle = ucfirst(str_replace(' ','_',$val['varTitle']));
                                        $activeClass = $i == 0 ? 'active' : '';
                                    @endphp

                                    <div class="tab-pane fade show {{$activeClass}}" id="{{$varTitle}}_tab" role="tabpanel" aria-labelledby="{{$varTitle}}-tab">
                                        <div class="link-list">
                                            <ul>
                                                @if(isset($val['children']) && !empty($val['children']))
                                                    @foreach($val['children'] as $c_key => $c_value)

                                                        @php
                                                            $varTitle = ucfirst(str_replace(' ','_',$c_value['varTitle']));
                                                            $varTitle = str_replace('&','and',$varTitle);

                                                            if($c_value['varTitle'] == 'Overview' || $c_value['varTitle'] == 'overview'){
                                                                $href = "#";
                                                            }elseif($c_value['varTitle'] == 'Policy & Regulation' || $c_value['varTitle'] == 'policy & regulation'){
                                                                $href = "#";
                                                            }elseif($c_value['varTitle'] == 'Apply for a new license' || $c_value['varTitle'] == 'Renew a license'){
                                                                $href = "mailto:info@ofreg.ky";
                                                            }else{
                                                                $href = url($c_value['txtPageUrl']);
                                                            }
                                                        @endphp

                                                        <li><a href="{{ $href }}" id="{{$varTitle}}_{{$c_value['txtPageUrl']}}">{{ $c_value['varTitle'] }} <i class="n-icon" data-icon="s-arrow-l"></i></a></li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    @php $i++ @endphp
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    var segment1ForNavigationMenu = "{{ $segment1 }}";
    var segment2ForNavigationMenu = "{{ $segment2 }}";
</script>