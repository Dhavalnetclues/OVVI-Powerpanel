@if(isset($data['content']) && !empty($data['content']))

    @php
        $row_animation =  $data['content']['row_animation'];
        $tem_aos = '';
        if(!empty($data['content']['row_animation'])) {
            $tem_aos = "data-aos=".$data['content']['row_animation'];
        }
        $row_class = $data['content']['row_class'];
    @endphp

    @if(!empty($row_class))
        <div class="{{ $row_class }}" {{ $tem_aos }}>
    @endif	

    @if(Request::segment(1) == '')
        <div class="container">
    @endif

    @if(isset($data['content']['val']) && !empty($data['content']['val']))
        @foreach($data['content']['val'] as $key => $val)
   
            @php
                $row_animation =  $val['col_row_animation'];
                $col_row_aos = '';
                if(!empty($val['col_row_animation'])) {
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
                            @foreach($cval['elementObj'] as $ekey => $eval)
                                @include('visualcomposer::frontview.builder-sections.element_templates',['eval' => $eval,'ekey' => $ekey, 'totalElement' => count($cval['elementObj'])])
                            @endforeach
                        @endif

                    @endif
                </div>
            @endforeach
            
            @if(!empty($val['col_row_class']))
                </div>
            @endif
        @endforeach
    @endif

    @if(Request::segment(1) == '')
        </div>
    @endif

    @if(!empty($row_class))
        </div>
    @endif

@endif