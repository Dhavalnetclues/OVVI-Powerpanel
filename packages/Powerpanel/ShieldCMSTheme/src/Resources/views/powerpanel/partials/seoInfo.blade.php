@php $metaInfoRequired = true; @endphp
@if(isset($metaRequired) && $metaRequired==true )
@php $metaInfoRequired = true; @endphp
@elseif(isset($metaRequired) && $metaRequired==false)
@php $metaInfoRequired = false; @endphp
@endif
{{-- <h3 class="form-section mb-3">{{ trans('template.common.seoinformation') }}</h3> --}}
<div class="row" id="edit_seo">
    <div class="col-md-12">
        <div class="form-group">
            @if(!empty($inf))
                @php  $Display = 'none'  @endphp
                <div class="d-flex justify-content-between seo-titlepart">
                    <h4 class="form-section mb-0">{{ trans('template.common.seoinformation') }}</h4>
                </div>
                <div class="seo_editor seocontent-part">
                    <div class="search-cover">
                        <div class="search-img">
                            <img src="{{ Config::get('Constant.CDN_PATH').'resources/images/seo-img.jpg' }}" alt="" title="">
                        </div>
                        <div class="search-part">
                            @if(isset($inf) && isset($inf['varURL']))
                                @php  $varURL = url('/'.$inf['varURL'])  @endphp
                            @else
                                @php  $varURL = '#'  @endphp
                            @endif

                            @if(isset($inf) && isset($inf['varMetaTitle']))
                                @php  $metaTitle = $inf['varMetaTitle']  @endphp
                                @php 
                                    if(!empty($inf_highLight['varMetaTitle']) && !empty($inf['varMetaTitle']) && ($inf_highLight['varMetaTitle'] != $inf['varMetaTitle'])){
                                        $Class_metatitle = " highlitetext";
                                    }else{
                                        $Class_metatitle = "";
                                    } 
                                @endphp
                                <h4 class="page-title"><span class="{!! $Class_metatitle !!}" id="meta_title">{{ $metaTitle }}</span></h4>
                                <a class="link" href="{{ $varURL }}" target="_blank">{{ $varURL }}</a>
                            @endif
                            
                            @if(isset($inf) && isset($inf['varMetaDescription']))
                                @php  $metaDescription = $inf['varMetaDescription']  @endphp
                                @php 
                                if(!empty($inf_highLight['varMetaDescription']) && !empty($inf['varMetaDescription']) && ($inf_highLight['varMetaDescription'] != $inf['varMetaDescription'])){
                                    $Class_metaDescription = " highlitetext";
                                }else{
                                    $Class_metaDescription = "";
                                } 
                                @endphp
                                <p class="mb-1"><span class="{{ $Class_metaDescription }}" id="meta_description">{{ $metaDescription }}</span></p>
                            @endif
                            <p class="mb-0 d-none">
                                @if(isset($inf) && isset($inf['varTags']) && !empty($inf['varTags']))
                                    @php $tagArr = explode(',',$inf['varTags']) @endphp

                                    @if(!empty($tagArr))
                                        @php
                                        if(!empty($inf_highLight['varTags']) && !empty($tagArr) && ($inf_highLight['varTags'] != $tagArr)){
                                            $Class_varTags = " highlitetext";
                                        }else{
                                            $Class_varTags = "";
                                        }
                                        @endphp

                                        <i class="ri-price-tag-3-fill" aria-hidden="true"></i>
                                        @foreach($tagArr as $key => $value)
                                            @if($key == count($tagArr) - 1)
                                                @php $sep = '' @endphp
                                            @else
                                                @php $sep = ', ' @endphp
                                            @endif
                                            &nbsp;<a style="color:#1D4DA1" target="_blank" href="#"><span>{{ $value.$sep }}</span></a>
                                        @endforeach 
                                    @endif 
                                    &nbsp; &nbsp; 
                                @endif
                                @if(isset($srank) && $srank == 1)
                                    <i class="ri-bar-chart-fill" title="Search Ranking" aria-hidden="true"></i> <span>High</span>
                                @elseif(isset($srank) && $srank == 2)
                                    <i class="ri-bar-chart-fill" title="Search Ranking" aria-hidden="true"></i> <span>Medium</span>
                                @elseif(isset($srank) && $srank == 3)
                                    <i class="ri-bar-chart-fill" title="Search Ranking" aria-hidden="true"></i> <span>Low</span>
                                @endif
                            </p>
                            <div class="all-btngroup mt-3">
                                <button type="button" id='auto-generate' class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" onclick="generate_seocontent('@if(!empty($form)){{ $form }}@endif');">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-refresh-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            {{ trans('template.common.autogenerate') }}
                                        </div>
                                    </div>
                                </button>
                                <button type="button" id='seo_edit' class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-edit-2-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            Edit Snippet
                                        </div>
                                    </div>
                                </button>
                                <button type="button" id='seo_edit_time' class="btn btn-primary bg-gradient waves-effect waves-light btn-label" style="display: none; margin-left: 0px;">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-edit-2-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            Edit Snippet
                                        </div>
                                    </div>
                                </button>
                            </div>
                            
                    </div>
                </div>
                @else
                @php  $Display = 'none'  @endphp
                <button type="button" id='auto-generate' class="btn btn-primary bg-gradient waves-effect waves-light btn-label" onclick="generate_seocontent('@if(!empty($form)){{ $form }}@endif');">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-refresh-line label-icon align-middle fs-20 me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            {{ trans('template.common.autogenerate') }}
                        </div>
                    </div>
                </button>
                <button type="button" id='seo_edit' class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-edit-2-line label-icon align-middle fs-20 me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            Edit Snippet
                        </div>
                    </div>
                </button>
                <button type="button" id='seo_edit_time' class="btn btn-primary bg-gradient waves-effect waves-light btn-label" style="display: none; margin-left: 0px;">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-edit-2-line label-icon align-middle fs-20 me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            Edit Snippet
                        </div>
                    </div>
                </button>
                <div class="seo_editor mt-3" style="display: none;">
                    <h4><span id="meta_title"></span></h4>
                    <p class="seo_link">
                        <a onClick="generatePreview('{{url('/previewpage?url='.(url('/')))}}');" class="snippet_alias"></a>
                    </p>
                    <p class="mb-0"><span id="meta_description"></span></p>
                </div>
            @endif
        </div>
    </div>
