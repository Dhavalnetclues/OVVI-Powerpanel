@if(isset($data['licenseRegisters']) && !empty($data['licenseRegisters']) && count($data['licenseRegisters']) > 0)
    <div class="row n-mt-25">
        @foreach($data['licenseRegisters'] as $licenseRegister)
            @php
                if(isset(App\Helpers\MyLibrary::getFront_Uri('licence-register')['uri'])){
                    $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('licence-register')['uri'];
                    $moduleFrontWithCatUrl = ($licenseRegister->varAlias != false ) ? $moduelFrontPageUrl . '/' . $licenseRegister->varAlias : $moduelFrontPageUrl;
                    $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('licence-register',$licenseRegister->txtCategories);
                    $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$licenseRegister->alias->varAlias;
                } else {
                    $recordLinkUrl = '';
                }
            @endphp
        
            <div class="col-lg-4 col-md-6 d-flex n-gap-2 n-gapp-lg-4 n-gapm-lg-3 n-gapm-md-2" data-aos="zoom-in">
                <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500" data-id="{{ $licenseRegister->varCompanyId }}">
                    <div class="n-fs-16 n-fw-500 n-ff-2 n-fc-black-500 n-lh-130"><a href="{{ $recordLinkUrl }}" title="{{$licenseRegister->varTitle}}">{{$licenseRegister->varTitle}}</a></div>
                    @if(isset($licenseRegister->dtDateTime) && !empty($licenseRegister->dtDateTime))
                    <div class="-status n-mt-40 n-fs-16 n-lh-120">
                        <div class="-status n-fc-a-500 n-fw-600">Date of Issue</div>
                        {{ date('d',strtotime($licenseRegister->dtDateTime)) }} {{ date('M',strtotime($licenseRegister->dtDateTime)) }}, {{ date('Y',strtotime($licenseRegister->dtDateTime)) }} 
                        @if(isset($licenseRegister->dtRenewaldate) && !empty($licenseRegister->dtRenewaldate))
                            ({{$licenseRegister->varStatus}} {{ date('d',strtotime($licenseRegister->dtDateTime)) }} {{ date('M',strtotime($licenseRegister->dtRenewaldate)) }}, {{ date('Y',strtotime($licenseRegister->dtRenewaldate)) }})
                        @endif
                    </div>
                    @endif
                    <div class="-status n-mt-15 n-fs-16 n-lh-120">
                        <div class="-status n-fc-a-500 n-fw-600">Current Status</div>
                        {{$licenseRegister->varStatus}}
                    </div>
                </article>
            </div>
        @endforeach
    </div>
    @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
        @if($data['licenseRegisters']->total() > $data['licenseRegisters']->perPage())
            <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up" id="paginationSection">
                @include('partial.pagination', ['paginator' => $data['licenseRegisters']->links()['paginator'], 'elements' => $data['licenseRegisters']->links()['elements']['0']])
            </div>
        @endif
    @endif
@endif