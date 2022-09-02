<div class="row">
    <div class="col-md-12 col-xs-12 animated fadeInUp">
        @php 
        if(isset($data['class'])){
        $class = $data['class'];
        }
        @endphp
        @if(!empty($data['alertsArr']) && count($data['alertsArr'])>0)
        <div class="alert_page {{ $class }}">
            @foreach($data['alertsArr'] as  $key => $alert)    
           
            @if($key == 1)
            <div class="alert_box high">
                <h2 class="title_alert">High</h2>
                <ul class="clearfix">
                    @foreach($alert as $alert_data)
                    <li><a title="" href="{{ $alert_data['url'] }}">{{ $alert_data['varTitle'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($key == 2)
            <div class="alert_box midium">
                <h2 class="title_alert">Medium</h2>
                <ul class="clearfix">
                    @foreach($alert as $alert_data)
                    <li><a title="" href="{{ $alert_data['url'] }}">{{ $alert_data['varTitle'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if($key == 3)
            <div class="alert_box low">
                <h2 class="title_alert">Low</h2>
                <ul class="clearfix">
                    @foreach($alert as $alert_data)
                    <li><a title="" href="{{ $alert_data['url'] }}">{{ $alert_data['varTitle'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif
            @endforeach
        </div>
        @else 
        @if(empty($PAGE_CONTENT))
        <h2 class="no_record">No Record Found.</h2>
        @endif
        @endif
    </div>
</div>