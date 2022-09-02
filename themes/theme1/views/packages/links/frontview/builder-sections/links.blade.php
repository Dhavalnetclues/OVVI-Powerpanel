@if(Request::segment(1) == '')
@if(isset($data['links']) && !empty($data['links']) && count($data['links']) > 0)
<section class="n-pt-50 n-pt-lg-100 home-service home-service-slider" data-aos="fade-up">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel owl-theme">
                    @foreach($data['links'] as $link => $linksobj)
                    <div class="item">
                        <article class="-items w-100 n-bs-1 n-mv-30 n-bgc-white-500">
                            <div class="-img">
                                <div class="thumbnail-container">
                                    <div class="thumbnail">
                                        <img src="{!! App\Helpers\resize_image::resize($linksobj->fkIntImgId) !!}" alt="{{$linksobj->varTitle}}" title="{{$linksobj->varTitle}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="-stitle">
                                <div class="nqtitle n-fw-800 text-uppercase">{{$linksobj->varTitle}}</div>
                                <div class="text-uppercase n-fs-18 n-fw-500 n-fc-black-500">{{$linksobj->varsubtitle}}</div>
                            </div>

                            <ul class="nqul n-fs-20 n-fw-500 n-fc-black-500 n-lh-130 n-mh-xl-25">
                                @foreach($linksobj['items'] as $childlink)

                                @php $link =''; @endphp
                                @if(isset($childlink->varLinkType) &&  $childlink->varLinkType == 'external')

                                    @php
                                    $link = $childlink->varExtLink ;
                                    @endphp

                                @elseif(isset($childlink->varLinkType) &&  $childlink->varLinkType == 'internal')

                                    @php
                                    if(isset($childlink->fkModuleId) && $childlink->fkModuleId == '3')
                                    {
                                        $module = \App\Modules::getModuleById($childlink->fkModuleId);
                                        if ($module->varModuleNameSpace != '') {
                                            $model = $module->varModuleNameSpace . 'Models\\' . $module->varModelName;
                                        } 
                                        $aliasid = $model::getRecordById($childlink->fkIntPageId);

                                        if(isset($aliasid->intAliasId) && !empty($aliasid->intAliasId))
                                        {
                                            $alias = \App\Alias::getAliasbyID($aliasid->intAliasId);
                                            if(isset($aliasid->varSector) && $aliasid->varSector == 'ofreg'){
                                                $slug = '';
                                            }
                                            else{
                                            $slug = $aliasid->varSector;
                                            }
                                            if($slug == ''){
                                                $link = url($alias['varAlias']);
                                            }else{
                                                if(isset($alias['varAlias']) && isset($slug)){
                                                    $link =  url($slug . '/' .$alias['varAlias']);
                                                } else{
                                                $link ='';
                                                }
                                            }
                                        }
                                        
                                    } else{
                                        $module = \App\Modules::getModuleById($childlink->fkModuleId);

                                        if(isset(App\Helpers\MyLibrary::getFront_Uri($module->varModuleName)['uri'])) {
                                            $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri($module->varModuleName)['uri'];
                                        }

                                        if ($module->varModuleNameSpace != '') {
                                            $model = $module->varModuleNameSpace . 'Models\\' . $module->varModelName;
                                        } else {
                                            $model = '\\App\\' . $module->varModelName;
                                        }

                                        $aliasid = $model::getRecordById($childlink->fkIntPageId);
                                        
                                        if(isset($aliasid->intAliasId) && !empty($aliasid->intAliasId)){
                                            $alias = \App\Alias::getAliasbyID($aliasid->intAliasId);
                                            $link = (isset($moduelFrontPageUrl) && !empty($alias)) ? $moduelFrontPageUrl . '/' . $alias->varAlias : $moduelFrontPageUrl;
                                        }
                                    }
                                    @endphp
                                    
                                @endif
                                
                                <li><a class="n-ah-a-500" href="{{$link}}" title="{{$childlink->varTitle}}">{{$childlink->varTitle}}</a></li>
                                @endforeach

                            </ul>
                        </article>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@else

<section>
    @php 
    if(isset($data['class'])){
    $class = $data['class'];
    }
    @endphp
    <div class="inner-page-container cms links_page {{ $class }}">
        <div class="container">
            <!-- Main Section S -->
            <div class="row">
                <div class="col-md-9 col-md-12 col-xs-12 animated fadeInUp">
                    <div class="right_content">
                        @php
                        if(isset($data['selectionlink']) && $data['selectionlink'] == 'Y'){
                        $linkarray = array();
                        @endphp
                        @if(!empty($data['links']) && count($data['links'])>0)
                        @foreach($data['links'] as $links)
                        @php
                        $linkarray[$links->intFKCategory][] = $links;
                        @endphp
                        @endforeach

                        @foreach($linkarray as $key => $linksItems)
                        @php
                        $linkcategory = \Powerpanel\LinksCategory\Models\LinksCategory::getCategoryIds($key);
                        @endphp
                        <h4>{{ $linkcategory->varTitle }}</h4>
                        <ul>
                            @foreach($linksItems as $key => $linkdata)
                            @php
                            $link = $linkdata->txtLink ;
                            @endphp
                            <li>
                                <p>{{ $linkdata->varTitle }} - <a class="li_link" href="{{ $link }}" title="{{ $link }}" target="_blank">{{ $link }}</a></p>
                            </li>
                            @endforeach
                        </ul>
                        @endforeach
                        @endif
                        @php
                        }else{
                        @endphp
                        @if(!empty($data['links']) && count($data['links'])>0)
                        @foreach($data['links'] as $linkcategory)
                        @if(!empty($linkcategory->items) && count($linkcategory->items) > 0)
                        <h4 id="catlist_{{ $linkcategory->id }}">{{ $linkcategory->varTitle }}</h4>
                        <ul>
                            @foreach($linkcategory->items as $linksItems)
                            @php
                            $link = $linksItems->txtLink ;
                            @endphp
                            <li>
                                <p>{{ $linksItems->varTitle }} - <a class="li_link" href="{{ $link }}" title="{{ $link }}" target="_blank">{{ $link }}</a></p>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                        @endforeach
                        @endif
                        @php
                        }
                        @endphp
                    </div>
                </div>
            </div>
            <!-- Main Section E -->
        </div>
    </div>
</section>
@endif
<script type="text/javascript">
    $(document).on("click", ".sidebar_catlist", function (event) {
        event.preventDefault();
        var scrollIdData = $(this).attr('href');
        var scrollId = scrollIdData.split('#')[1];
        var docsectionStartPosition = $("#" + scrollId).offset().top - $("header").height() - $('.header-section').height();
        $('html, body').animate({
            scrollTop: docsectionStartPosition
        }, 'slow');
    });
</script>