@if(isset($data['registerApplications']) && !empty($data['registerApplications']) && count($data['registerApplications']) > 0)
    @foreach($data['registerApplications'] as $registerApplication)
        @php
            if(isset(App\Helpers\MyLibrary::getFront_Uri('register-application')['uri'])){
                $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('register-application')['uri'];
                $moduleFrontWithCatUrl = ($registerApplication->varAlias != false ) ? $moduelFrontPageUrl . '/' . $registerApplication->varAlias : $moduelFrontPageUrl;
                $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('register-application',$registerApplication->txtCategories);
                $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$registerApplication->alias->varAlias;
            } else {
                $recordLinkUrl = '';
            }
        @endphp
        <div class="col-lg-4 col-md-6 d-flex n-gap-2 n-gapp-lg-4 n-gapm-lg-3 n-gapm-md-2" data-aos="zoom-in">
            <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                <div class="n-fs-16 n-fw-500 n-ff-2 n-fc-black-500 n-lh-130"><a href="{{ $recordLinkUrl }}" title="{{$registerApplication->varTitle}}" >{{ $registerApplication->varTitle }}</a></div>
                <div class="-status n-mt-40 n-fs-16 n-lh-120">
                    <div class="-status n-fc-a-500 n-fw-600">Current Status</div>
                    {{ $registerApplication->varStatus }}
                </div>
            </article>
        </div>
    @endforeach
    
    @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
        @if($data['registerApplications']->total() > $data['registerApplications']->perPage())
            <div class="col-lg-12">
                <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up" id="paginationSection">
                    @include('partial.pagination', ['paginator' => $data['registerApplications']->links()['paginator'], 'elements' => $data['registerApplications']->links()['elements']['0']])
                </div>
            </div>
        @endif
    @endif
@endif