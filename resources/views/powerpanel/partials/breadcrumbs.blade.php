<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0"></h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('powerpanel') }}">{{ trans('template.common.home') }}</a></li>

                    @if(isset($breadcrumb['url']))
                        <li class="breadcrumb-item"><a href="{{ url($breadcrumb['url']) }}">{{ $breadcrumb['module'] }}</a></li>
                    @endif
                    
                    @if(isset($breadcrumb['inner_title']))
                        <li class="breadcrumb-item active">{{ $breadcrumb['inner_title'] }}</li>
                    @else
                        <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>