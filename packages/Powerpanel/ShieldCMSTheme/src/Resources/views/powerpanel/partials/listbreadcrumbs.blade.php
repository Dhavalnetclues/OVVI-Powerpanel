<div class="title_bar">
    <div class="page-head">
        <div class="page-title">
            <h1>{{ $ModuleName }} </h1>                        
        </div>   
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <span aria-hidden="true" class="icon-home"></span>
                <a href="{{ url('powerpanel') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li class="active">{{ $ModuleName }}</li>
        </ul>
        <a class="drop_toogle_arw" href="javascript:void(0);" data-toggle="collapse" data-target="#cmspage_id"><i class="la la-chevron-circle-up"></i></a>                                           
    </div>  
    <div class="add_category_button pull-right">
        <a title="Help" class="add_category" target="_blank" href="{{ $CDN_PATH.'assets/videos/Shield_CMS_WorkFlow.mp4'}}">
            <span title="Help">Help</span> <i class="la la-question-circle"></i>
        </a>
    </div>
</div>