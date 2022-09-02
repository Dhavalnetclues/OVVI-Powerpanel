@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif

@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
    {{-- Year Filter horizontal scrolling // START --}}
    <div class="n-mb-lg-30 n-mv-40 container">
        <h5 class="nqtitle">Sort by Years</h5>
        <div class="minute-wrap">
            <ul class="mCcontentx" id="yearFilter">
                @php
                    $yearsArr = array_combine(range(date("Y"), 2019), range(date("Y"), 2019));
                @endphp
                @php $i = "1"; @endphp
                @foreach($yearsArr as $key => $value)
                    @if($i == 1 || $i == 2)
                        @php $checked = "checked"; @endphp
                    @else
                        @php $checked = ""; @endphp
                    @endif
                    <li class="n-mt-30 n-mb-30">
                        <div class="form-group ac-form-group n-mb-0">
                            <div class="ac-checkbox-list n-pt-0">
                                <label class="ac-checkbox">
                                    <input type="checkbox" value="{{ $key }}" {{$checked}}> {{ $value }} <span></span>
                                </label>
                            </div>
                        </div>
                    </li>
                    @php $i++ @endphp
                @endforeach
            </ul>
        </div>
    </div>
    {{-- Year Filter horizontal scrolling // END --}}

    <section class="inner-page-gap news-page">
        <div class="container">
            <div class="row">
                <div class="inner-page-gap news-page">
                    <div class="">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-xl-4">
                                    <div class="date-val n-mb-10 n-mb-lg-20">
                                        @if(isset($letestNews->dtDateTime) && $letestNews->dtDateTime != '')
                                            {{ date('M',strtotime($letestNews->dtDateTime)) }} {{ date('d',strtotime($letestNews->dtDateTime)) }}, {{ date('Y',strtotime($letestNews->dtDateTime)) }}
                                        @endif
                                    </div>
                                    <h1 class="nqtitle-large n-mb-20"> {{ $letestNews->varTitle }}</h1>
                                    <div class="cms desc n-mb-30 n-mb-lg-0"> <p>{{ $letestNews->varShortDescription }} </p></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                <div id="pageContent"></div>
            </div>
        </div>
    </section>
@else
   

    {{-- Content Start --}}
    <section class="inner-page-gap news-page">
        {{-- Year Filter horizontal scrolling // START --}}
        <div class="container">
            <div class="fliter_section">
                <h5>Sort by Years</h5>
                <div class="minute-wrap">
                    <ul class="mCcontentx" id="yearFilter">
                        @php
                            $yearsArr = array_combine(range(date("Y"), 2019), range(date("Y"), 2019));
                        @endphp

                        @php $i = "1"; @endphp
                        @foreach($yearsArr as $key => $value)
                            @if($i == 1 || $i == 2)
                                @php $checked = "checked"; @endphp
                            @else
                                @php $checked = ""; @endphp
                            @endif
                            <li>
                                <div class="form-group ac-form-group n-mb-0">
                                    <div class="ac-checkbox-list n-pt-0">
                                        <label class="ac-checkbox">
                                            <input type="checkbox" value="{{ $key }}" {{$checked}}> {{ $value }} <span></span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                            @php $i++ @endphp
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        {{-- Year Filter horizontal scrolling // END --}}
        <div class="container">
            <div id="pageContent" class="">
                @if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]')
                    @php echo $PAGE_CONTENT['response']; @endphp
                @else
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">No data found</div>
                            <div class="desc n-fs-20 n-fw-500 n-lh-130">Please reset filter to see the data.</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif

@php
    $val = json_decode($pageContent->txtDescription);
    if(!empty($val)){
        foreach ($val as $key => $limitval) {
            if ($limitval->type == 'news_template') {
                $lim =   $limitval->val->limit;
            }
        }
        if(isset($lim) && !empty($lim)){
            $limit = $lim;
        }else{
            $limit = '12';
        }
    } else {
        $limit = '';
    }
@endphp

<script type="text/javascript">
    let textDescription = "{{json_encode($pageContent->txtDescription)}}";
    var Limits = "{{$limit}}";
</script>
    <script src="{{ $CDN_PATH.'assets/js/packages/news/news.js' }}" type="text/javascript"></script>
@if(!Request::ajax())
    @section('footer_scripts')
        <!-- <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script> -->
    @endsection
@endsection
@endif