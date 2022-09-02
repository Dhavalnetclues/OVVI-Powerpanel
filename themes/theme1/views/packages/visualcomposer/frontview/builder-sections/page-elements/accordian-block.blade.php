@if(isset($data['content']) && !empty($data['content']))                        
  <li class="-li">
      @php
        $collapsed = 'collapsed';
        $show = '';
      @endphp
      @if(isset($data['ekey']) && $data['ekey'] == 0)
        @php
          $collapsed = '';
          $show = 'show';
        @endphp
      @endif
      <a class="-tabs {{ $collapsed }}" data-toggle="collapse" href="#{{str_slug($data['title'])}}" aria-expanded="true" aria-controls="{{str_slug($data['title'])}}"> {!! $data['title'] !!}<span></span></a>
      <div id="{{str_slug($data['title'])}}" class="-info collapse {{ $show }}" aria-labelledby="headingOne" data-parent="#accordionExample">
        <div class="cms">  
          {!! $data['content'] !!}
        </div>
      </div>
  </li>
@endif