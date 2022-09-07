
    <ul class="navbar-nav" id="navbar-nav">            		
        <!-- Back  -->
        <li id="main_menu" class="nav-item" style="display: none;">
            <a href="#" class="nav-link menu-link active" title="Back">
                <i class="ri-arrow-go-back-line"></i> <span data-key="t-widgets">Back</span>
            </a>
        </li>               

        <!-- Dashboard -->
        <li data-id="1" class="nav-sort nav-item">
            <a href="{{ url('powerpanel') }}" class="nav-link menu-link {{ $menuArr['dashboard_active'] }}" title="{{ trans('template.sidebar.dashboard') }}">
                <i class="ri-dashboard-2-line"></i> <span data-key="t-widgets">{{ trans('template.sidebar.dashboard') }}</span>
            </a>
        </li>

        <!-- Menu -->
        {{-- @if((isset($menuArr['can-menu-list']) && $menuArr['can-menu-list']))
            <li data-id="2" class="nav-sort nav-item">
                <a href="{{ url('powerpanel/menu') }}" class="nav-link menu-link {{ $menuArr['menu_active'] }}" title="{{ trans('template.sidebar.menu') }}">
                    <i class="ri-menu-line"></i> <span data-key="t-widgets">{{ trans('template.sidebar.menu') }}</span>
                </a>
            </li>
        @endif --}}

        <!-- Pages -->
       {{--  @if((isset($menuArr['can-pages-list']) && $menuArr['can-pages-list']) ||
            (isset($menuArr['can-page_template-list']) && $menuArr['can-page_template-list']) ||
            (isset($menuArr['can-banner-list']) && $menuArr['can-banner-list']) ||

            (isset($menuArr['can-news-category-list']) && $menuArr['can-news-category-list']) ||
            (isset($menuArr['can-news-list']) && $menuArr['can-news-list']) ||
            // (isset($menuArr['can-decision-list']) && $menuArr['can-decision-list']) ||
            // (isset($menuArr['can-decision-category-list']) && $menuArr['can-decision-category-list']) ||
            // (isset($menuArr['can-rfps-list']) && $menuArr['can-rfps-list']) ||
            (isset($menuArr['can-rfp-list']) && $menuArr['can-rfp-list']) ||
            // (isset($menuArr['can-links-category-list']) && $menuArr['can-links-category-list']) ||
            // (isset($menuArr['can-links-list']) && $menuArr['can-links-list']) ||
            (isset($menuArr['can-faq-category-list']) && $menuArr['can-faq-category-list']) ||
            (isset($menuArr['can-event-category-list']) && $menuArr['can-event-category-list']) ||
            (isset($menuArr['can-events-list']) && $menuArr['can-events-list']) ||
            (isset($menuArr['can-blog-category-list']) && $menuArr['can-blog-category-list']) ||
            (isset($menuArr['can-blogs-list']) && $menuArr['can-blogs-list']) ||
            (isset($menuArr['can-companies-list']) && $menuArr['can-companies-list']) ||
            (isset($menuArr['can-faq-list']) && $menuArr['can-faq-list']) ||
            (isset($menuArr['can-career-category-list']) && $menuArr['can-career-category-list']) ||
            (isset($menuArr['can-careers-list']) && $menuArr['can-careers-list']) ||
            (isset($menuArr['can-netcareers-list']) && $menuArr['can-netcareers-list']) ||
            // (isset($menuArr['can-complaint-services-list']) && $menuArr['can-complaint-services-list']) ||
            // (isset($menuArr['can-boardofdirectors-list']) && $menuArr['can-boardofdirectors-list']) ||
            // (isset($menuArr['can-register-application-list']) && $menuArr['can-register-application-list']) ||
            // (isset($menuArr['can-licence-register-list']) && $menuArr['can-licence-register-list']) ||
            // (isset($menuArr['can-forms-and-fees-list']) && $menuArr['can-forms-and-fees-list']) ||
            (isset($menuArr['can-popup-list']) && $menuArr['can-popup-list']) ||
            // (isset($menuArr['can-candwservice-list']) && $menuArr['can-candwservice-list']) ||
            (isset($menuArr['can-service-list']) && $menuArr['can-service-list']) ||
            (isset($menuArr['can-service-category-list']) && $menuArr['can-service-category-list']) ||
            // (isset($menuArr['can-public-record-category-list']) && $menuArr['can-public-record-category-list']) ||
            // (isset($menuArr['can-public-record-list']) && $menuArr['can-public-record-list']) ||
            // (isset($menuArr['can-organizations-list']) && $menuArr['can-organizations-list']) ||
            // (isset($menuArr['can-department-list']) && $menuArr['can-department-list']) ||
            // (isset($menuArr['can-interconnections-list']) && $menuArr['can-interconnections-list']) ||
            // (isset($menuArr['can-number-allocation']) && $menuArr['can-number-allocation']) ||
            // (isset($menuArr['can-online-polling-list']) && $menuArr['can-online-polling-list']) ||

            (isset($menuArr['can-tag-list']) && $menuArr['can-tag-list']) ||
            (isset($menuArr['can-maintenance-list']) && $menuArr['can-maintenance-list']) ||
            // (isset($menuArr['can-quick-links-list']) && $menuArr['can-quick-links-list']) ||
            (isset($menuArr['can-publications-category-list']) && $menuArr['can-publications-category-list']) ||
            (isset($menuArr['can-photo-album-category-list']) && $menuArr['can-photo-album-category-list']) ||
            (isset($menuArr['can-photo-album-list']) && $menuArr['can-photo-album-list']) ||
            (isset($menuArr['can-publications-list']) && $menuArr['can-publications-list']) ||
            (isset($menuArr['can-video-gallery-list']) && $menuArr['can-video-gallery-list']) || 
            (isset($menuArr['can-testimonial-list']) && $menuArr['can-testimonial-list']) || (isset($menuArr['can-advertise-list']) && $menuArr['can-advertise-list']) ||
            (isset($menuArr['can-show-category-list']) && $menuArr['can-show-category-list']) ||
            (isset($menuArr['can-shows-list']) && $menuArr['can-shows-list']) ||
            (isset($menuArr['can-products-category-list']) && $menuArr['can-products-category-list']) ||
            (isset($menuArr['can-products-list']) && $menuArr['can-products-list']) ||
            (isset($menuArr['can-sponsor-list']) && $menuArr['can-sponsor-list']) ||
            (isset($menuArr['can-sponsor-category-list']) && $menuArr['can-sponsor-category-list']) ||
            (isset($menuArr['can-projects-category-list']) && $menuArr['can-projects-category-list']) || 
            (isset($menuArr['can-projects-list']) && $menuArr['can-projects-list']) ||
            (isset($menuArr['can-clients-category-list']) && $menuArr['can-clients-category-list']) ||
            (isset($menuArr['can-client-list']) && $menuArr['can-client-list']) ||
            (isset($menuArr['can-team-list']) && $menuArr['can-team-list']) ||
            (isset($menuArr['can-formbuilder-list']) && $menuArr['can-formbuilder-list'])
            )
            <li class="nav-item">
                <a class="nav-link menu-link" href="#pagemenu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ ((isset($menuArr['pagemenu']) && $menuArr['pagemenu'] == 'active') || (isset($menuArr['contmg']) && $menuArr['contmg'] == 'active')) ? 'true' : 'false' }}" aria-controls="pagemenu" title="{{ trans('template.sidebar.pages') }}">
                    <i class="ri-pages-line"></i> <span data-key="t-widgets">{{ trans('template.sidebar.pages') }}</span>
                </a>
                <div class="collapse menu-dropdown {{ ((isset($menuArr['pagemenu']) && $menuArr['pagemenu'] == 'active') || (isset($menuArr['contmg']) && $menuArr['contmg'] == 'active')) ? 'show' : '' }}" id="pagemenu">
                    <ul class="nav nav-sm flex-column">
                        <!-- Pages -->
                        @if(isset($menuArr['can-pages-list']) && $menuArr['can-banner-list'])
                            <li data-id="3" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/pages') }}" class="nav-link {{ $menuArr['page_active'] }}" data-key="t-starter" title="{{ trans('template.sidebar.pages') }}">
                                    <i class="ri-pages-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.pages') }}</span>
                                </a>
                            </li>
                        @endif

                        <!-- Page Template -->
                        @if(isset($menuArr['can-page_template-list']) && $menuArr['can-page_template-list'])
                            <li data-id="4" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/page_template') }}" class="nav-link {{ $menuArr['page_template_active'] }}" data-key="t-starter" title="{{ trans('template.sidebar.pagetemplate') }}">
                                    <i class="ri-file-list-2-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.pagetemplate') }}</span>
                                </a>
                            </li>
                        @endif

                        <!-- Banners -->
                        @if(isset($menuArr['can-banner-list']) && $menuArr['can-banner-list'])
                            <li data-id="5" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/banners') }}" class="nav-link {{ $menuArr['banner_active'] }}" data-key="t-starter" title="{{ trans('template.sidebar.banner') }}">
                                    <i class="ri-image-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.banner') }}</span>
                                </a>
                            </li>
                        @endif

                        <!-- Modules -->
                        @if(
                            (isset($menuArr['can-news-category-list']) && $menuArr['can-news-category-list']) ||
                            (isset($menuArr['can-news-list']) && $menuArr['can-news-list']) ||
                            // (isset($menuArr['can-decision-list']) && $menuArr['can-decision-list']) ||
                            // (isset($menuArr['can-decision-category-list']) && $menuArr['can-decision-category-list']) ||
                            // (isset($menuArr['can-rfps-list']) && $menuArr['can-rfps-list']) ||
                            (isset($menuArr['can-rfp-list']) && $menuArr['can-rfp-list']) ||
                            // (isset($menuArr['can-links-category-list']) && $menuArr['can-links-category-list']) ||
                            // (isset($menuArr['can-links-list']) && $menuArr['can-links-list']) ||
                            (isset($menuArr['can-faq-category-list']) && $menuArr['can-faq-category-list']) ||
                            (isset($menuArr['can-event-category-list']) && $menuArr['can-event-category-list']) ||
                            (isset($menuArr['can-events-list']) && $menuArr['can-events-list']) ||
                            (isset($menuArr['can-blog-category-list']) && $menuArr['can-blog-category-list']) ||
                            (isset($menuArr['can-blogs-list']) && $menuArr['can-blogs-list']) ||
                            // (isset($menuArr['can-companies-list']) && $menuArr['can-companies-list']) ||
                            (isset($menuArr['can-faq-list']) && $menuArr['can-faq-list']) ||
                            (isset($menuArr['can-career-category-list']) && $menuArr['can-career-category-list']) ||
                            (isset($menuArr['can-careers-list']) && $menuArr['can-careers-list']) ||
                            (isset($menuArr['can-netcareers-list']) && $menuArr['can-netcareers-list']) ||
                            // (isset($menuArr['can-complaint-services-list']) && $menuArr['can-complaint-services-list']) ||
                            // (isset($menuArr['can-boardofdirectors-list']) && $menuArr['can-boardofdirectors-list']) ||
                            // (isset($menuArr['can-register-application-list']) && $menuArr['can-register-application-list']) ||
                            // (isset($menuArr['can-licence-register-list']) && $menuArr['can-licence-register-list']) ||
                            // (isset($menuArr['can-forms-and-fees-list']) && $menuArr['can-forms-and-fees-list']) ||
                            (isset($menuArr['can-popup-list']) && $menuArr['can-popup-list']) ||
                            // (isset($menuArr['can-candwservice-list']) && $menuArr['can-candwservice-list']) ||
                            (isset($menuArr['can-service-list']) && $menuArr['can-service-list']) ||
                            (isset($menuArr['can-service-category-list']) && $menuArr['can-service-category-list']) ||
                            // (isset($menuArr['can-public-record-category-list']) && $menuArr['can-public-record-category-list']) ||
                            // (isset($menuArr['can-public-record-list']) && $menuArr['can-public-record-list']) ||
                            // (isset($menuArr['can-organizations-list']) && $menuArr['can-organizations-list']) ||
                            // (isset($menuArr['can-department-list']) && $menuArr['can-department-list']) ||
                            // (isset($menuArr['can-interconnections-list']) && $menuArr['can-interconnections-list']) ||
                            // (isset($menuArr['can-number-allocation']) && $menuArr['can-number-allocation']) ||
                            // (isset($menuArr['can-online-polling-list']) && $menuArr['can-online-polling-list']) ||

                            (isset($menuArr['can-tag-list']) && $menuArr['can-tag-list']) ||
                            (isset($menuArr['can-maintenance-list']) && $menuArr['can-maintenance-list']) ||
                            // (isset($menuArr['can-quick-links-list']) && $menuArr['can-quick-links-list']) ||
                            (isset($menuArr['can-publications-category-list']) && $menuArr['can-publications-category-list']) ||
                            (isset($menuArr['can-photo-album-category-list']) && $menuArr['can-photo-album-category-list']) ||
                            (isset($menuArr['can-photo-album-list']) && $menuArr['can-photo-album-list']) ||
                            (isset($menuArr['can-publications-list']) && $menuArr['can-publications-list']) ||
                            (isset($menuArr['can-video-gallery-list']) && $menuArr['can-video-gallery-list']) || 
                            (isset($menuArr['can-testimonial-list']) && $menuArr['can-testimonial-list']) || (isset($menuArr['can-advertise-list']) && $menuArr['can-advertise-list']) ||
                            (isset($menuArr['can-show-category-list']) && $menuArr['can-show-category-list']) ||
                            (isset($menuArr['can-shows-list']) && $menuArr['can-shows-list']) ||
                            (isset($menuArr['can-products-category-list']) && $menuArr['can-products-category-list']) ||
                            (isset($menuArr['can-products-list']) && $menuArr['can-products-list']) ||
                            (isset($menuArr['can-sponsor-list']) && $menuArr['can-sponsor-list']) ||
                            (isset($menuArr['can-sponsor-category-list']) && $menuArr['can-sponsor-category-list']) ||
                            (isset($menuArr['can-projects-category-list']) && $menuArr['can-projects-category-list']) || 
                            (isset($menuArr['can-projects-list']) && $menuArr['can-projects-list']) ||
                            (isset($menuArr['can-clients-category-list']) && $menuArr['can-clients-category-list']) ||
                            (isset($menuArr['can-client-list']) && $menuArr['can-client-list']) ||
                            (isset($menuArr['can-team-list']) && $menuArr['can-team-list']) ||
                            (isset($menuArr['can-formbuilder-list']) && $menuArr['can-formbuilder-list']))

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#contmg" data-bs-toggle="collapse" role="button"
                                    aria-expanded="{{ (isset($menuArr['contmg']) && $menuArr['contmg'] == 'active') ? 'true' : 'false' }}" aria-controls="contmg" title="Contents">
                                    <i class="ri-sound-module-line d-none"></i> <span data-key="t-sitemanagement">Contents</span>
                                </a>
                                <div class="collapse menu-dropdown {{ (isset($menuArr['contmg']) && $menuArr['contmg'] == 'active') ? 'show' : '' }}" id="contmg">
                                    <ul class="nav nav-sm flex-column">
                                        <!-- Blogs -->
                                        @if((isset($menuArr['can-blog-category-list']) && $menuArr['can-blog-category-list']) || (isset($menuArr['can-blogs-list']) && $menuArr['can-blogs-list']))
                                            @if((isset($menuArr['can-blog-category-list'])) && (isset($menuArr['can-blogs-list'])) )
                                            <li data-id="6" class="nav-sort nav-item">
                                                <a href="#blog" class="nav-link" data-bs-toggle="collapse" role="button"
                                                    aria-expanded="{{ (isset($menuArr['blogmg']) && $menuArr['blogmg']=='active')? 'true' : 'false' }}" aria-controls="blog" data-key="t-blog" title="{{ trans('template.sidebar.blog') }}">
                                                    <i class="ri-article-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.blog') }}</span>
                                                </a>
                                                
                                                <div class="collapse menu-dropdown {{ (isset($menuArr['blogmg']) && $menuArr['blogmg']=='active')? 'show' : '' }}" id="blog">
                                                    <ul class="nav nav-sm flex-column">
                                                        @if(isset($menuArr['can-blog-category-list']) && $menuArr['can-blog-category-list'])
                                                        <li data-id="7" class="nav-sort nav-item">
                                                            <a href="{{ url('powerpanel/blog-category') }}" class="nav-link {{ $menuArr['blogcategory_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.blogcategory') }}">
                                                                <i class="ri-file-list-3-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.blogcategory') }}</span>
                                                            </a>
                                                        </li>
                                                        @endif

                                                        @if(isset($menuArr['can-blogs-list']) && $menuArr['can-blogs-list'])
                                                        <li data-id="8" class="nav-sort nav-item">
                                                            <a href="{{ url('powerpanel/blogs') }}" class="nav-link {{ $menuArr['blogs_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.blog') }}">
                                                                <i class="ri-article-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.blog') }}</span>
                                                            </a>
                                                        </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </li>
                                            @endif
                                        @endif

                                        <!-- News -->
                                        @if((isset($menuArr['can-news-category-list']) && $menuArr['can-news-category-list']) || (isset($menuArr['can-news-list']) && $menuArr['can-news-list']))
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-news-category-list'])) && (isset($menuArr['can-news-list'])) )
                                                    <a href="#news" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['newsmg']) && $menuArr['newsmg']=='active')? 'true' : 'false' }}" aria-controls="news" data-key="t-profile" title="{{ trans('template.sidebar.news') }}"> <i class="ri-newspaper-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.news') }}</span>
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['newsmg']) && $menuArr['newsmg']=='active')? 'show' : '' }}" id="news">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-news-category-list']) && $menuArr['can-news-category-list'])
                                                            <li data-id="9" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/news-category') }}" class="nav-link {{ $menuArr['news_category_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.newscategory') }}">
                                                                    <i class="ri-survey-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.newscategory') }}</span>
                                                                </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-news-list']) && $menuArr['can-news-list'])
                                                            <li data-id="10" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/news') }}" class="nav-link {{ $menuArr['news_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.news') }}">
                                                                    <i class="ri-newspaper-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.news') }}</span>
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-news-category-list'])))
                                                    @if(isset($menuArr['can-news-category-list']) && $menuArr['can-news-category-list'])
                                                        <a href="{{ url('powerpanel/news-category') }}" class="nav-link {{ $menuArr['news_category_active'] }}" title="{{ trans('template.sidebar.newscategory') }}">
                                                            <i class="ri-survey-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.newscategory') }}</span>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-news-list']) && $menuArr['can-news-list'])
                                                        <a href="{{ url('powerpanel/news') }}" class="nav-link {{ $menuArr['news_active'] }}" title="{{ trans('template.sidebar.news') }}">
                                                            <i class="ri-newspaper-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.news') }}</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif

                                        <!-- Events -->
                                        @if((isset($menuArr['can-event-category-list']) && $menuArr['can-event-category-list']) || (isset($menuArr['can-events-list']) && $menuArr['can-events-list']))
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-event-category-list'])) && (isset($menuArr['can-events-list'])) )
                                                    <a href="#eventmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['eventmg']) && $menuArr['eventmg']=='active')? 'true' : 'false' }}" aria-controls="eventmg" data-key="t-profile" title="Events"> <i class="ri-calendar-event-line d-none"></i> <span data-key="t-widgets">Events</span>
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['eventmg']) && $menuArr['eventmg']=='active')? 'show' : '' }}" id="eventmg">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-event-category-list']) && $menuArr['can-event-category-list'])
                                                            <li data-id="11" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/event-category') }}" class="nav-link {{ $menuArr['eventcategory_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.eventcategory') }}"> <i class="ri-calendar-todo-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.eventcategory') }}</span> </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-events-list']) && $menuArr['can-events-list'])
                                                            <li data-id="12" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/events') }}" class="nav-link {{ $menuArr['events_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.events') }}">
                                                                    <i class="ri-calendar-event-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.events') }}</span>
                                                                </a>
                                                            </li>
                                                            @endif
                                                            
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-event-category-list'])))
                                                    @if(isset($menuArr['can-event-category-list']) && $menuArr['can-event-category-list'])
                                                        <a href="{{ url('powerpanel/event-category') }}" class="nav-link {{ $menuArr['eventcategory_active'] }}" title="{{ trans('template.sidebar.eventcategory') }}">
                                                            <i class="ri-calendar-todo-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.eventcategory') }}</span>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-events-list']) && $menuArr['can-events-list'])
                                                        <a href="{{ url('powerpanel/events') }}" class="nav-link {{ $menuArr['events_active'] }}" title="{{ trans('template.sidebar.events') }}">
                                                            <i class="ri-calendar-event-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.events') }}</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif

                                        <!-- Service -->
                                        @if((isset($menuArr['can-service-category-list']) && $menuArr['can-service-category-list']) || (isset($menuArr['can-service-list']) && $menuArr['can-service-list']))
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-service-category-list'])) && (isset($menuArr['can-service-list'])) )
                                                    <a href="#sertmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['sertmg']) && $menuArr['sertmg']=='active')? 'true' : 'false' }}" aria-controls="sertmg" data-key="t-profile" href="Services"> <i class="ri-settings-3-line d-none"></i> <span data-key="t-widgets">Services</span>
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['sertmg']) && $menuArr['sertmg']=='active')? 'show' : '' }}" id="sertmg">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-service-category-list']) && $menuArr['can-service-category-list'])
                                                            <li data-id="13" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/service-category') }}" class="nav-link {{ $menuArr['service_category_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.servicescategory') }}"> <i class="ri-list-settings-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.servicescategory') }}</span> </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-service-list']) && $menuArr['can-service-list'])
                                                            <li data-id="14" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/service') }}" class="nav-link {{ $menuArr['service_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.service') }}">
                                                                    <i class="ri-settings-3-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.service') }}</span>
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-service-category-list'])))
                                                    @if(isset($menuArr['can-service-category-list']) && $menuArr['can-service-category-list'])
                                                        <a href="{{ url('powerpanel/service-category') }}" class="nav-link {{ $menuArr['service_category_active'] }}" title="{{ trans('template.sidebar.servicescategory') }}">
                                                            <i class="ri-list-settings-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.servicescategory') }}</span>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-service-list']) && $menuArr['can-service-list'])
                                                        <a href="{{ url('powerpanel/service') }}" class="nav-link {{ $menuArr['service_active'] }}" title="{{ trans('template.sidebar.service') }}">
                                                            <i class="ri-settings-3-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.service') }}</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif
                                        
                                        <!-- Links -->
                                        @if((isset($menuArr['can-quick-links-list']) && $menuArr['can-quick-links-list']) || (isset($menuArr['can-links-category-list']) && $menuArr['can-links-category-list']) || (isset($menuArr['can-links-list']) && $menuArr['can-links-list']))
                                            <li data-id="15" class="nav-sort nav-item">
                                                <a href="#linkmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                    aria-expanded="{{ (isset($menuArr['linkmg']) && $menuArr['linkmg']=='active')? 'true' : 'false' }}" aria-controls="linkmg" data-key="t-profile" href="Links"> <i class="ri-links-line d-none"></i> <span data-key="t-widgets">Links</span>
                                                </a>
                                                <div class="collapse menu-dropdown {{ (isset($menuArr['linkmg']) && $menuArr['linkmg']=='active')? 'show' : '' }}" id="linkmg">
                                                    <ul class="nav nav-sm flex-column">
                                                        @if(isset($menuArr['can-quick-links-list']) && $menuArr['can-quick-links-list'])
                                                        <li data-id="16" class="nav-sort nav-item">
                                                            <a href="{{ url('powerpanel/quick-links') }}" class="nav-link {{ $menuArr['quicklinks_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.quicklinks') }}">
                                                                <i class="ri-external-link-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.quicklinks') }}</span>
                                                            </a>
                                                        </li>
                                                        @endif
                                                        @if(isset($menuArr['can-links-category-list']) && $menuArr['can-links-category-list'])
                                                        <li data-id="17" class="nav-sort nav-item">
                                                            <a href="{{ url('powerpanel/links-category') }}" class="nav-link {{ $menuArr['linkscategory_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.linkscategory') }}">
                                                                <i class="ri-link-unlink-m d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.linkscategory') }}</span>
                                                            </a>
                                                        </li>
                                                        @endif
                                                        @if(isset($menuArr['can-links-list']) && $menuArr['can-links-list'])
                                                        <li data-id="18" class="nav-sort nav-item">
                                                            <a href="{{ url('powerpanel/links') }}" class="nav-link {{ $menuArr['links_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.links') }}">
                                                                <i class="ri-links-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.links') }}</span>
                                                            </a>
                                                        </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </li>
                                        @endif

                                        <!-- FAQ -->
                                        @if((isset($menuArr['can-faq-category-list']) && $menuArr['can-faq-category-list']) || (isset($menuArr['can-faq-list']) && $menuArr['can-faq-list']))
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-faq-category-list'])) && (isset($menuArr['can-faq-list'])) )
                                                    <a href="#faqmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['faqmg']) && $menuArr['faqmg']=='active')? 'true' : 'false' }}" aria-controls="faqmg" data-key="t-profile" title="FAQs"> <i class="ri-question-line d-none"></i> <span data-key="t-widgets">FAQs</span>
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['faqmg']) && $menuArr['faqmg']=='active')? 'show' : '' }}" id="faqmg">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-faq-category-list']) && $menuArr['can-faq-category-list'])
                                                            <li data-id="19" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/faq-category') }}" class="nav-link {{ $menuArr['faqcategory_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.faqcategory') }}"> <i class="ri-questionnaire-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.faqcategory') }}</span> </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-faq-list']) && $menuArr['can-faq-list'])
                                                            <li data-id="20" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/faq') }}" class="nav-link {{ $menuArr['faq_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.faq') }}">
                                                                    <i class="ri-question-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.faq') }}</span>
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-faq-category-list'])))
                                                    @if(isset($menuArr['can-faq-category-list']) && $menuArr['can-faq-category-list'])
                                                        <a href="{{ url('powerpanel/faq-category') }}" class="nav-link {{ $menuArr['faqcategory_active'] }}" title="{{ trans('template.sidebar.faqcategory') }}">
                                                            <i class="ri-questionnaire-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.faqcategory') }}</span>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-faq-list']) && $menuArr['can-faq-list'])
                                                        <a href="{{ url('powerpanel/faq') }}" class="nav-link {{ $menuArr['faq_active'] }}" title="{{ trans('template.sidebar.faq') }}">
                                                            <i class="ri-question-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.faq') }}</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif

                                        <!-- Careers -->
                                        @if((isset($menuArr['can-career-category-list']) && $menuArr['can-career-category-list']) || (isset($menuArr['can-careers-list']) && $menuArr['can-careers-list']))
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-career-category-list'])) && (isset($menuArr['can-careers-list'])) )
                                                    <a href="#careermg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['careermg']) && $menuArr['careermg']=='active')? 'true' : 'false' }}" aria-controls="careermg" data-key="t-profile" title="Careers"> <i class="ri-suitcase-line d-none"></i> <span data-key="t-widgets">Careers</span>
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['careermg']) && $menuArr['careermg']=='active')? 'show' : '' }}" id="careermg">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-career-category-list']) && $menuArr['can-career-category-list'])
                                                            <li data-id="21" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/career-category') }}" class="nav-link {{ $menuArr['careercategory_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.careercategory') }}"> <i class="ri-suitcase-3-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.careercategory') }}</span> </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-careers-list']) && $menuArr['can-careers-list'])
                                                            <li data-id="22" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/careers') }}" class="nav-link {{ $menuArr['careers_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.career') }}">
                                                                    <i class="ri-suitcase-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.career') }}</span>
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-career-category-list'])))
                                                    @if(isset($menuArr['can-career-category-list']) && $menuArr['can-career-category-list'])
                                                        <a href="{{ url('powerpanel/career-category') }}" class="nav-link {{ $menuArr['careercategory_active'] }}" title="{{ trans('template.sidebar.careercategory') }}">
                                                            <i class="ri-suitcase-3-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.careercategory') }}</span>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-careers-list']) && $menuArr['can-careers-list'])
                                                        <a href="{{ url('powerpanel/careers') }}" class="nav-link {{ $menuArr['careers_active'] }}" title="{{ trans('template.sidebar.career') }}">
                                                            <i class="ri-suitcase-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.career') }}</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif

                                        <!-- Publications -->
                                        @if((isset($menuArr['can-publications-category-list']) && $menuArr['can-publications-category-list']) || (isset($menuArr['can-publications-list']) && $menuArr['can-publications-list']))
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-publications-category-list'])) && (isset($menuArr['can-publications-list'])) )
                                                    <a href="#pubtmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['pubtmg']) && $menuArr['pubtmg']=='active')? 'true' : 'false' }}" aria-controls="pubtmg" data-key="t-profile" title="Publications"> <i class="ri-newspaper-line d-none"></i> <span data-key="t-widgets">Publications</span>
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['pubtmg']) && $menuArr['pubtmg']=='active')? 'show' : '' }}" id="pubtmg">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-publications-category-list']) && $menuArr['can-publications-category-list'])
                                                            <li data-id="23" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/publications-category') }}" class="nav-link {{ $menuArr['publications_category_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.publicationscategory') }}"> <i class="ri-survey-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.publicationscategory') }}</span> </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-publications-list']) && $menuArr['can-publications-list'])
                                                            <li data-id="24" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/publications') }}" class="nav-link {{ $menuArr['publications_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.publications') }}">
                                                                    <i class="ri-newspaper-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.publications') }}</span>
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-publications-category-list'])))
                                                    @if(isset($menuArr['can-publications-category-list']) && $menuArr['can-publications-category-list'])
                                                        <a href="{{ url('powerpanel/publications-category') }}" class="nav-link {{ $menuArr['publications_category_active'] }}" title="{{ trans('template.sidebar.publicationscategory') }}">
                                                            <i class="ri-survey-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.publicationscategory') }}</span>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-publications-list']) && $menuArr['can-publications-list'])
                                                        <a href="{{ url('powerpanel/publications') }}" class="nav-link {{ $menuArr['publications_active'] }}" title="{{ trans('template.sidebar.publications') }}">
                                                            <i class="ri-newspaper-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.publications') }}</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif

                                        <!-- Public Record -->
                                        @if((isset($menuArr['can-public-record-category-list']) && $menuArr['can-public-record-category-list']) || (isset($menuArr['can-public-record-list']) && $menuArr['can-public-record-list']))
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-public-record-category-list'])) && (isset($menuArr['can-public-record-list'])) )
                                                    <a href="#publtmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['publtmg']) && $menuArr['publtmg']=='active')? 'true' : 'false' }}" aria-controls="publtmg" data-key="t-profile" title="{{ trans('template.sidebar.publicrecord') }}"> <i class="ri-group-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.publicrecord') }}</span>
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['publtmg']) && $menuArr['publtmg']=='active')? 'show' : '' }}" id="publtmg">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-public-record-category-list']) && $menuArr['can-public-record-category-list'])
                                                            <li data-id="25" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/public-record-category') }}" class="nav-link {{ $menuArr['public-record-category_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.publicrecordcategory') }}"> <i class="ri-contacts-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.publicrecordcategory') }}</span> </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-public-record-list']) && $menuArr['can-public-record-list'])
                                                            <li data-id="26" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/public-record') }}" class="nav-link {{ $menuArr['public-record_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.publicrecord') }}">
                                                                    <i class="ri-group-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.publicrecord') }}</span>
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-public-record-category-list'])))
                                                    @if(isset($menuArr['can-public-record-category-list']) && $menuArr['can-public-record-category-list'])
                                                        <a class="nav-link {{ $menuArr['public-record-category_active'] }}" title="{{ trans('template.sidebar.publicrecordcategory') }}" href="{{ url('powerpanel/public-record-category') }}">
                                                            <i class="ri-contacts-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.publicrecordcategory') }}</span>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-public-record-list']) && $menuArr['can-public-record-list'])
                                                        <a class="nav-link {{ $menuArr['public-record_active'] }}" title="{{ trans('template.sidebar.publicrecord') }}" href="{{ url('powerpanel/public-record') }}">
                                                            <i class="ri-group-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.publicrecord') }}</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif

                                        <!--Decision -->
                                        @if((isset($menuArr['can-decision-category-list']) && $menuArr['can-decision-category-list']) || (isset($menuArr['can-decision-list']) && $menuArr['can-decision-list']))
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-decision-category-list'])) && (isset($menuArr['can-decision-list'])) )
                                                    <a href="#decisionmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['decisionmg']) && $menuArr['decisionmg']=='active')? 'true' : 'false' }}" aria-controls="decisionmg" data-key="t-profile" title="{{ trans('template.sidebar.decision') }}"> <i class="ri-user-follow-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.decision') }}</span>
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['decisionmg']) && $menuArr['decisionmg']=='active')? 'show' : '' }}" id="decisionmg">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-decision-category-list']) && $menuArr['can-decision-category-list'])
                                                            <li data-id="27" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/decision-category') }}" class="nav-link {{ $menuArr['decisioncategory_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.decisioncategory') }}"> <i class="ri-user-settings-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.decisioncategory') }}</span> </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-decision-list']) && $menuArr['can-decision-list'])
                                                            <li data-id="28" class="nav-sort nav-item">
                                                                <a href="{{ url('powerpanel/decision') }}" class="nav-link {{ $menuArr['decision_active'] }}" data-key="t-settings" title="{{ trans('template.sidebar.decision') }}">
                                                                    <i class="ri-user-follow-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.decision') }}</span>
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-decision-category-list'])))
                                                    @if(isset($menuArr['can-decision-category-list']) && $menuArr['can-decision-category-list'])
                                                        <a href="{{ url('powerpanel/decision-category') }}" class="nav-link {{ $menuArr['decisioncategory_active'] }}" title="{{ trans('template.sidebar.decisioncategory') }}">
                                                            <i class="ri-user-settings-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.decisioncategory') }}</span>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-decision-list']) && $menuArr['can-decision-list'])
                                                        <a href="{{ url('powerpanel/decision') }}" class="nav-link {{ $menuArr['decision_active'] }}" title="{{ trans('template.sidebar.decision') }}">
                                                            <i class="ri-user-follow-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.decision') }}</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif

                                        <!-- Service List -->
                                        @if(isset($menuArr['can-candwservice-list']) && $menuArr['can-candwservice-list'])
                                            <li data-id="29" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/candwservice') }}" class="nav-link {{ $menuArr['candwservice_active'] }}" title="{{ trans('template.sidebar.candwservice') }}">
                                                    <i class="ri-list-ordered d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.candwservice') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Team -->
                                        @if(isset($menuArr['can-team-list']) && $menuArr['can-team-list'])
                                            <li data-id="30" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/team') }}" class="nav-link {{ $menuArr['team_active'] }}" title="{{ trans('template.sidebar.team') }}">
                                                    <i class="ri-team-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.team') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <!-- Board of Directors -->
                                        @if(isset($menuArr['can-boardofdirectors-list']) && $menuArr['can-boardofdirectors-list'])
                                            <li data-id="31" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/boardofdirectors') }}" class="nav-link {{ $menuArr['boardofdirectors_active'] }}" title="{{ trans('template.sidebar.boardofdirectors') }}">
                                                    <i class="ri-file-user-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.boardofdirectors') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <!-- Consultations -->
                                        @if(isset($menuArr['can-consultations']) && $menuArr['can-consultations'])
                                            <li data-id="32" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/consultations') }}" class="nav-link {{ $menuArr['consultations_active'] }}" title="{{ trans('template.sidebar.consultations') }}">
                                                   <i class="ri-headphone-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.consultations') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <!-- Register Application List -->
                                        @if(isset($menuArr['can-register-application-list']) && $menuArr['can-register-application-list'])
                                            <li data-id="33" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/register-application') }}" class="nav-link {{ $menuArr['register-application_active'] }}" title="{{ trans('template.sidebar.register_application') }}">
                                                    <i class="ri-registered-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.register_application') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Licence Register -->
                                        @if(isset($menuArr['can-licence-register-list']) && $menuArr['can-licence-register-list'])
                                            <li data-id="34" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/licence-register') }}" class="nav-link {{ $menuArr['licence-register_active'] }}" title="{{ trans('template.sidebar.licence-register') }}">
                                                    <i class="ri-registered-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.licence-register') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Alerts -->
                                        @if(isset($menuArr['can-alerts-list']) && $menuArr['can-alerts-list'])
                                            <li data-id="35" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/alerts') }}" class="nav-link {{ $menuArr['alerts_active'] }}" title="{{ trans('template.sidebar.alerts') }}">
                                                    <i class="ri-alert-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.alerts') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Organizations -->
                                        @if(isset($menuArr['can-organizations-list']) && $menuArr['can-organizations-list'])
                                            <li data-id="36" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/organizations') }}" class="nav-link {{ $menuArr['organizations_active'] }}" title="{{ trans('template.sidebar.organizations') }}">
                                                    <i class="ri-government-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.organizations') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Department List -->
                                        @if(isset($menuArr['can-department-list']) && $menuArr['can-department-list'])
                                            <li data-id="37" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/department') }}" class="nav-link {{ $menuArr['department_active'] }}" title="{{ trans('template.sidebar.department') }}">
                                                    <i class="ri-building-4-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.department') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Inter Connections -->
                                        @if(isset($menuArr['can-interconnections-list']) && $menuArr['can-interconnections-list'])
                                            <li data-id="38" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/interconnections') }}" class="nav-link {{ $menuArr['interconnections_active'] }}" title="{{ trans('template.sidebar.interconnections') }}">
                                                    <i class="ri-signal-tower-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.interconnections') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Allocation -->
                                        @if(isset($menuArr['can-number-allocation']) && $menuArr['can-number-allocation'])
                                            <li data-id="39" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/number-allocation') }}" class="nav-link {{ $menuArr['number_allocation_active'] }}" title="{{ trans('template.sidebar.number_allocation') }}">
                                                    <i class="ri-album-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.number_allocation') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <!-- Company -->
                                        @if(isset($menuArr['can-companies-list']) && $menuArr['can-companies-list'])
                                            <li data-id="40" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/companies') }}" class="nav-link {{ $menuArr['companies_active'] }}" title="{{ trans('template.sidebar.company') }}">
                                                    <i class="ri-building-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.company') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Complaint Services -->
                                        @if(isset($menuArr['can-complaint-services-list']) && $menuArr['can-complaint-services-list'])
                                            <li data-id="41" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/complaint-services') }}" class="nav-link {{ $menuArr['complaint-services_active'] }}" title="{{ trans('template.sidebar.complaintservices') }}">
                                                    <i class="ri-file-settings-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.complaintservices') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Broadcasting -->
                                        @if(isset($menuArr['can-fmbroadcasting-list']) && $menuArr['can-fmbroadcasting-list'])
                                            <li data-id="42" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/fmbroadcasting') }}" class="nav-link {{ $menuArr['fmbroadcasting_active'] }}" title="{{ trans('template.sidebar.fmbroadcasting') }}">
                                                    <i class="ri-broadcast-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.fmbroadcasting') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Forms and Fees -->
                                        @if(isset($menuArr['can-forms-and-fees-list']) && $menuArr['can-forms-and-fees-list'])
                                            <li data-id="43" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/forms-and-fees') }}" class="nav-link {{ $menuArr['forms-and-fees_active'] }}" title="{{ trans('template.sidebar.forms-and-fees') }}">
                                                    <i class="ri-file-text-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.forms-and-fees') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- RFPS -->
                                        @if(isset($menuArr['can-rfps-list']) && $menuArr['can-rfps-list'])
                                            <li data-id="44" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/rfps') }}" class="nav-link {{ $menuArr['rfps_active'] }}" title="{{ trans('template.sidebar.rfps') }}">
                                                    <i class="ri-bill-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.rfps') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!--Products -->
                                        @if((isset($menuArr['can-products-category-list']) && $menuArr['can-products-category-list']) || (isset($menuArr['can-products-list']) && $menuArr['can-products-list']))
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-products-category-list'])) && (isset($menuArr['can-products-list'])) )
                                                    <a href="#pcontmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['pcontmg']) && $menuArr['pcontmg']=='active')? 'true' : 'false' }}" aria-controls="pcontmg" data-key="t-profile"> <i class="ri-gift-2-line d-none"></i> <span data-key="t-widgets">Products</span>
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['pcontmg']) && $menuArr['pcontmg']=='active')? 'show' : '' }}" id="pcontmg">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-products-category-list']) && $menuArr['can-products-category-list'])
                                                            <li class="nav-item">
                                                                <a title="{{ trans('template.sidebar.productscategory') }}" href="{{ url('powerpanel/product-category') }}" class="nav-link {{ $menuArr['products_category_active'] }}" data-key="t-settings"> <i class="ri-store-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.productscategory') }}</span> </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-products-list']) && $menuArr['can-products-list'])
                                                            <li class="nav-item">
                                                                <a title="{{ trans('template.sidebar.product') }}" href="{{ url('powerpanel/products') }}" class="nav-link {{ $menuArr['products_active'] }}" data-key="t-settings">
                                                                    <i class="ri-gift-2-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.product') }}</span>
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-products-category-list'])))
                                                    @if(isset($menuArr['can-products-category-list']) && $menuArr['can-products-category-list'])
                                                        <a class="nav-link {{ $menuArr['products_category_active'] }}" title="{{ trans('template.sidebar.productscategory') }}" href="{{ url('powerpanel/product-category') }}">
                                                            <i class="ri-store-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.productscategory') }}</span>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-products-list']) && $menuArr['can-products-list'])
                                                        <a class="nav-link {{ $menuArr['products_active'] }}" title="{{ trans('template.sidebar.product') }}" href="{{ url('powerpanel/products') }}">
                                                            <i class="ri-gift-2-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.product') }}</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif

                                        <!--Sponsor -->
                                        @if(
                                            (isset($menuArr['can-sponsor-list']) && $menuArr['can-sponsor-list']) ||
                                            (isset($menuArr['can-sponsor-category-list']) && $menuArr['can-sponsor-category-list'])
                                            )
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-sponsor-category-list'])) && (isset($menuArr['can-sponsor-list'])) )
                                                    <a href="#sponmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['sponmg']) && $menuArr['sponmg']=='active')? 'true' : 'false' }}" aria-controls="sponmg" data-key="t-profile"> {{ trans('template.sidebar.sponser') }}
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['sponmg']) && $menuArr['sponmg']=='active')? 'show' : '' }}" id="sponmg">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-sponsor-category-list']) && $menuArr['can-sponsor-category-list'])
                                                            <li class="nav-item">
                                                                <a title="{{ trans('template.sidebar.sponsorcategory') }}" href="{{ url('powerpanel/sponsor-category') }}" class="nav-link {{ $menuArr['sponsor_category_active'] }}" data-key="t-settings"> {{ trans('template.sidebar.sponsorcategory') }} </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-sponsor-list']) && $menuArr['can-sponsor-list'])
                                                            <li class="nav-item">
                                                                <a title="{{ trans('template.sidebar.sponser') }}" href="{{ url('powerpanel/sponsor') }}" class="nav-link {{ $menuArr['sponsor_active'] }}" data-key="t-settings">
                                                                {{ trans('template.sidebar.sponser') }}
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-sponsor-category-list'])))
                                                    @if(isset($menuArr['can-sponsor-category-list']) && $menuArr['can-sponsor-category-list'])
                                                        <a class="nav-link {{ $menuArr['sponsor_category_active'] }}" title="{{ trans('template.sidebar.sponsorcategory') }}" href="{{ url('powerpanel/sponsor-category') }}">
                                                            {{ trans('template.sidebar.sponsorcategory') }}
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-sponsor-list']) && $menuArr['can-sponsor-list'])
                                                        <a class="nav-link {{ $menuArr['sponsor_active'] }}" title="{{ trans('template.sidebar.sponser') }}" href="{{ url('powerpanel/sponsor') }}">
                                                            {{ trans('template.sidebar.sponser') }}
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif
                                        
                                        <!--Shows -->
                                        @if((isset($menuArr['can-show-category-list']) && $menuArr['can-show-category-list']) || (isset($menuArr['can-shows-list']) && $menuArr['can-shows-list']))
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-show-category-list'])) && (isset($menuArr['can-shows-list'])) )
                                                    <a href="#scontmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['scontmg']) && $menuArr['scontmg']=='active')? 'true' : 'false' }}" aria-controls="scontmg" data-key="t-profile"> {{ trans('template.sidebar.shows') }}
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['scontmg']) && $menuArr['scontmg']=='active')? 'show' : '' }}" id="scontmg">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-show-category-list']) && $menuArr['can-show-category-list'])
                                                            <li class="nav-item">
                                                                <a title="{{ trans('template.sidebar.showcategory') }}" href="{{ url('powerpanel/show-category') }}" class="nav-link {{ $menuArr['show_category_active'] }}" data-key="t-settings"> {{ trans('template.sidebar.showcategory') }} </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-shows-list']) && $menuArr['can-shows-list'])
                                                            <li class="nav-item">
                                                                <a title="{{ trans('template.sidebar.shows') }}" href="{{ url('powerpanel/shows') }}" class="nav-link {{ $menuArr['shows_active'] }}" data-key="t-settings">
                                                                {{ trans('template.sidebar.shows') }}
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-show-category-list'])))
                                                    @if(isset($menuArr['can-show-category-list']) && $menuArr['can-show-category-list'])
                                                        <a class="nav-link {{ $menuArr['show_category_active'] }}" title="{{ trans('template.sidebar.showcategory') }}" href="{{ url('powerpanel/show-category') }}">
                                                            {{ trans('template.sidebar.showcategory') }}
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-shows-list']) && $menuArr['can-shows-list'])
                                                        <a class="nav-link {{ $menuArr['shows_active'] }}" title="{{ trans('template.sidebar.shows') }}" href="{{ url('powerpanel/shows') }}">
                                                            {{ trans('template.sidebar.shows') }}
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif

                                        <!--Client -->
                                        @if((isset($menuArr['can-clients-category-list']) && $menuArr['can-clients-category-list']) || (isset($menuArr['can-client-list']) && $menuArr['can-client-list']))
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-clients-category-list'])) && (isset($menuArr['can-client-list'])) )
                                                    <a href="#catmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['catmg']) && $menuArr['catmg']=='active')? 'true' : 'false' }}" aria-controls="catmg" data-key="t-profile">  {{ trans('template.sidebar.client') }} 
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['catmg']) && $menuArr['catmg']=='active')? 'show' : '' }}" id="catmg">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-clients-category-list']) && $menuArr['can-clients-category-list'])
                                                            <li class="nav-item">
                                                                <a title="{{ trans('template.sidebar.clientcategory') }}" href="{{ url('powerpanel/client-category') }}" class="nav-link {{ $menuArr['client_category_active'] }}" data-key="t-settings"> {{ trans('template.sidebar.clientcategory') }} </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-client-list']) && $menuArr['can-client-list'])
                                                            <li class="nav-item">
                                                                <a title="{{ trans('template.sidebar.client') }}" href="{{ url('powerpanel/client') }}" class="nav-link {{ $menuArr['client_active'] }}" data-key="t-settings">
                                                                {{ trans('template.sidebar.client') }}
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-clients-category-list'])))
                                                    @if(isset($menuArr['can-clients-category-list']) && $menuArr['can-clients-category-list'])
                                                        <a class="nav-link {{ $menuArr['client_category_active'] }}" title="{{ trans('template.sidebar.clientcategory') }}" href="{{ url('powerpanel/client-category') }}">
                                                            {{ trans('template.sidebar.clientcategory') }}
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-client-list']) && $menuArr['can-client-list'])
                                                        <a class="nav-link {{ $menuArr['client_active'] }}" title="{{ trans('template.sidebar.client') }}" href="{{ url('powerpanel/client') }}">
                                                            {{ trans('template.sidebar.client') }}
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif

                                        <!--Real Estate -->
                                        @if((isset($menuArr['can-projects-category-list']) && $menuArr['can-projects-category-list']) || (isset($menuArr['can-projects-list']) && $menuArr['can-projects-list']))
                                            <li class="nav-item">
                                                @if((isset($menuArr['can-projects-category-list'])) && (isset($menuArr['can-projects-list'])) )
                                                    <a href="#realmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                        aria-expanded="{{ (isset($menuArr['realmg']) && $menuArr['realmg']=='active')? 'true' : 'false' }}" aria-controls="realmg" data-key="t-profile">  Real Estate
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ (isset($menuArr['realmg']) && $menuArr['realmg']=='active')? 'show' : '' }}" id="realmg">
                                                        <ul class="nav nav-sm flex-column">
                                                            @if(isset($menuArr['can-projects-category-list']) && $menuArr['can-projects-category-list'])
                                                            <li class="nav-item">
                                                                <a title="{{ trans('template.sidebar.projectscategory') }}" href="{{ url('powerpanel/project-category') }}" class="nav-link {{ $menuArr['projects_category_active'] }}" data-key="t-settings"> {{ trans('template.sidebar.projectscategory') }} </a>
                                                            </li>
                                                            @endif

                                                            @if(isset($menuArr['can-projects-list']) && $menuArr['can-projects-list'])
                                                            <li class="nav-item">
                                                                <a title="Projects" href="{{ url('powerpanel/projects') }}" class="nav-link {{ $menuArr['projects_active'] }}" data-key="t-settings">
                                                                Projects
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif((isset($menuArr['can-projects-category-list'])))
                                                    @if(isset($menuArr['can-projects-category-list']) && $menuArr['can-projects-category-list'])
                                                        <a class="nav-link {{ $menuArr['projects_category_active'] }}" title="{{ trans('template.sidebar.projectscategory') }}" href="{{ url('powerpanel/project-category') }}">
                                                            {{ trans('template.sidebar.projectscategory') }}
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($menuArr['can-projects-list']) && $menuArr['can-projects-list'])
                                                        <a class="nav-link {{ $menuArr['projects_active'] }}" title="Projects" href="{{ url('powerpanel/projects') }}">
                                                            Projects
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif
                                        
                                        <!--Photo Album -->
                                        @if(
                                            (isset($menuArr['can-photo-album-category-list']) && $menuArr['can-photo-album-category-list']) ||
                                            (isset($menuArr['can-photo-album-list']) && $menuArr['can-photo-album-list']) ||
                                            (isset($menuArr['can-photo-gallery-list']) && $menuArr['can-photo-gallery-list'])
                                            )
                                            <li data-id="45" class="nav-sort nav-item">
                                                <a href="#photoalmg" class="nav-link" data-bs-toggle="collapse" role="button"
                                                    aria-expanded="{{ (isset($menuArr['photoalmg']) && $menuArr['photoalmg']=='active')? 'true' : 'false' }}" aria-controls="photoalmg" data-key="t-profile"> <i class="ri-image-add-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.photoalbum') }}</span>
                                                </a>
                                                <div class="collapse menu-dropdown {{ (isset($menuArr['photoalmg']) && $menuArr['photoalmg']=='active')? 'show' : '' }}" id="photoalmg">
                                                    <ul class="nav nav-sm flex-column">
                                                        @if(isset($menuArr['can-photo-album-list']) && $menuArr['can-photo-album-list'])
                                                        <li class="nav-item">
                                                            <a title="{{ trans('template.sidebar.photoalbum') }}" href="{{ url('powerpanel/photo-album') }}" class="nav-link {{ $menuArr['photo_album_active'] }}" data-key="t-settings">
                                                                <i class="ri-image-add-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.photoalbum') }}</span>
                                                            </a>
                                                        </li>
                                                        @endif
                                                        
                                                        @if(isset($menuArr['can-photo-gallery-list']) && $menuArr['can-photo-gallery-list'])
                                                        <li class="nav-item">
                                                            <a title="{{ trans('template.sidebar.photogallery') }}" href="{{ url('powerpanel/photo-gallery') }}" class="nav-link {{ $menuArr['photo_gallery_active'] }}" data-key="t-settings">
                                                                <i class="ri-image-2-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.photogallery') }}</span>
                                                            </a>
                                                        </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </li>
                                        @endif

                                        <!-- Video Gallery -->
                                        @if(isset($menuArr['can-video-gallery-list']) && $menuArr['can-video-gallery-list'])
                                            <li class="nav-item">
                                                <a class="nav-link {{ $menuArr['video_gallery_active'] }}" title="{{ trans('template.sidebar.video_gallery') }}" href="{{ url('powerpanel/video-gallery') }}">
                                                    <i class="ri-video-add-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.video_gallery') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Testimonial -->
                                        @if(isset($menuArr['can-testimonial-list']) && $menuArr['can-testimonial-list'])
                                            <li class="nav-item">
                                                <a class="nav-link  {{ $menuArr['testimonial_active'] }}" title="{{ trans('template.sidebar.testimonial') }}" href="{{ url('powerpanel/testimonial') }}">
                                                    <i class="ri-chat-quote-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.testimonial') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <!-- Advertsements -->
                                        @if(isset($menuArr['can-advertise-list']) && $menuArr['can-advertise-list'])
                                            <li class="nav-item">
                                                <a class="nav-link {{ $menuArr['ads_active'] }}" title="{{ trans('template.sidebar.advertisements') }}" href="{{ url('powerpanel/advertise') }}">
                                                    <i class="ri-speaker-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.advertisements') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    
                                        <!-- Popup List -->
                                        @if(isset($menuArr['can-popup-list']) && $menuArr['can-popup-list'])
                                            <li class="nav-item">
                                                <a class="nav-link {{ $menuArr['popup_active'] }}" title="{{ trans('template.sidebar.popup') }}" href="{{ url('powerpanel/popup') }}">
                                                    <i class="ri-bring-forward d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.popup') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <!-- Online Polling -->
                                        @if (Config::get('Constant.DEFAULT_ONLINEPOLLINGFORM') == 'Y')
                                            @if(isset($menuArr['can-online-polling-list']) && $menuArr['can-online-polling-list'])
                                                <li class="nav-item">
                                                    <a class="nav-link {{ $menuArr['online_polling_active'] }}" title="{{ trans('template.sidebar.onlinepolling') }}" href="{{ url('powerpanel/online-polling') }}">
                                                        <i class="ri-file-ppt-2-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.onlinepolling') }}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endif

                                        <!-- Tag -->
                                        @if(isset($menuArr['can-tag-list']) && $menuArr['can-tag-list'])
                                            <li class="nav-item">
                                                <a class="nav-link {{ $menuArr['tag_active'] }}" title="{{ trans('template.sidebar.tag') }}" href="{{ url('powerpanel/tag') }}">
                                                    <i class="ri-price-tag-3-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.tag') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Maintenance -->
                                        @if(isset($menuArr['can-maintenance-list']) && $menuArr['can-maintenance-list'])
                                            <li class="nav-item">
                                                <a class="nav-link {{ $menuArr['maintenance_active'] }}" title="{{ trans('template.sidebar.maintenance') }}" href="{{ url('powerpanel/maintenance') }}">
                                                    <i class="ri-file-settings-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.maintenance') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <!-- RFP -->
                                        @if(isset($menuArr['can-rfp-list']) && $menuArr['can-rfp-list'])
                                            <li class="nav-item">
                                                <a class="nav-link {{ $menuArr['rfp_active'] }}" title="{{ trans('template.sidebar.rfp') }}" href="{{ url('powerpanel/rfp') }}">
                                                    <i class="ri-bill-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.rfp') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Form Builder -->
                                        @if(isset($menuArr['can-formbuilder-list']) && $menuArr['can-formbuilder-list'])
                                            <li class="nav-item">
                                                <a class="nav-link {{ $menuArr['formbuilder_active'] }}" title="{{ trans('template.sidebar.formbuilder') }}" href="{{ url('powerpanel/formbuilder') }}">
                                                    <i class="ri-todo-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.formbuilder') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif

                    </ul>
                </div>
            </li>
        @endif --}}

        <!-- Workflow -->
        @if((isset($menuArr['can-workflow-list']) && $menuArr['can-workflow-list']) || 

            (isset($menuArr['can-roles-list']) && $menuArr['can-roles-list']) ||
            (isset($menuArr['can-users-list']) && $menuArr['can-users-list']))
            <li class="nav-item">
                <a class="nav-link menu-link" href="#workflow" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ ((isset($menuArr['usermg']) && $menuArr['usermg'] == 'active') || (isset($menuArr['usermg']) && $menuArr['usermg']=='active')) ? 'true' : 'false' }}" aria-controls="workflow" title="{{ trans('template.sidebar.users') }}">
                    <i class="ri-file-user-fill"></i> <span data-key="t-widgets">{{ trans('template.sidebar.users') }}</span>
                </a>
                <div class="collapse menu-dropdown {{ ((isset($menuArr['usermg']) && $menuArr['usermg']=='active')) ? 'show' : '' }}" id="workflow">
                    <ul class="nav nav-sm flex-column">
                        @if(isset($menuArr['can-roles-list']) && $menuArr['can-roles-list'])
                        <li data-id="47" class="nav-sort nav-item">
                            <a href="{{ url('powerpanel/roles') }}" class="nav-link {{ $menuArr['roles_active'] }}" data-key="t-analytics" title="{{ trans('template.sidebar.rolemanager') }}"> 
                                <i class="ri-profile-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.rolemanager') }}</span>
                            </a>
                        </li>
                    @endif
                    
                    <!-- User Management -->
                    @if(isset($menuArr['can-users-list']) && $menuArr['can-users-list'])
                        <li data-id="48" class="nav-sort nav-item">
                            <a href="{{ url('powerpanel/users') }}" class="nav-link {{ $menuArr['events_lead_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.usermanagement') }}"> 
                                <i class="ri-file-user-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.usermanagement') }}</span>
                            </a>
                        </li>
                    @endif
                        <!-- Workflow -->
                        {{-- @if(isset($menuArr['can-workflow-list']) && $menuArr['can-workflow-list'])
                            <li data-id="46" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/workflow') }}" class="nav-link {{ $menuArr['workflow_active'] }}" data-key="t-starter" title="{{ trans('template.sidebar.workflow') }}">
                                    <i class="ri-stackshare-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.workflow') }}</span>
                                </a>
                            </li>
                        @endif --}}

                        <!-- Users -->                       
                    
                            {{-- <a class="nav-link menu-link" href="#usermg" data-bs-toggle="collapse" role="button"
                                aria-expanded="{{ (isset($menuArr['usermg']) && $menuArr['usermg']=='active') ? 'true' : 'false' }}" aria-controls="usermg">
                                <i class="ri-user-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.users') }}</span>
                            </a>
                            <div class="collapse menu-dropdown {{ (isset($menuArr['usermg']) && $menuArr['usermg']=='active') ? 'show' : '' }}" id="usermg">
                                <ul class="nav nav-sm flex-column">
                                    <!-- Role Manager -->
                                   
                                </ul>
                            </div> --}}
                                
                    </ul>
                </div>
            </li>
        @endif

        <!-- Leads -->
        @if(
            (isset($menuArr['can-contact-us-list']) && $menuArr['can-contact-us-list']) ||
            // (isset($menuArr['can-complaint-list']) && $menuArr['can-complaint-list']) ||
            (isset($menuArr['can-appointment-lead-list']) && $menuArr['can-appointment-lead-list']) ||
            (isset($menuArr['can-getdemo-leads-list']) && $menuArr['can-getdemo-leads-list']) ||
            (isset($menuArr['can-reseller-leads-list']) && $menuArr['can-reseller-leads-list']) ||
            (isset($menuArr['can-events-lead-list']) && $menuArr['can-events-lead-list']) ||
            (isset($menuArr['can-careers-lead-list']) && $menuArr['can-careers-lead-list']) ||
            (isset($menuArr['can-newsletter-lead-list']) && $menuArr['can-newsletter-lead-list'])||
            (isset($menuArr['can-error-tracking-list']) && $menuArr['can-error-tracking-list'])||
            // (isset($menuArr['can-online-polling-lead-list']) && $menuArr['can-online-polling-lead-list']) ||
            (isset($menuArr['can-order-lead-list']) && $menuArr['can-order-lead-list']) ||
            // (isset($menuArr['can-payonline-list']) && $menuArr['can-payonline-list']) ||
            (isset($menuArr['can-submit-tickets-list']) && $menuArr['can-submit-tickets-list']))
            <li class="nav-item">
                <a class="nav-link menu-link" href="#leadmg" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ (isset($menuArr['leadmg']) && $menuArr['leadmg']=='active')? 'true' : 'false' }}" aria-controls="leadmg" title="{{ trans('template.sidebar.leads') }}">
                    <i class="ri-list-ordered"></i> <span data-key="t-dashboards">{{ trans('template.sidebar.leads') }}</span>
                </a>
                <div class="collapse menu-dropdown {{ (isset($menuArr['leadmg']) && $menuArr['leadmg']=='active')? 'show' : '' }}" id="leadmg">
                    <ul class="nav nav-sm flex-column">
                        @if(isset($menuArr['can-contact-us-list']) && $menuArr['can-contact-us-list'])
                            <li data-id="49" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/contact-us') }}" class="nav-link {{ $menuArr['contact_active'] }}" data-key="t-analytics" title="{{ trans('template.sidebar.contactuslead') }}">
                                    <i class="ri-contacts-book-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.contactuslead') }}</span>
                                </a>
                            </li>
                        @endif

                        @if(isset($menuArr['can-getdemo-leads-list']) && $menuArr['can-getdemo-leads-list'])
                            <li data-id="55" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/getdemo-leads') }}" class="nav-link {{ $menuArr['getdemo_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.getdemolead') }}"> 
                                    <i class="ri-getdemo-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.getdemolead') }}</span>
                                </a>
                            </li>
                        @endif

                        {{-- @if(isset($menuArr['can-reseller-leads-list']) && $menuArr['can-reseller-leads-list'])
                            <li data-id="55" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/reseller-leads') }}" class="nav-link {{ $menuArr['reseller_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.resellerlead') }}"> 
                                    <i class="ri-reseller-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.resellerlead') }}</span>
                                </a>
                            </li>
                        @endif --}}

                        @if(isset($menuArr['can-order-lead-list']) && $menuArr['can-order-lead-list'])
                        <li class="nav-item">
                            <a class="nav-link {{ $menuArr['order_active'] }}" title="{{ trans('template.sidebar.orderleads') }}" href="{{ url('powerpanel/order') }}">
                                <i class="ri-todo-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.orderleads') }}</span>
                            </a>
                        </li>
                    @endif
                        
                        @if(isset($menuArr['can-complaint-list']) && $menuArr['can-complaint-list'])
                            <li data-id="50" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/complaint') }}" class="nav-link {{ $menuArr['complaint_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.complaintlead') }}">
                                    <i class="ri-file-edit-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.complaintlead') }}</span>
                                </a>
                            </li>
                        @endif
                        
                        {{-- @if(isset($menuArr['can-events-lead-list']) && $menuArr['can-events-lead-list'])
                            <li data-id="51" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/events-lead') }}" class="nav-link {{ $menuArr['events_lead_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.eventLeads') }}"> 
                                    <i class="ri-calendar-todo-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.eventLeads') }}</span>
                                </a>
                            </li>
                        @endif --}}
                        
                        @if(isset($menuArr['can-careers-lead-list']) && $menuArr['can-careers-lead-list'])
                            <li data-id="52" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/careers-lead') }}" class="nav-link {{ $menuArr['careers_lead_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.careerLeads') }}"> 
                                    <i class="ri-handbag-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.careerLeads') }}</span>
                                </a>
                            </li>
                        @endif
                        
                        {{-- @if(isset($menuArr['can-payonline-list']) && $menuArr['can-payonline-list'])
                            <li data-id="53" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/payonline') }}" class="nav-link {{ $menuArr['payonline_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.payonline') }}"> 
                                    <i class="ri-secure-payment-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.payonline') }}</span>
                                </a>
                            </li>
                        @endif --}}
                        
                        {{-- @if(isset($menuArr['can-appointment-lead-list']) && $menuArr['can-appointment-lead-list'])
                            <li data-id="54" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/appointment-lead') }}" class="nav-link {{ $menuArr['appointment_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.bookanappointment') }}"> 
                                    <i class="ri-article-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.bookanappointment') }}</span>
                                </a>
                            </li>
                        @endif --}}
                        
                        {{-- @if(isset($menuArr['can-feedback-leads-list']) && $menuArr['can-feedback-leads-list'])
                            <li data-id="55" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/feedback-leads') }}" class="nav-link {{ $menuArr['feedback_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.feedbacklead') }}"> 
                                    <i class="ri-feedback-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.feedbacklead') }}</span>
                                </a>
                            </li>
                        @endif --}}
                        
                        {{-- @if(isset($menuArr['can-newsletter-lead-list']) && $menuArr['can-newsletter-lead-list'])
                            <li data-id="56" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/newsletter-lead') }}" class="nav-link {{ $menuArr['news_letter_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.newsletterleads') }}"> 
                                    <i class="ri-newspaper-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.newsletterleads') }}</span>
                                </a>
                            </li>
                        @endif --}}
                        
                        {{-- @if(isset($menuArr['can-formbuilder-lead-list']) && $menuArr['can-formbuilder-lead-list'])
                            <li data-id="57" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/formbuilder-lead') }}" class="nav-link {{ $menuArr['form_builder_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.formbuilderleads') }}"> 
                                    <i class="ri-todo-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.formbuilderleads') }}</span>
                                </a>
                            </li>
                        @endif --}}
                        
                        {{-- @if(isset($menuArr['can-error-tracking-list']) && $menuArr['can-error-tracking-list'])
                            <li data-id="58" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/error-tracking') }}" class="nav-link {{ $menuArr['error_tracking_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.errortracking') }}"> 
                                    <i class="ri-window-2-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.errortracking') }}</span>
                                </a>
                            </li>
                        @endif --}}
                        
                        {{-- @if(isset($menuArr['can-online-polling-lead-list']) && $menuArr['can-online-polling-lead-list'])
                            <li data-id="59" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/online-polling-lead') }}" class="nav-link {{ $menuArr['online_polling_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.onlinepollinglead') }}"> 
                                    <i class="ri-chat-poll-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.onlinepollinglead') }}</span>
                                </a>
                            </li>
                        @endif --}}

                        <!-- Ticket List -->
                        {{-- @if(isset($menuArr['can-submit-tickets-list']) && $menuArr['can-submit-tickets-list'])
                            <li class="nav-item">
                                <a class="nav-link {{ $menuArr['tickets_active'] }}" href="{{ url('powerpanel/submit-tickets') }}" title="{{ trans('template.sidebar.submitticketslead') }}">
                                    <i class="ri-ticket-2-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.submitticketslead') }}</span>
                                </a>
                            </li>
                        @endif --}}
                    </ul>
                </div>
            </li>
        @endif

        <!-- Reports -->
       {{--  @if(
            (isset($menuArr['search-statictics-list']) && $menuArr['search-statictics-list']) ||
            (isset($menuArr['hits-report-list']) && $menuArr['hits-report-list']) ||
            (isset($menuArr['document-report-list']) && $menuArr['document-report-list'])
            )
            <li class="nav-item">
                <a class="nav-link menu-link" href="#reportmg" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ (isset($menuArr['reportmg']) && $menuArr['reportmg']=='active')? 'true' : 'false' }}" aria-controls="reportmg">
                    <i class="ri-line-chart-line"></i> <span data-key="t-dashboards">{{ trans('template.sidebar.report') }}</span>
                </a>
                <div class="collapse menu-dropdown {{ (isset($menuArr['reportmg']) && $menuArr['reportmg']=='active')? 'show' : '' }}" id="reportmg">
                    <ul class="nav nav-sm flex-column">
                        @if(isset($menuArr['search-statictics-list']) && $menuArr['search-statictics-list'])
                            <li data-id="60" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/search-statictics') }}" class="nav-link {{ $menuArr['searchstatictics_active'] }}" data-key="t-analytics" title="Search Statistics"> 
                                    <i class="ri-pie-chart-line d-none"></i> <span data-key="t-widgets">Search Statistics</span>
                                </a>
                            </li>
                        @endif
                        
                        @if(isset($menuArr['hits-report-list']) && $menuArr['hits-report-list'])
                            <li data-id="61" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/hits-report') }}" class="nav-link {{ $menuArr['hitsreport_active'] }}" data-key="t-crm" title="Hits Report">
                                    <i class="ri-survey-line d-none"></i> <span data-key="t-widgets">Hits Report</span>
                                </a>
                            </li>
                        @endif
                        
                        @if(isset($menuArr['document-report-list']) && $menuArr['document-report-list'])
                            <li data-id="62" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/document-report') }}" class="nav-link {{ $menuArr['documentreport_active'] }}" data-key="t-crm" title="Documents Report">
                                    <i class="ri-file-chart-line d-none"></i> <span data-key="t-widgets">Documents Report</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif --}}

        <!-- Market Place -->
        {{-- <li data-id="63" class="nav-item nav-sort">
            <a class="nav-link menu-link" href="javascript:void(0);" title="{{ trans('template.sidebar.marketplace') }}">
                <i class="ri-macbook-line"></i> <span data-key="t-widgets">{{ trans('template.sidebar.marketplace') }}</span>
            </a>
        </li> --}}

        <!-- Recent Updates -->
        {{-- @if(isset($menuArr['can-recent-updates-list']) && $menuArr['can-recent-updates-list'])
            <li class="nav-item">
                <a class="nav-link menu-link {{ $menuArr['recmg'] }}" href="{{ url('powerpanel/recent-updates') }}" title="{{ trans('template.sidebar.recentupdates') }}">
                    <i class="ri-pencil-line"></i> <span data-key="t-widgets">{{ trans('template.sidebar.recentupdates') }}</span>
                </a>
            </li>
        @endif --}}

        <!-- Messaging System -->
        {{-- @if (Config::get('Constant.DEFAULT_MESSAGINGSYSTEM') == 'Y')
            @if(isset($menuArr['can-messagingsystem-list']) && $menuArr['can-messagingsystem-list'])
                <li class="nav-item">
                    <a class="nav-link menu-link {{ $menuArr['messagingsystem_active'] }}" href="{{ url('powerpanel/messagingsystem') }}" title="{{ trans('template.sidebar.messagingsystem') }}">
                        <i class="ri-wechat-line"></i> <span data-key="t-widgets">{{ trans('template.sidebar.messagingsystem') }}</span>
                    </a>
                </li>
            @endif
        @endif --}}

        <!-- Settings -->
        @if((isset($menuArr['can-media-manager-list']) && $menuArr['can-media-manager-list']) ||
            (isset($menuArr['can-contact-list']) && $menuArr['can-contact-list']) ||
            (isset($menuArr['can-blockedip-list']) && $menuArr['can-blockedip-list']) ||

            (isset($menuArr['can-email-log-list']) && $menuArr['can-email-log-list']) ||
            (isset($menuArr['can-log-list']) && $menuArr['can-log-list']) ||
            (isset($menuArr['can-error-logs-list']) && $menuArr['can-error-logs-list']) ||
            (isset($menuArr['can-login-history']) && $menuArr['can-login-history']))
            <li class="nav-item">
                <a class="nav-link menu-link" href="#settings" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ (isset($menuArr['settings']) && $menuArr['settings'] == 'active') ? 'true' : 'false' }}" aria-controls="settings" title="{{ trans('template.sidebar.settings') }}">
                    <i class="ri-settings-2-line"></i> <span data-key="t-widgets">{{ trans('template.sidebar.settings') }}</span>
                </a>
                <div class="collapse menu-dropdown {{ (isset($menuArr['settings']) && $menuArr['settings'] == 'active') ? 'show' : '' }}" id="settings">
                    <ul class="nav nav-sm flex-column">
                        <!-- Medi   a Manager -->
                        {{-- @if(isset($menuArr['can-media-manager-list']) && $menuArr['can-media-manager-list'])
                            <li data-id="64" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/media-manager') }}" class="nav-link {{ $menuArr['mediamanager_active'] }}" data-key="t-starter" title="Media Manager">
                                    <i class="ri-camera-line d-none"></i> <span data-key="t-widgets">Media Manager</span>
                                </a>
                            </li>
                        @endif --}}

                        <!-- Contacts -->
                        {{-- @if(isset($menuArr['can-contact-list']) && $menuArr['can-contact-list'])
                            <li data-id="65" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/contact-info') }}" class="nav-link {{ $menuArr['contact_info_active'] }}" data-key="t-starter" title="{{ trans('template.sidebar.contact') }}">
                                    <i class="ri-phone-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.contact') }}</span>
                                </a>
                            </li>
                        @endif --}}

                        <!-- Blocked IPs -->
                        {{-- @if(isset($menuArr['can-blockedip-list']) && $menuArr['can-blockedip-list'])
                            <li data-id="66" class="nav-sort nav-item">
                                <a href="{{ url('powerpanel/blocked-ips') }}" class="nav-link {{ $menuArr['blockedip_active'] }}" data-key="t-starter" title="Blocked IPs">
                                    <i class="ri-lock-2-line d-none"></i> <span data-key="t-widgets">Blocked IPs</span>
                                </a>
                            </li>
                        @endif --}}

                        <!-- Logs -->
                        {{-- @if(
                            (isset($menuArr['can-email-log-list']) && $menuArr['can-email-log-list']) ||
                            (isset($menuArr['can-log-list']) && $menuArr['can-log-list']) ||
                            (isset($menuArr['can-error-logs-list']) && $menuArr['can-error-logs-list']))
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#logmg" data-bs-toggle="collapse" role="button"
                                    aria-expanded="{{ (isset($menuArr['logmg']) && $menuArr['logmg']=='active')? 'true' : 'false' }}" aria-controls="logmg">
                                    <i class="ri-mail-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.logs') }}</span>
                                </a>
                                <div class="collapse menu-dropdown {{ (isset($menuArr['logmg']) && $menuArr['logmg']=='active')? 'show' : '' }}" id="logmg">
                                    <ul class="nav nav-sm flex-column">
                                        <!-- Audit Log -->
                                        @if(isset($menuArr['can-log-list']) && $menuArr['can-log-list'])
                                            <li data-id="67" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/log') }}" class="nav-link {{ $menuArr['log_active'] }}" data-key="t-analytics" title="{{ trans('template.sidebar.logmanager') }}">
                                                    <i class="ri-mail-check-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.logmanager') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <!-- Email Logs -->
                                        @if(isset($menuArr['can-email-log-list']) && $menuArr['can-email-log-list'])
                                            <li data-id="68" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/email-log') }}" class="nav-link {{ $menuArr['email_active'] }}" data-key="t-crm" title="{{ trans('template.sidebar.emaillogs') }}">
                                                    <i class="ri-mail-open-line d-none"></i> <span data-key="t-widgets">{{ trans('template.sidebar.emaillogs') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <!-- Error Logs -->
                                        @if(isset($menuArr['can-error-logs-list']) && $menuArr['can-error-logs-list'])
                                            <li data-id="69" class="nav-sort nav-item">
                                                <a href="{{ url('powerpanel/error-logs') }}" class="nav-link {{ $menuArr['error_logs_active'] }}" data-key="t-crm" title="Error Logs">
                                                    <i class="ri-alert-line d-none"></i> <span data-key="t-widgets">Error Logs</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif --}}

                        <!-- Login History -->
                        @if(isset($menuArr['can-login-history']) && $menuArr['can-login-history'])
                            <li class="nav-item">
                                <a class="nav-link {{ $menuArr['login_history_active'] }}" href="{{ url('powerpanel/login-history') }}" data-key="t-starter" title="{{ trans('Login History') }}">
                                    <i class="ri-key-2-line d-none"></i> <span data-key="t-widgets">{{ trans('Login History') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif

    </ul>