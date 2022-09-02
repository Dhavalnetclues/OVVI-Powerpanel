@if(isset($data['content']) && !empty($data['content']))

    @php
        $tem_aos = '';
        if(isset($data['content']['row_animation'])) {
        		$row_animation =  $data['content']['row_animation'];
            $tem_aos = "data-aos=".$data['content']['row_animation'];
        }
        $row_class = $data['content']['row_class'];
        $foi_sec = ['foi','how-to-make-a-complaint'];
    @endphp

    @if(((in_array(Request::segment(1),$foi_sec)) && Request::segment(2) == '') || Request::segment(2) == 'kydomain-introduction')
        <div class="inner-page-gap foi-sec pb-0">
    @endif

    @if(!empty($row_class))
        <div class="{{ $row_class }}" {{ $tem_aos }}>
    @endif

    @php 
        $col_width = isset($data['content']['val'][0]['column_row_width']) ? $data['content']['val'][0]['column_row_width'] : 'F';
    @endphp

    @if(Request::segment(1) == '' || $col_width == 'F')
        <div class="container">
    @endif

    @if(isset($data['content']['val']) && !empty($data['content']['val']))
        @foreach($data['content']['val'] as $key => $val)

            @php
                $col_row_aos = '';
                if(isset($val['col_row_animation'])) {
              		$row_animation =  $val['col_row_animation'];
                  $col_row_aos = "data-aos=".$row_animation;
                }
            @endphp
            
            @if(!empty($val['col_row_class']))
                <div class="{{ $val['col_row_class'] }}" {{ $col_row_aos }}>
            @endif
            
            @foreach($val['columns'] as $ckey => $cval)
                @php
                    $col_aos = '';
                    if(!empty($cval['animation'])) {
                        $col_aos = "data-aos=".$cval['animation'];
                    }
                @endphp
                <div class="{{ $cval['column_class'] }}" {{ $col_aos }}>
                    @if(isset($cval['elementObj']) && !empty($cval['elementObj']))
                    
                        @if(isset($cval['elementObj']['type']) && !empty($cval['elementObj']['type']))
                            @include('visualcomposer::frontview.builder-sections.element_templates',['eval' => $cval['elementObj'], 'ekey' => 0])
                        @else
                            @if(Request::segment(1) == 'team' && Request::segment(2) == '')
                                <div class="board-minute n-mt-lg-10 n-mb-lg-30 n-mv-40 container">
                                <h3 class="nqtitle n-pt-lg-20" data-aos="fade-up">Board of Directors Minutes</h3>
                                <div class="minute-wrap" data-aos="flip-up">
                                <ul class="mCcontentx">
                            @endif
                                        @foreach($cval['elementObj'] as $ekey => $eval)
                                            @include('visualcomposer::frontview.builder-sections.element_templates',['eval' => $eval,'ekey' => $ekey, 'totalElement' => count($cval['elementObj'])])
                                        @endforeach
                            @if(Request::segment(1) == 'team' && Request::segment(2) == '')
                                </ul>
                                </div>
                                </div>
                            @endif
                        @endif

                    @endif
                </div>
            @endforeach
            
            @if(!empty($val['col_row_class']))
                </div>
            @endif
        @endforeach
    @endif

    @if(Request::segment(1) == '' || $col_width == 'F')
        </div>
    @endif

    @if(!empty($row_class))
        </div>
    @endif

    @if(((in_array(Request::segment(1),$foi_sec)) && Request::segment(2) == '') || Request::segment(2) == 'kydomain-introduction')
        </div>
    @endif

@endif