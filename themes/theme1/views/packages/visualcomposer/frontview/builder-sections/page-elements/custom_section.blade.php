@if(isset($data['content']['layout']) && $data['content']['layout'] == 'slider' )

<div class="nqtitle-small text-uppercase n-fc-black-500 n-fw-800 n-pb-xl-30 n-pb-15">{{$data['content']['title']}}</div>
<ul class="nqul n-fs-20 n-fw-500 n-fc-black-500 n-lh-130">
    @foreach($data['content']['records'] as $customdata)

    <li><a href="{{$customdata['link']}}" title="{{$customdata['title']}}" class="n-ah-a-500">{{$customdata['title']}}</a></li>
    @endforeach

</ul>
@elseif(isset($data['content']['layout']) && $data['content']['layout'] == 'grid' )

<div class="-stitle">
    <div class="nqtitle n-fw-800 text-uppercase">{{$data['content']['title']}}</div>
    <div class="text-uppercase n-fs-18 n-fw-500 n-fc-black-500">Sector Regulation</div>
</div>

<ul class="nqul n-fs-20 n-fw-500 n-fc-black-500 n-lh-130 n-mh-xl-25">
    @foreach($data['content']['records'] as $customdata)
    <li><a class="n-ah-a-500" href="{{$customdata['link']}}"title="{{$customdata['title']}}">{{$customdata['title']}}</a></li>
@endforeach
</ul>

@elseif(isset($data['content']['layout']) && $data['content']['layout'] == 'list')
<div class="nqtitle-small text-uppercase n-fc-black-500 n-fw-800 n-pb-xl-30 n-pb-15">{{$data['content']['title']}}</div>
<ul class="nqul n-fs-20 n-fw-500 n-fc-black-500 n-lh-130">
    @foreach($data['content']['records'] as $customdata)

    <li><a href="{{$customdata['link']}}" title="{{$customdata['title']}}" class="n-ah-a-500">{{$customdata['title']}}</a></li>
    @endforeach

</ul>
@endif
