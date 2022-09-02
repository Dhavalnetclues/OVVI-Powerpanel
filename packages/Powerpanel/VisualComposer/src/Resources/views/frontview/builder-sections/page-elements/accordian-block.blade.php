{{-- @if(isset($data['content']) && !empty($data['content']))                        
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
@endif --}}

@if(isset($data['content']) && !empty($data['content']))
  <div class="card">

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

    <div class="card-header">
        <a class="card-link collapsed {{ $collapsed }}" data-toggle="collapse" aria-expanded="true"  href="#{{str_slug($data['title'])}}" aria-controls="{{str_slug($data['title'])}}">
          {!! $data['title'] !!} <i class="n-icon fa fa-angle-down" data-icon="s-arrow-down"></i>
        </a>
    </div>

    <div id="{{str_slug($data['title'])}}" class="collapse {{ $show }}" aria-labelledby="headingOne" data-parent="#accordionExample">
        <div class="cms card-body">
            {!! $data['content'] !!}
        </div>
    </div>

  </div>
@endif