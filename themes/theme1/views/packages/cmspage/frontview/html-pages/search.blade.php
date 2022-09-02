@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
<section class="inner-page-gap search">
    @include('layouts.share-email-print')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="nqtitle-small n-fc-black-500">Search Results for <span class="n-fc-a-500">fuel</span></h2>
                <div class="n-fs-18 n-fw-500 n-fc-black-500 n-lh-110">About 244 results</div>

                <!-- <h2 class="nqtitle-small n-fc-black-500">Your search <span class="n-fc-a-500">asfsdfasf</span> did not match with any records.</h2>
                <div class="n-fs-18 n-fw-500 n-fc-black-500 n-lh-110">No results containing all your search terms were found.</div>
                <div class="cms n-mt-15">
                    <h2>Suggestions:</h2>
                    <ul>
                        <li>Try a different keyword or alternate search terms.</li>
                        <li>Broaden your search using fewer keywords or use simpler language.</li>
                    </ul>
                    <p><strong>Kindly try again using the above search tips.</strong></p>
                </div> -->
            </div>
        </div>

        <div class="row n-mt-25">
            @php for ($x = 1; $x <= 6; $x++) { @endphp
            <div class="col-xl-2 col-md-4 col-sm-6 n-gapp-7 n-gapm-xl-4 n-gapm-md-3 n-gapm-sm-2 d-flex">
                <article class="-items w-100 d-flex flex-column n-bs-1 n-br-5">
                    <div class="thumbnail-container" data-thumb="66.66%">
                        <div class="thumbnail">
                            
                        </div>
                    </div>
                    <div class="n-pa-15 d-flex flex-column -desc">
                        <div class="n-fs-14 n-fw-500 n-fc-dark-500 n-lh-130 n-mb-10">OfReg</div>
                        <h2 class="n-fs-18 n-fw-500 n-ff-1 n-fc-black-500 n-lh-120"><a href="#" title="Press Release - OfReg Approves New Consumer Owned Renewable Energy (CORE) Tariff" class="n-ah-a-500">Press Release - OfReg Approves New Consumer Owned Renewable Energy (CORE) Tariff</a></h2>
                        <div class="n-fs-14 n-fw-500 n-fc-dark-500 n-lh-130 mt-auto n-pt-15"> Mar 10, 2021 </div>
                    </div>
                </article>
            </div>
            @php } @endphp
        </div>

        <div class="row n-mt-30 justify-content-center">
            @php for ($x = 1; $x <= 6; $x++) { @endphp
            <div class="col-xl-6 n-gapp-3 n-gapm-xl-2 d-flex" data-aos="fade-up">
                <article class="-items n-bs-1 n-br-5 n-pa-15 w-100 d-flex flex-column">
                    <div class="row no-gutters">
                        <div class="col-sm-2 col-3">
                            <div class="thumbnail-container" data-thumb="100%">
                                <div class="thumbnail">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-10 col-9 n-pl-15">
                            <div class="n-fs-14 n-fw-500 n-fc-dark-500 n-lh-130 n-mb-10">OfReg</div>
                            <h2 class="n-fs-20 n-fw-500 n-ff-1 n-fc-black-500 n-lh-120"><a href="#" title="Press Release - OfReg Approves New Consumer Owned Renewable Energy (CORE) Tariff" class="n-ah-a-500">Press Release - </a></h2>
                            <!-- <div class="n-fs-17 n-fw-500 n-fc-dark-500 n-lh-130 n-mt-10">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</div> -->
                            <div class="n-fs-14 n-fw-500 n-fc-dark-500 n-lh-130 mt-auto n-pt-15"> Mar 10, 2021 </div>
                        </div>
                    </div>  
                </article>
            </div>

            <div class="col-xl-6 n-gapp-3 n-gapm-xl-2 d-flex" data-aos="fade-up">
                <article class="-items n-bs-1 n-br-5 n-pa-15 w-100 d-flex flex-column">
                    <div class="n-fs-14 n-fw-500 n-fc-dark-500 n-lh-130 n-mb-10">OfReg</div>
                    <h2 class="n-fs-20 n-fw-500 n-ff-1 n-fc-black-500 n-lh-120"><a href="#" title="Press Release - OfReg Approves New Consumer Owned Renewable Energy (CORE) Tariff" class="n-ah-a-500">Press Release - OfReg Approves New Consumer Owned Renewable Energy (CORE) Tariff</a></h2>
                    <div class="n-fs-17 n-fw-500 n-fc-dark-500 n-lh-130 n-mt-10">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</div>
                    <div class="n-fs-14 n-fw-500 n-fc-dark-500 n-lh-130 mt-auto n-pt-15"> Mar 10, 2021 </div>
                </article>
            </div>
            @php } @endphp
        </div>

        <div class="row">
            <div class="col-12 n-mt-lg-80 n-mt-40">
                <ul class="pagination justify-content-center align-content-center">
                    <li class="page-item">
                        <a class="page-link" href="#" title="Previous">
                            <i class="n-icon" data-icon="s-pagination"></i>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#" title="1">1</a></li>
                    <li class="page-item active"><a class="page-link" href="#" title="2">2</a></li>
                    <li class="page-item"><a class="page-link" href="#" title="3">3</a></li>
                    <li class="page-item"><a class="page-link" href="#" title="4">4</a></li>
                    <li class="page-item"><a class="page-link" href="#" title="5">5</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" title="Next">
                            <i class="n-icon" data-icon="s-pagination"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

@if(!Request::ajax())
@section('footer_scripts')


@endsection
@endsection
@endif