@php
$blogurl = '';
@endphp
@if(isset($data['blogs']) && !empty($data['blogs']) && count($data['blogs']) > 0)
@php
    $cols = 'col-md-4 col-sm-4 col-xs-12';
    $grid = '3';

    if($data['cols'] == 'grid_2_col'){
        $cols = 'col-xl-6 col-lg-6 col-md-6';
        $grid = '2';
    }elseif ($data['cols'] == 'grid_3_col') {
        $cols = 'col-xl-4 col-lg-4 col-md-6';
        $grid = '3';
    }elseif ($data['cols'] == 'grid_4_col') {
        $cols = 'col-xl-3 col-lg-3 col-md-3';
        $grid = '4';
    }

    if(isset($data['class'])){
        $class = $data['class'];
    }

    if(isset($data['paginatehrml']) && $data['paginatehrml'] == true){
        $pcol = $cols;
    }else{
        $pcol = 'item';
    }
@endphp

@if(Request::segment(1) == '')
<section class="blog_sec owl-section {{ $class }} " data-aos="fade-left" data-grid="{{ $grid }}">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 animated fadeInUp">
                <div class="same_title text-center">
                    @if(isset($data['title']) && $data['title'] != '')
                    <h2 class="title_div">{{ $data['title'] }}</h2>
                    @endif
                    @if(isset($data['desc']) && $data['desc'] != '')
                    <p>{!! $data['desc'] !!}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="blog_slide">
            <div class="row">
                @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
                <div class="col-12">
                    <div class="owl-carousel owl-theme owl-nav-absolute">
                @endif
                    @foreach($data['blogs'] as $blog)
                        @php
                        if(isset(App\Helpers\MyLibrary::getFront_Uri('blogs')['uri'])){
                        $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('blogs')['uri'];
                        $moduleFrontWithCatUrl = ($blog->varAlias != false ) ? $moduelFrontPageUrl . '/' . $blog->varAlias : $moduelFrontPageUrl;
                        // dd($blog);
                        // $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('blogs',$blog->intFkCategory);
                        $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$blog->alias->varAlias;
                        }else{
                        $recordLinkUrl = '';
                        }
                        @endphp
                        @if(isset($blog->fkIntImgId))
                        @php
                        $itemImg = App\Helpers\resize_image::resize($blog->fkIntImgId);
                        @endphp
                        @else
                        @php
                        $itemImg = $CDN_PATH.'assets/images/blog-img1.jpg';
                        @endphp
                        @endif

                        @if(isset($blog->custom['description']))
                        @php
                        $description = $blog->custom['description'];
                        @endphp
                        @else
                        @php
                        $description = $blog->varShortDescription;
                        @endphp
                        @endif
                        @if($data['cols'] == 'list')
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class=" listing clearfix">
                                @if(isset($blog->fkIntImgId) && $blog->fkIntImgId != '')
                                <div class="blog_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $blog->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $blog->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($blog->dtDateTime) && $blog->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($blog->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $blog->varTitle }}" alt="{{ $blog->varTitle }}">{{ $blog->varTitle }}</a></h5>
                                    @if(isset($description) && $description != '')
                                    <p>{!! (strlen($description) > 150) ? substr($description, 0, 150).'...' : $description !!}</p>
                                    @endif
                                    <a class="btn ac-border " href="{{ $recordLinkUrl }}" title="Read More">Read More</a>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="{{ $pcol }}">
                            <div class="blog_post">
                                @if(isset($blog->fkIntImgId) && $blog->fkIntImgId != '')
                                <div class="blog_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $blog->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $blog->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($blog->dtDateTime) && $blog->dtDateTime != '')
                                        <div class="date">{{ date('M d, Y',strtotime($blog->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $blog->varTitle }}" alt="{{ $blog->varTitle }}">{{ $blog->varTitle }}</a></h5>
                                    @if(isset($description) && $description != '')
                                    <p>{!! (strlen($description) > 95) ? substr($description, 0, 95).'...' : $description !!}</p>
                                    @endif
                                    <a class="btn ac-border " href="{{ $recordLinkUrl }}" title="Read More">Read More</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                    @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
                    </div>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12 text-center">
                    <a class="btn ac-border btn-more" href="{!! $blogurl !!}" title="View All">View All</a>
                </div>
            </div>
        </div>
    </div>
</section>
@else
<section class="inner-page-gap">
    <div class="container">
        @if(isset($data['desc']) && $data['desc'] != '')
        <div class="row">
            <div class="col-sm-12 col-xs-12 n-mb-20">
                <p>{!! $data['desc'] !!}</p>
            </div>
        </div>
        @endif
        <div class="{{ $class }}" data-grid="{{ $grid }}">
            <div class="row">
                @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
                <!-- <div class="col-12"> -->
                    <!-- <div class="owl-carousel owl-theme owl-nav-absolute">  -->
                        @endif
                        @foreach($data['blogs'] as $blog)
                        @php
                        if(isset(App\Helpers\MyLibrary::getFront_Uri('blogs')['uri'])){
                        $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('blogs')['uri'];
                        $moduleFrontWithCatUrl = ($blog->varAlias != false ) ? $moduelFrontPageUrl . '/' . $blog->varAlias : $moduelFrontPageUrl;
                        $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('blogs',$blog->intFkCategory);
                        $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$blog->alias->varAlias;
                        }else{
                        $recordLinkUrl = '';
                        }
                        @endphp
                        @if(isset($blog->fkIntImgId))
                        @php
                        $itemImg = App\Helpers\resize_image::resize($blog->fkIntImgId);
                        @endphp
                        @else
                        @php
                        $itemImg = $CDN_PATH.'assets/images/blog-img1.jpg';
                        @endphp
                        @endif

                        @if(isset($blog->custom['description']))
                        @php
                        $description = $blog->custom['description'];
                        @endphp
                        @else
                        @php
                        $description = $blog->varShortDescription;
                        @endphp
                        @endif
                        @if($data['cols'] == 'list')
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="listing clearfix">
                                @if(isset($blog->fkIntImgId) && $blog->fkIntImgId != '')
                                <div class="blog_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $blog->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $blog->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($blog->dtDateTime) && $blog->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($blog->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $blog->varTitle }}" alt="{{ $blog->varTitle }}">{{ $blog->varTitle }}</a></h5>
                                    @if(isset($description) && $description != '')
                                    <p>{!! (strlen($description) > 150) ? substr($description, 0, 150).'...' : $description !!}</p>
                                    @endif
                                    <a class="btn ac-border " href="{{ $recordLinkUrl }}" title="Read More">Read More</a>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="{{ $pcol }} col-lg-4 col-md-6 bloglisting-page">
                            <div class="blog_post">
                                @if(isset($blog->fkIntImgId) && $blog->fkIntImgId != '')
                                <div class="blog_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $blog->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $blog->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($blog->dtDateTime) && $blog->dtDateTime != '')
                                    <div class="date">{{ date('M d, Y',strtotime($blog->dtDateTime)) }}</div>
                                    @endif
                                    <h2 class="sub_title text-truncate"><a href="{{ $recordLinkUrl }}" title="{{ $blog->varTitle }}" alt="{{ $blog->varTitle }}">{{ $blog->varTitle }}</a></h2>
                                    @if(isset($description) && $description != '')
                                    <p>{!! (strlen($description) > 95) ? substr($description, 0, 95).'...' : $description !!}</p>
                                    @endif
                                    <a href="{{ $recordLinkUrl }}" class="btn ac-border" title="Read More">Read More <i class="fa fa-arrow-right" aria-hidden="true"></i></a>                                    
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
                    <!-- </div> -->
                <!-- </div> -->
                @endif
            </div>
            @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
            @if($data['blogs']->total() > $data['blogs']->perPage())
            <div class="row">
                <div class="col-sm-12 n-mt-30 text-center">
                    {{ $data['blogs']->links() }}
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</section>
@endif


{{-- <section class="inner-page-gap blog_listing">
   <div class="container">
      <div class="bloglisting_post">
         <div class="row">
            <div class="col-lg-4 col-sm-6 blog_list_sec">
               <div class="bloglisting-card">
                  <div class="blog-img"><a href="blog-details.html"><img src="http://localhost/powerpanel-demo-2022/public_html/cdn/assets/images/2022-07-07-06-14-16-blog3.jpg" alt="blog" class="blog-fixed"> </a> </div>
                  <div class="blog-info">
                     <div class="blog-quick-inf">
                     <div class="date">Wednesday 13 Jul, 2022</div>   
                     <h4><a href="#">Finding the best social media Blog</a>
                     </h4>
                     <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem...</p>
                     <div class="more-btn">
                     <a href="javascript:void(0)" class="link" title="Read More">Read More <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                     </div>
                      </div> 
                     
                  </div>
               </div>
            </div>
            <div class="col-lg-4 col-sm-6 blog_list_sec">
               <div class="bloglisting-card">
                  <div class="blog-img"><a href="blog-details.html"><img src="http://localhost/powerpanel-demo-2022/public_html/cdn/assets/images/2022-07-06-08-14-33-blog1.jpg" alt="blog" class="blog-fixed"> </a> </div>
                  <div class="blog-info">
                     <div class="blog-quick-inf">
                     <div class="date">Wednesday 13 Jul, 2022</div>   
                     <h4><a href="#">Finding the best social media Blog</a>
                     </h4>
                     <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem...</p>
                     <div class="more-btn">
                     <a href="javascript:void(0)" class="link" title="Read More">Read More <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                     </div>
                     </div> 
                     
                  </div>
               </div>
            </div>
            <div class="col-lg-4 col-sm-6 blog_list_sec">
               <div class="bloglisting-card">
                  <div class="blog-img"><a href="blog-details.html"><img src="http://localhost/powerpanel-demo-2022/public_html/cdn/assets/images/2022-07-07-06-14-16-blog3.jpg" alt="blog" class="blog-fixed"> </a> </div>
                  <div class="blog-info">
                     <div class="blog-quick-inf">
                     <div class="date">Wednesday 13 Jul, 2022</div> 
                     <h4><a href="blog-details.html">Finding the best social media Blog</a>
                     </h4>
                     <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem...</p>
                     <div class="more-btn">
                     <a href="javascript:void(0)" class="link" title="Read More">Read More <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                     </div> 
                     </div>
                    
                  </div>
               </div>
            </div>
			<div class="col-lg-4 col-sm-6 blog_list_sec">
               <div class="bloglisting-card">
                  <div class="blog-img"><a href="blog-details.html"><img src="http://localhost/powerpanel-demo-2022/public_html/cdn/assets/images/2022-07-06-08-14-33-blog1.jpg" alt="blog" class="blog-fixed"> </a> </div>
                  <div class="blog-info">
                     <div class="blog-quick-inf">
                     <div class="date">Wednesday 13 Jul, 2022</div>
                     <h4><a href="#">Finding the best social media Blog</a>
                     </h4>
                     <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem...</p>
                     <div class="more-btn">
                     <a href="javascript:void(0)" class="link" title="Read More">Read More <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                     </div>
                    </div> 
                    
                  </div>
               </div>
            </div>
			<div class="col-lg-4 col-sm-6 blog_list_sec">
               <div class="bloglisting-card">
                  <div class="blog-img"><a href="blog-details.html"><img src="http://localhost/powerpanel-demo-2022/public_html/cdn/assets/images/2022-07-07-06-14-16-blog3.jpg" alt="blog" class="blog-fixed"> </a> </div>
                  <div class="blog-info">
                     <div class="blog-quick-inf">
                     <div class="date">Wednesday 13 Jul, 2022</div>
                     <h4><a href="#">Finding the best social media Blog</a>
                     </h4>
                     <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem...</p>
                     <div class="more-btn">
                     <a href="javascript:void(0)" class="link" title="Read More">Read More <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                     </div>
                    </div> 
                   
                  </div>
               </div>
            </div>
         
            <div class="col-lg-4 col-sm-6 blog_list_sec">
               <div class="bloglisting-card">
                  <div class="blog-img"><a href="blog-details.html"><img src="http://localhost/powerpanel-demo-2022/public_html/cdn/assets/images/2022-07-06-08-14-33-blog1.jpg" alt="blog" class="blog-fixed"> </a> </div>
                  <div class="blog-info">
                     <div class="blog-quick-inf">
                     <div class="date">Wednesday 13 Jul, 2022</div>
                     <h4><a href="#">Finding the best social media Blog</a>
                     </h4>
                     <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem...</p>
                     <div class="more-btn">
                     <a href="javascript:void(0)" class="link" title="Read More">Read More <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                     </div>
                     </div>
                     
                  </div>
               </div>
            </div>




            
      </div>
   </div>
</section> --}}


@endif