</div>

<div id="seo_edit_dispaly" style="display:{!! $Display !!}">
                                <div class="row mt-4">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating form-group @if($errors->first('varMetaTitle')) has-error @endif">
                                            @php if(!empty($inf_highLight['varMetaTitle']) && !empty($inf['varMetaTitle']) && ($inf_highLight['varMetaTitle'] != $inf['varMetaTitle'])){
                                            $Class_metatitle = " highlitetext";
                                            }else{
                                            $Class_metatitle = "";
                                            } 
                                            @endphp
                                            <label class="control-label form-label {!! $Class_metatitle !!}">{{ trans('template.common.metatitle') }} 
                                                @if($metaInfoRequired)
                                                <span aria-required="true" class="required"> * </span>
                                                @endif
                                            </label>      

                                            @if(isset($inf) && isset($inf['varMetaTitle']))
                                                @php  $metaTitle = $inf['varMetaTitle']  @endphp
                                            @else
                                                @php  $metaTitle = null  @endphp
                                            @endif

                                            {!! Form::text('varMetaTitle', $metaTitle , array('maxlength'=>'160','class' => 'form-control maxlength-handler metatitlespellingcheck','id'=>'varMetaTitle','autocomplete'=>'off','onkeyup'=>'MetaTitle_Function(this.value)')) !!}
                                            <!-- <span>Maximum 500 Characters </span> -->
                                            <span class="help-block">{{ $errors->first('varMetaTitle') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating form-group" id="tags-input">
                                            <label class="form-label {{ (isset($Class_varTags)?$Class_varTags:'') }}" for="site_name">Add New Tag</label>   
                                            @php
                                            if(!empty($inf_highLight['varTags']) && !empty($tagArr) && ($inf_highLight['varTags'] != $tagArr)){
                                                $Class_varTags = " highlitetext";
                                            }else{
                                                $Class_varTags = "";
                                            }
                                            @endphp     
                                            @if(isset($inf) && isset($inf['varTags']) && !empty($inf['varTags']))
                                                @php  $tags = $inf['varTags']  @endphp
                                            @else
                                                @php  $tags = null  @endphp
                                            @endif
                                            {!! Form::text('tags',$tags, array('class' => 'form-control', 'data-role' => 'tagsinput')) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    @if(Config::get('Constant.CHRSearchRank') == 'Y')
                                    <div class="col-md-12">
                                    @else
                                    <div class="col-md-12">
                                    @endif
                                        <div class="cm-floating mb-0 form-group @if($errors->first('varMetaDescription')) has-error @endif">
                                            @php if(!empty($inf_highLight['varMetaDescription']) && !empty($inf['varMetaDescription']) && ($inf_highLight['varMetaDescription'] != $inf['varMetaDescription'])){
                                            $Class_metaDescription = " highlitetext";
                                            }else{
                                            $Class_metaDescription = "";
                                            } 
                                            @endphp
                                            <label class="control-label form-label {!! $Class_metaDescription !!}">{{ trans('template.common.metadescription') }} 
                                                @if($metaInfoRequired)
                                                <span aria-required="true" class="required"> * </span>
                                                @endif
                                            </label>

                                            @if(isset($inf) && isset($inf['varMetaDescription']))
                                            @php  $metaDescription = $inf['varMetaDescription']  @endphp
                                            @else
                                            @php  $metaDescription = null  @endphp
                                            @endif

                                            {!! Form::textarea('varMetaDescription', $metaDescription, 
                                            array(
                                            'maxlength'=>'200',
                                            'class' => 'form-control resize-none maxlength-handler metadescspellingcheck',              
                                            'cols' => '40', 
                                            'rows' => '2',
                                            'id' => 'varMetaDescription',
                                            'spellcheck' => 'true',
                                            'onkeyup'=>'MetaDescription_Function(this.value)'
                                            )) 
                                            !!}
                                            <!-- <span>Maximum 500 Characters </span> -->
                                            <span class="help-block">{{ $errors->first('varMetaDescription') }}</span>
                                        </div>
                                    </div>

                                    {{-- @if(Config::get('Constant.CHRSearchRank') == 'Y')
                                        <div class="col-md-6 mb-1">
                                            <label class="{{ (isset($Class_intSearchRank)?$Class_intSearchRank:'') }} form-label">Search Ranking</label>
                                            <a href="javascript:;" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('blogs::template.common.SearchEntityTools') }}" title="{{ trans('blogs::template.common.SearchEntityTools') }}"><i class="ri-information-line text-danger fs-18"></i></a>
                                            <div class="wrapper search_rank">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="search_rank" @if(isset($srank) && $srank == 1) checked @endif id="yes_radio" value="1">
                                                    <label class="form-check-label" id="yes-lbl">High</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="search_rank" @if(isset($srank) && $srank == 2) checked @endif id="maybe_radio" value="2">
                                                    <label class="form-check-label" id="maybe-lbl">Medium</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="search_rank" @if(isset($srank) && $srank == 3) checked @endif id="no_radio" value="3">
                                                    <label class="form-check-label" id="no-lbl">Low</label>
                                                </div>
                                                <div class="toggle"></div>
                                            </div>
                                        </div>
                                    @endif --}}
                                </div>
                            </div>
                        </div>

<script>
function MetaTitle_Function(value) {
    document.getElementById("meta_title").innerHTML = value;
}
function MetaDescription_Function(value) {
    document.getElementById("meta_description").innerHTML = value;
}
</script>
