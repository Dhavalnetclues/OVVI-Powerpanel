@if(isset($data['quickLink']) && !empty($data['quickLink']))

@if(isset($data['class']))
@php $class = $data['class']; @endphp
@endif

<section class="n-pt-50 n-pt-lg-100 home-short-service home-short-service-slider" data-aos="fade-up">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="owl-carousel owl-theme">
                    @foreach($data['quickLink'] as $qlink)
                    @php
                    $link ='';
                    @endphp
                    @if(isset($qlink->varLinkType) &&  $qlink->varLinkType == 'external')

                        @php
                        $link = $qlink->varExtLink ;
                        @endphp

                    @elseif(isset($qlink->varLinkType) &&  $qlink->varLinkType == 'internal')
                        @php
                            if(isset($qlink->fkModuleId) && $qlink->fkModuleId == '3')
                            {
                                $module = \App\Modules::getModuleById($qlink->fkModuleId);
                                if ($module->varModuleNameSpace != '') {
                                    $model = $module->varModuleNameSpace . 'Models\\' . $module->varModelName;
                                } 
                                $aliasid = $model::getRecordById($qlink->fkIntPageId);
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
                            } else{
                                $module = \App\Modules::getModuleById($qlink->fkModuleId);

                                if(isset(App\Helpers\MyLibrary::getFront_Uri($module->varModuleName)['uri'])) {
                                    $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri($module->varModuleName)['uri'];
                                }

                                if ($module->varModuleNameSpace != '') {
                                    $model = $module->varModuleNameSpace . 'Models\\' . $module->varModelName;
                                } else {
                                    $model = '\\App\\' . $module->varModelName;
                                }

                                $aliasid = $model::getRecordById($qlink->fkIntPageId);
                                $alias = \App\Alias::getAliasbyID($aliasid->intAliasId);

                                $link = (isset($moduelFrontPageUrl) && !empty($alias)) ? $moduelFrontPageUrl . '/' . $alias->varAlias : $moduelFrontPageUrl;

                            }
                        @endphp
                    @endif
                    <div class="item item-m d-flex">
                        <div class="align-self-end">
                            <h2 class="title-m">{{$qlink->varTitle}}</h2>
                            <a href="{{$link}}" class="more-info" title="More Info">More Info</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>			
        </div>
    </div>
</section>


@endif
