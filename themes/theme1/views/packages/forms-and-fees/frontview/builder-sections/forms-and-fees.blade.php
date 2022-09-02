@if(isset($data['formsandfees']) && !empty($data['formsandfees']) && count($data['formsandfees']) > 0)
        <ul class="nqul ac-collapse accordion" id="applicationforms">
            @foreach($data['formsandfees'] as $key =>$forms)
                @if($key == 0)
                    @php
                        $collapsed = '';
                        $expand = 'true';
                        $class = 'show';
                    @endphp
                @else
                    @php
                        $collapsed = 'collapsed';
                        $expand = 'false';
                        $class = '';
                    @endphp
                @endif
                <li class="-li">
                    
                    <a class="-tabs {{$collapsed}}" data-toggle="collapse" href="#collapse{{$forms->id}}" aria-expanded="{{$expand}}" aria-controls="collapse{{$forms->id}}">{{ $forms->varTitle}} <span></span></a>
                    <div id="collapse{{$forms->id}}" class="-info collapse {{$class}}" data-parent="#applicationforms">
                        {!! $forms->txtDescription !!}
                    </div>
                </li>
            @endforeach
        </ul>
@endif