 
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

<!--  <label class="control-label form_title">{{ trans('template.display') }} <span aria-required="true" class="required"> * </span> </label> -->
    <label class="form_title {{ $Class_chrPublish }} {!! $Class_metatitle !!}" for="site_name">Status <span aria-required="true" class="required"> * </span></label>
    <div class="md-radio-inline">
        <div class="md-radio">
            @if ((isset($display) && $display == 'Y') || (null == old('chrMenuDisplay') || old('chrMenuDisplay') == 'Y'))
            @php  $checked_yes = 'checked'  @endphp
            @else
            @php  $checked_yes = ''  @endphp
            @endif     
            <input class="md-radiobtn" type="radio" value="Y" name="chrMenuDisplay" id="chrMenuDisplay0" {{ $checked_yes }}> 

            @if (Request::segment(2)=='users') 
            <label for="chrMenuDisplay0"> <span></span> <span class="check"></span> <span class="box"></span> {{ trans('template.common.activate') }}  </label>
            @else
            <label for="chrMenuDisplay0"> <span></span> <span class="check"></span> <span class="box"></span> {{ trans('template.common.publish') }} </label>
            @endif

        </div>
        <div class="md-radio">               
            @if ((isset($display) && $display == 'N') || (old('chrMenuDisplay') == 'N'))
            @php  $checked_no = 'checked'  @endphp
            @else 
            @php  $checked_no = ''  @endphp
            @endif     
            <input class="md-radiobtn" type="radio" value="N" name="chrMenuDisplay" id="chrMenuDisplay1" {{  $checked_no }}>

            @if (Request::segment(2)=='users') 
            <label for="chrMenuDisplay1"> <span></span> <span class="check"></span> <span class="box"></span>  {{ trans('template.common.deactivate') }} </label>
            @else
            <label for="chrMenuDisplay1"> <span></span> <span class="check"></span> <span class="box"></span> {{ trans('template.common.unpublish') }} </label>
            @endif

        </div>
        @if (Request::segment(2)!='users' && Request::segment(2)!='organizations' && Request::segment(2)!='department' && Request::segment(2)!='onlinepollingcategory' && Request::segment(2)!='onlinepolling' && Request::segment(2)!='maintenance' && Request::segment(2)!='tag' && Request::segment(2)!='page_template') 
        @if (Config::get('Constant.DEFAULT_DRAFT') == 'Y')
        <div class="md-radio">               
            @if ((isset($display) && $display == 'D') || (old('chrDraft') == 'D'))
            @php $checked_D = 'checked' @endphp
            @else
            @php  $checked_D = ''  @endphp
            @endif
            <input class="md-radiobtn" type="radio" value="D" name="chrMenuDisplay" id="chrDraft" {{  $checked_D }}>
            <label for="chrDraft"> <span></span> <span class="check"></span> <span class="box"></span> Save as Draft </label>
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