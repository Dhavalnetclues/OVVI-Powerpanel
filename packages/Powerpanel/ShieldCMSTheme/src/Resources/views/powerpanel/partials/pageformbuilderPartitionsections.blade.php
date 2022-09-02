@if(is_array($sections))
    <div class="col-md-12 col-xs-small "><span><i class="fa fa-file-text-o" aria-hidden="true"></i></span> {!! $sections[1] !!}</div>
    <input id="{{ 'item-form' }}" data-class="{{ $sections[1] }}" data-id="{{ $sections[0] }}" type="hidden" class="txtip formclass" value="{{ $sections[1] }}"/>
    <div class="clearfix"></div>
@endif 
