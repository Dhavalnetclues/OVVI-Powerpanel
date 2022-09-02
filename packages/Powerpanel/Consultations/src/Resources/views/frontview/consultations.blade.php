@if(!Request::ajax())
@extends('layouts.app')
@section('content')
{{-- @include('layouts.inner_banner') --}}
{{-- Banner Start --}}
<section class="home-banner inner-page-banner" data-aos="fade-up">
    <div class="slider owl-carousel">
        <div class="item">
            <div class="thumbnail-container">
                <div class="thumbnail">
                    <picture>
                        <source media="(max-width:1024px)" srcset="{{ $CDN_PATH.'assets/images/Default-Banner-M.jpg' }}">
                        <img src="{{ $CDN_PATH.'assets/images/Default-Banner-D.jpg' }}" alt="Title" title="Inner Banner" />

                        @if(!empty($inner_banner_data[0]) && isset($inner_banner_data[0]))
                        <img src="{!! App\Helpers\resize_image::resize($inner_banner_data[0]->fkIntInnerImgId,1920,853) !!}" alt="{{ $inner_banner_data[0]->varTitle }}" title="{{ $inner_banner_data[0]->varTitle }}" />
                        @endif
                    </picture>
                </div>
            </div>
        </div>
    </div>
    <div class="-banner-item" data-aos="flip-up">
        <div class="container">
            <div class="container-w">
                <div class="row">
                    <div class="col-12">
                        <div class="banner-tabs">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Consultations</a>
                                </li>
                                <li class="nav-item search-tab aaa">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                        <span class="search-wrap">
                                            <button type="submit"><i class="n-icon bbb" data-icon="s-search"></i></button>
                                            <input type="text" class="bbb" placeholder="Search" id="searchfilter">
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="link-list">
                                        <ul class="nqul s-category d-flex flex-wrap n-fs-14 n-ff-2 n-fw-600 n-fc-black-500" id="categoryFilter">
                                            <li class="all-tag consultationTagForMove"><a class="active" href="javascript:void(0)" title="All">All Consultations</a></li>
                                            <li class="energy-tag consultationTagForMove"><a href="javascript:void(0)" title="Energy">Energy</a></li>
                                            <li class="fuel-tag consultationTagForMove"><a href="javascript:void(0)" title="Fuel">Fuel</a></li>
                                            <li class="ict-tag consultationTagForMove"><a href="javascript:void(0)" title="ICT">ICT</a></li>
                                            <li class="water-tag consultationTagForMove"><a href="javascript:void(0)" title="Water">Water</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- Banner End --}}
@endif

@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
    {{-- Year Filter horizontal scrolling // START --}}
    <div class=" n-mb-lg-30 n-mv-40 container">
        <h3 class="nqtitle" data-aos="fade-up">Sort by Years</h3>
        <div class="minute-wrap" data-aos="flip-up">
            <ul class="mCcontentx" id="yearFilter">
                @php
                    // $year = now()->year;
                    // $lastYear = $year - 3;
                    $yearsArr = array_combine(range(date("Y"), 2019), range(date("Y"), 2019));
                @endphp
                {{-- @for ($i = $year; $i >= $lastYear; $i--)
                    <li class="n-mt-30 n-mb-30">
                        <div class="form-group ac-form-group n-mb-0">
                            <div class="ac-checkbox-list n-pt-0">
                                <label class="ac-checkbox">
                                    <input type="checkbox" value="{{ $i }}">
                                    {{ $i }}<span></span>
                                </label>
                            </div>
                        </div>
                    </li>
                @endfor --}}
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
    <section class="inner-page-gap news-listing consultations-listing">
        <div class="container n-pt-lg-130 n-pt-50">
            <div class="row">
                {{-- @include('consultations::frontview.consultations-left-panel') --}}
                @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                <div class="col-xl-12" id="pageContent">
                </div>
            </div>
        </div>
    </section>
@else
    {{-- Year Filter horizontal scrolling // START --}}
    <div class=" n-mb-lg-30 n-mv-40 container">
        <h3 class="nqtitle" data-aos="fade-up">Sort by Years</h3>
        <div class="minute-wrap" data-aos="flip-up">
            <ul class="mCcontentx" id="yearFilter">
                @php
                    // $year = now()->year;
                    // $lastYear = $year - 3;
                    $yearsArr = array_combine(range(date("Y"), 2019), range(date("Y"), 2019));
                @endphp

                {{-- @for ($i = $year; $i >= $lastYear; $i--)
                    <li class="n-mt-30 n-mb-30">
                        <div class="form-group ac-form-group n-mb-0">
                            <div class="ac-checkbox-list n-pt-0">
                                <label class="ac-checkbox">
                                    <input type="checkbox" value="{{ $i }}"> {{ $i }} <span></span>
                                </label>
                            </div>
                        </div>
                    </li>
                @endfor --}}
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

    <section class="inner-page-gap news-listing consultations-listing">
        <div class="container n-pt-lg-30 n-pt-50">
            <div class="row">
                {{-- @include('consultations::frontview.consultations-left-panel') --}}
                <div class="col-xl-12 container" id="pageContent">
                    @if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]')
                        @php echo $PAGE_CONTENT['response']; @endphp
                    @else 
                        <div class="row container">
                            <div class="col-12 text-center" data-aos="fade-up">
                                <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">No data found</div>
                                <div class="desc n-fs-20 n-fw-500 n-lh-130">Please reset filter to see the data.</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
@php
    $val = json_decode($pageContent->txtDescription);
    if(!empty($val)){
        foreach ($val as $key => $limitval) {
            if ($limitval->type == 'consultations_template') {
                $lim =   $limitval->val->limit;
            }
        }
        if(isset($lim) && !empty($lim)){
            $limit = $lim;
        }else{
            $limit = '12';
        }
    }else{
        $limit = '';
    }
@endphp

<script type="text/javascript">
    let textDescription = "{{ json_encode($txtDescription) }}"
    let Limits = "{{$limit}}"
</script>
<script src="{{ $CDN_PATH.'assets/js/packages/consultations/consultations.js' }}" type="text/javascript"></script>
@if(!Request::ajax())
    @section('footer_scripts')
        <!-- <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script> -->
    @endsection
@endsection
@endif