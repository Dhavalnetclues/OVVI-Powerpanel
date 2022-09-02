 
@if (isset($Class_chrPublish))
@php  $Class_chrPublish = $Class_chrPublish  @endphp
@else
@php  $Class_chrPublish = ''  @endphp
@endif

<div class="form-group">
    @if(!empty($display_highlight) && $display_highlight != $display)
    @php $Class_metatitle = " highlitetext"; @endphp
    @else
    @php $Class_metatitle = " "; @endphp
    @endif  

<!--  <label class="control-label form-label">{{ trans('template.display') }} <span aria-required="true" class="required"> * </span> </label> -->
    <!-- <label class="form-label {{ $Class_chrPublish }} {!! $Class_metatitle !!}" for="site_name">Status <span aria-required="true" class="required"> * </span></label> -->
    <div class="md-radio-inline mb-3">
        <div class="form-check form-check-inline">
            @if ((isset($display) && $display == 'Y') || (null == old('chrMenuDisplay') || old('chrMenuDisplay') == 'Y'))
            @php  $checked_yes = 'checked'  @endphp
            @else
            @php  $checked_yes = ''  @endphp
            @endif
            <input class="form-check-input" type="radio" name="chrMenuDisplay" id="chrMenuDisplay" value="Y" {{ $checked_yes }}>
            @if (Request::segment(2)=='users') 
            <label class="form-check-label" for="chrMenuDisplay"> <span class="check"></span> <span class="box"></span> {{ trans('template.common.activate') }}  </label>
            @else
            <label class="form-check-label" for="chrMenuDisplay"> <span class="check"></span> <span class="box"></span> {{ trans('template.common.publish') }} </label>
            @endif
        </div>

        <div class="form-check form-check-inline">
            @if ((isset($display) && $display == 'N') || (old('chrMenuDisplay') == 'N'))
            @php  $checked_no = 'checked'  @endphp
            @else 
            @php  $checked_no = ''  @endphp
            @endif
            <input class="form-check-input" type="radio" name="chrMenuDisplay" id="chrMenuDisplay1" value="N" {{ $checked_no }}>

            @if (Request::segment(2)=='users') 
            <label class="form-check-label" for="chrMenuDisplay1"> <span class="check"></span> <span class="box"></span> {{ trans('template.common.deactivate') }}  </label>
            @else
            <label class="form-check-label" for="chrMenuDisplay1"> <span class="check"></span> <span class="box"></span> {{ trans('template.common.unpublish') }} </label>
            @endif
        </div>

        @if (Request::segment(2)!='contact-info' && Request::segment(2)!='users' && Request::segment(2)!='organizations' && Request::segment(2)!='department' && Request::segment(2)!='maintenance' && Request::segment(2)!='tag' && Request::segment(2)!='page_template' && Request::segment(2)!='popup') 
            @if (Config::get('Constant.DEFAULT_DRAFT') == 'Y')
                <div class="form-check form-check-inline">
                    @if ((isset($display) && $display == 'D') || (old('chrDraft') == 'D'))
                        @php $checked_D = 'checked' @endphp
                    @else
                        @php  $checked_D = ''  @endphp
                    @endif
                    <input class="form-check-input" type="radio" name="chrMenuDisplay" id="chrDraft" value="D" {{ $checked_D }}>
                    <label class="form-check-label" for="chrDraft"> <span class="check"></span> <span class="box"></span> Save as Draft </label>
                </div>
            @endif
        @endif
        <span class="help-block">
            <strong>{{ $errors->first('chrMenuDisplay') }}</strong>
        </span>
        <div id="frmmail_membership_error">
        </div>
    </div>
</div>   