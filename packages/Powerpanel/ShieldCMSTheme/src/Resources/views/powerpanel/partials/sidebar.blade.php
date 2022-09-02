@php 
$menuArr = App\Helpers\PowerPanelSidebarConfig::getConfig(); 
@endphp
<div class="page-sidebar-wrapper">		
    <div class="page-sidebar navbar-collapse collapse">
        <div class="scroller" style="max-height:calc(100vh - 100px);" data-rail-visible="1" data-rail-color="#fff" data-handle-color="#ccc">
            <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <li class="nav-item start {{ $menuArr['dashboard_active'] }} {{ $menuArr['dashboard_open'] }}">
                    <a href="{{ url('powerpanel') }}" title="{{ trans('template.sidebar.dashboard') }}" class="nav-link nav-toggle">
                        <i class="la la-dashboard"></i>
                        <span class="title">{{ trans('template.sidebar.dashboard') }}</span>
                        <span class="{{ $menuArr['dashboard_selected'] }}"></span>
                    </a>
                </li>
                @if((isset($menuArr['can-menu-list']) && $menuArr['can-menu-list']) ||
                (isset($menuArr['can-pages-list']) && $menuArr['can-pages-list']) ||
                (isset($menuArr['can-banner-list']) && $menuArr['can-banner-list']) ||
                (isset($menuArr['can-static-block']) && $menuArr['can-static-block']) ||
                (isset($menuArr['can-contact-list']) && $menuArr['can-contact-list']))
                <li class="nav-item @if( isset($menuArr['sitemg']) && $menuArr['sitemg']=='active' ) active @endif @if( isset($menuArr['sitemgopen']) && $menuArr['sitemgopen']=='open' ) open @endif">
                    <a title="{{ trans('template.sidebar.sitemanagement') }}" href="javascript:;" class="nav-link nav-toggle">
                        <i class="la la-sitemap"></i>
                        <span class="title">{{ trans('template.sidebar.sitemanagement') }}</span>
                        <span class="arrow {{ (isset($menuArr['sitemgopen']) && $menuArr['sitemgopen']=='open')? 'open' : '' }}"></span>
                        <span class=""></span>
                        <span class=""></span>
                    </a>
                    <ul class="sub-menu" style="display: block;">
                        @if(isset($menuArr['can-menu-list']) && $menuArr['can-menu-list'])
                        <li class="nav-item {{ $menuArr['menu_active'] }} {{ $menuArr['menu_open'] }}">
                            <a title="{{ trans('template.sidebar.menu') }}" href="{{ url('powerpanel/menu') }}" class="nav-link nav-toggle">
                                <i class="icon-list"></i>
                                <span class="title">{{ trans('template.sidebar.menu') }}</span>
                                <span class="{{ $menuArr['menu_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-pages-list']) && $menuArr['can-pages-list'])
                        <li class="nav-item {{ $menuArr['page_active'] }} {{ $menuArr['page_open'] }}">
                            <a title="{{ trans('template.sidebar.pages') }}" href="{{ url('powerpanel/pages') }}" class="nav-link nav-toggle">
                                <i class="icon-layers"></i>
                                <span class="title">{{ trans('template.sidebar.pages') }}</span>
                                <span class="{{ $menuArr['page_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-banner-list']) && $menuArr['can-banner-list'])
                        <li class="nav-item {{ $menuArr['banner_active'] }} {{ $menuArr['banner_open'] }}">
                            <a title="{{ trans('template.sidebar.banner') }}" href="{{ url('powerpanel/banners') }}" class="nav-link nav-toggle">
                                <i class="icon-picture"></i>
                                <span class="title">{{ trans('template.sidebar.banner') }}</span>
                                <span class="{{ $menuArr['banner_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-static-block']) && $menuArr['can-static-block'])
                        {{--<li class="nav-item {{ $menuArr['staticblocks_active'] }} {{ $menuArr['staticblocks_open'] }}">
                        <a title="{{ trans('template.sidebar.staticblock') }}" href="{{ url('powerpanel/static-block') }}" class="nav-link nav-toggle">
                            <i class="fa fa-commenting-o"></i>
                            <span class="title">{{ trans('template.sidebar.staticblock') }}</span>
                            <span class="{{ $menuArr['staticblocks_selected'] }}"></span>
                        </a>
                </li>--}}
                @endif
                @if(isset($menuArr['can-contact-list']) && $menuArr['can-contact-list'])
                <li class="nav-item start {{ $menuArr['contact_info_active'] }} {{ $menuArr['contact_info_open'] }}">
                    <a title="{{ trans('template.sidebar.contact') }}" href="{{ url('/powerpanel/contact-info') }}" class="nav-link nav-toggle">
                        <i class="fa fa-volume-control-phone"></i>
                        <span class="title">{{ trans('template.sidebar.contact') }}</span>
                        <span class="{{ $menuArr['contact_info_selected'] }}"></span>
                    </a>
                </li>
                @endif
                @if(
                (isset($menuArr['can-news-category-list']) && $menuArr['can-news-category-list']) ||
                (isset($menuArr['can-news-list']) && $menuArr['can-news-list']) ||
                (isset($menuArr['can-rfps-list']) && $menuArr['can-rfps-list']) ||
                (isset($menuArr['can-rfp-list']) && $menuArr['can-rfp-list']) ||
                (isset($menuArr['can-links-category-list']) && $menuArr['can-links-category-list']) ||
                (isset($menuArr['can-links-list']) && $menuArr['can-links-list']) ||
                (isset($menuArr['can-faq-category-list']) && $menuArr['can-faq-category-list']) ||
                (isset($menuArr['can-event-category-list']) && $menuArr['can-event-category-list']) ||
                (isset($menuArr['can-events-list']) && $menuArr['can-events-list']) ||
                (isset($menuArr['can-blog-category-list']) && $menuArr['can-blog-category-list']) ||
                (isset($menuArr['can-blogs-list']) && $menuArr['can-blogs-list']) ||
                (isset($menuArr['can-decision-category-list']) && $menuArr['can-decision-category-list']) ||
                (isset($menuArr['can-decision-list']) && $menuArr['can-decision-list']) ||
                (isset($menuArr['can-netblogs-list']) && $menuArr['can-netblogs-list']) ||
                (isset($menuArr['can-companies-list']) && $menuArr['can-companies-list']) ||
                (isset($menuArr['can-faq-list']) && $menuArr['can-faq-list']) ||
                (isset($menuArr['can-careers-list']) && $menuArr['can-careers-list']) ||  
                (isset($menuArr['can-service-list']) && $menuArr['can-service-list']) ||
                (isset($menuArr['can-service-category-list']) && $menuArr['can-service-category-list']) ||
                (isset($menuArr['can-public-record-category-list']) && $menuArr['can-public-record-category-list']) ||
                (isset($menuArr['can-public-record-list']) && $menuArr['can-public-record-list']) ||
                (isset($menuArr['can-organizations-list']) && $menuArr['can-organizations-list']) ||
                (isset($menuArr['can-department-list']) && $menuArr['can-department-list']) ||
                (isset($menuArr['can-interconnections-list']) && $menuArr['can-interconnections-list']) ||
                (isset($menuArr['can-number-allocation']) && $menuArr['can-number-allocation']) ||
                (isset($menuArr['can-online-polling-list']) && $menuArr['can-online-polling-list']) ||
                (isset($menuArr['can-complaint-services-list']) && $menuArr['can-complaint-services-list']) ||
                (isset($menuArr['can-boardofdirectors-list']) && $menuArr['can-boardofdirectors-list']) ||
                (isset($menuArr['can-register-application-list']) && $menuArr['can-register-application-list']) ||
                (isset($menuArr['can-licence-register-list']) && $menuArr['can-licence-register-list']) ||
                (isset($menuArr['can-forms-and-fees-list']) && $menuArr['can-forms-and-fees-list']) ||
                (isset($menuArr['can-popup-list']) && $menuArr['can-popup-list']) ||
                (isset($menuArr['can-candwservice-list']) && $menuArr['can-candwservice-list']) ||
                (isset($menuArr['can-tag-list']) && $menuArr['can-tag-list']) ||
                (isset($menuArr['can-maintenance-list']) && $menuArr['can-maintenance-list']) ||
                (isset($menuArr['can-quick-links-list']) && $menuArr['can-quick-links-list']) ||
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
                (isset($menuArr['can-payonline-list']) && $menuArr['can-payonline-list']) ||
                (isset($menuArr['can-news-list']) && $menuArr['can-news-list'])
                )
                <li class="nav-item {{ (isset($menuArr['contmg']) && $menuArr['contmg']=='active')? 'open active' : '' }}">
                    <a title="Modules" href="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-puzzle-piece"></i>
                        <span class="title">Modules</span>
                        <span class="arrow {{ (isset($menuArr['contmg']) && $menuArr['contmg']=='active')? 'open' : '' }}"></span>
                        <span class=""></span>
                        <span class=""></span>
                    </a>
                    <ul class="sub-menu">
                        @if(isset($menuArr['can-alerts-list']) && $menuArr['can-alerts-list'])
                        <li class="nav-item {{ $menuArr['alerts_active'] }} {{ $menuArr['alerts_active'] }}">
                            <a title="{{ trans('template.sidebar.alerts') }}" href="{{ url('powerpanel/alerts') }}" class="nav-link nav-toggle">
                                <i class="ri-alert-fill"></i>
                                <span class="title">{{ trans('template.sidebar.alerts') }}</span>
                                <span class="{{ $menuArr['alerts_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                       

                        @if(isset($menuArr['can-organizations-list']) && $menuArr['can-organizations-list'])

                        <li class="nav-item {{ $menuArr['organizations_active'] }} {{ $menuArr['organizations_open'] }}">
                            <a title="{{ trans('template.sidebar.organizations') }}" href="{{ url('powerpanel/organizations') }}" class="nav-link nav-toggle">
                                <i class="fa fa-university"></i>
                                <span class="title">Organizations</span>
                                <span class="{{ $menuArr['organizations_selected'] }}"></span>
                            </a>
                        </li>
                        @endif

                        @if(isset($menuArr['can-department-list']) && $menuArr['can-department-list'])                          
                        <li class="nav-item {{ $menuArr['department_active'] }} {{ $menuArr['department_open'] }}">
                            <a title="{{ trans('template.sidebar.department') }}" href="{{ url('powerpanel/department') }}" class="nav-link nav-toggle">
                                <i class="fa fa-building-o"></i>
                                <span class="title">{{ trans('template.sidebar.department') }}</span>
                                <span class="{{ $menuArr['department_selected'] }}"></span>
                            </a>
                        </li>
                        @endif

                        @if(isset($menuArr['can-interconnections-list']) && $menuArr['can-interconnections-list'])                          
                        <li class="nav-item {{ $menuArr['interconnections_active'] }} {{ $menuArr['interconnections_open'] }}">
                            <a title="{{ trans('template.sidebar.interconnections') }}" href="{{ url('powerpanel/interconnections') }}" class="nav-link nav-toggle">
                                <i class="fa fa-building-o"></i>
                                <span class="title">{{ trans('template.sidebar.interconnections') }}</span>
                                <span class="{{ $menuArr['interconnections_selected'] }}"></span>
                            </a>
                        </li>
                        @endif

                        @if(isset($menuArr['can-number-allocation']) && $menuArr['can-number-allocation'])                          
                        <li class="nav-item {{ $menuArr['number_allocation_active'] }} {{ $menuArr['number_allocation_open'] }}">
                            <a title="{{ trans('template.sidebar.number_allocation') }}" href="{{ url('powerpanel/number-allocation') }}" class="nav-link nav-toggle">
                                <i class="fa fa-building-o"></i>
                                <span class="title">{{ trans('template.sidebar.number_allocation') }}</span>
                                <span class="{{ $menuArr['number_allocation_selected'] }}"></span>
                            </a>
                        </li>
                        @endif

                        @if(isset($menuArr['can-consultations']) && $menuArr['can-consultations'])                          
                        <li class="nav-item {{ $menuArr['consultations_active'] }} {{ $menuArr['consultations_open'] }}">
                            <a title="{{ trans('template.sidebar.consultations') }}" href="{{ url('powerpanel/consultations') }}" class="nav-link nav-toggle">
                                <i class="fa fa-building-o"></i>
                                <span class="title">{{ trans('template.sidebar.consultations') }}</span>
                                <span class="{{ $menuArr['consultations_selected'] }}"></span>
                            </a>
                        </li>
                        @endif


                        @if((isset($menuArr['can-quick-links-list']) && $menuArr['can-quick-links-list']) || (isset($menuArr['can-links-category-list']) && $menuArr['can-links-category-list']) || (isset($menuArr['can-links-list']) && $menuArr['can-links-list']))
                        <li class="nav-item {{ (isset($menuArr['linkmg']) && $menuArr['linkmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.links') }}" href="javascript:;" class="nav-link nav-toggle">
                                <i class="ri-external-link-line"></i>
                                <span class="title">Links</span>
                                <span class="arrow {{ (isset($menuArr['linkmg']) && $menuArr['linkmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                                @if(isset($menuArr['can-quick-links-list']) && $menuArr['can-quick-links-list'])
                                <li class="nav-item {{ $menuArr['quicklinks_active'] }} {{ $menuArr['quicklinks_open'] }}">
                                    <a title="{{ trans('template.sidebar.quicklinks') }}" href="{{ url('powerpanel/quick-links') }}" class="nav-link nav-toggle">
                                        <i class="ri-external-link-line"></i>
                                        <span class="title">{{ trans('template.sidebar.quicklinks') }}</span>
                                        <span class="{{ $menuArr['quicklinks_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                                @if(isset($menuArr['can-links-category-list']) && $menuArr['can-links-category-list'])
                                <li class="nav-item {{ $menuArr['linkscategory_active'] }} {{ $menuArr['linkscategory_open'] }}">
                                    <a title="{{ trans('template.sidebar.linkscategory') }}" href="{{ url('powerpanel/links-category') }}" class="nav-link nav-toggle">
                                        <i class="ri-external-link-line"></i>
                                        <span class="title">{{ trans('template.sidebar.linkscategory') }}</span>
                                        <span class="{{ $menuArr['linkscategory_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                                @if(isset($menuArr['can-links-list']) && $menuArr['can-links-list'])
                                <li class="nav-item {{ $menuArr['links_active'] }} {{ $menuArr['links_open'] }}">
                                    <a title="{{ trans('template.sidebar.links') }}" href="{{ url('powerpanel/links') }}" class="nav-link nav-toggle">
                                        <i class="ri-external-link-line"></i>
                                        <span class="title">{{ trans('template.sidebar.links') }}</span>
                                        <span class="{{ $menuArr['links_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        <!-- @if((isset($menuArr['can-links-category-list']) && $menuArr['can-links-category-list']) || (isset($menuArr['can-links-list']) && $menuArr['can-links-list']))
                        <li class="nav-item {{ (isset($menuArr['linksmg']) && $menuArr['linksmg']=='active')? 'open active' : '' }}">
                                        <a title="Links" href="javascript:;" class="nav-link nav-toggle">
                                                        <i class="ri-external-link-line"></i>
                                                        <span class="title">Links Management</span>
                                                        <span class="arrow {{ (isset($menuArr['linksmg']) && $menuArr['linksmg']=='active')? 'open' : '' }}"></span>
                                                        <span class=""></span>
                                                        <span class=""></span>
                                        </a>
                                        <ul class="sub-menu">
                                                        @if(isset($menuArr['can-links-category-list']) && $menuArr['can-links-category-list'])
                                                        <li class="nav-item {{ $menuArr['linkscategory_active'] }} {{ $menuArr['linkscategory_open'] }}">
                                                                        <a title="{{ trans('template.sidebar.linkscategory') }}" href="{{ url('powerpanel/links-category') }}" class="nav-link nav-toggle">
                                                                                        <i class="ri-external-link-line"></i>
                                                                                        <span class="title">{{ trans('template.sidebar.linkscategory') }}</span>
                                                                                        <span class="{{ $menuArr['linkscategory_selected'] }}"></span>
                                                                        </a>
                                                        </li>
                                                        @endif
                                                        @if(isset($menuArr['can-links-list']) && $menuArr['can-links-list'])
                                                        <li class="nav-item {{ $menuArr['links_active'] }} {{ $menuArr['links_open'] }}">
                                                                        <a title="{{ trans('template.sidebar.links') }}" href="{{ url('powerpanel/links') }}" class="nav-link nav-toggle">
                                                                                        <i class="ri-external-link-line"></i>
                                                                                        <span class="title">{{ trans('template.sidebar.links') }}</span>
                                                                                        <span class="{{ $menuArr['links_selected'] }}"></span>
                                                                        </a>
                                                        </li>
                                                        @endif
                                        </ul>
                        </li>
                        @endif -->
                        @if((isset($menuArr['can-faq-category-list']) && $menuArr['can-faq-category-list']) || (isset($menuArr['can-faq-list']) && $menuArr['can-faq-list']))
                        <li class="nav-item {{ (isset($menuArr['faqmg']) && $menuArr['faqmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.faq') }}" href="javascript:;" class="nav-link nav-toggle">
                                <i class="fa fa-question-circle-o"></i>
                                <span class="title">FAQs</span>
                                <span class="arrow {{ (isset($menuArr['faqmg']) && $menuArr['faqmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                                @if(isset($menuArr['can-faq-category-list']) && $menuArr['can-faq-category-list'])
                                <li class="nav-item {{ $menuArr['faqcategory_active'] }} {{ $menuArr['faqcategory_open'] }}">
                                    <a title="{{ trans('template.sidebar.faqcategory') }}" href="{{ url('powerpanel/faq-category') }}" class="nav-link nav-toggle">
                                        <i class="fa fa-question-circle-o"></i>
                                        <span class="title">{{ trans('template.sidebar.faqcategory') }}</span>
                                        <span class="{{ $menuArr['faqcategory_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                                @if(isset($menuArr['can-faq-list']) && $menuArr['can-faq-list'])
                                <li class="nav-item {{ $menuArr['faq_active'] }} {{ $menuArr['faq_open'] }}">
                                    <a title="{{ trans('template.sidebar.faq') }}" href="{{ url('powerpanel/faq') }}" class="nav-link nav-toggle">
                                        <i class="fa fa-question-circle-o"></i>
                                        <span class="title">{{ trans('template.sidebar.faq') }}</span>
                                        <span class="{{ $menuArr['faq_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if((isset($menuArr['can-news-category-list']) && $menuArr['can-news-category-list']) || 
                        (isset($menuArr['can-news-list']) && $menuArr['can-news-list']))
                        <li class="nav-item {{ (isset($menuArr['newsmg']) && $menuArr['newsmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.news') }}" href="javascript:;" class="nav-link nav-toggle">
                                <i class="fa fa-newspaper-o"></i>
                                <span class="title">{{ trans('template.sidebar.news') }}</span>
                                <span class="arrow {{ (isset($menuArr['newsmg']) && $menuArr['newsmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                                @if(isset($menuArr['can-news-category-list']) && $menuArr['can-news-category-list'])
                                <li class="nav-item {{ $menuArr['news_category_active'] }} {{ $menuArr['news_category_open'] }}">
                                    <a title="{{ trans('template.sidebar.newscategory') }}" href="{{ url('powerpanel/news-category') }}" class="nav-link nav-toggle">
                                        <i class="fa fa-newspaper-o"></i>
                                        <span class="title">{{ trans('template.sidebar.newscategory') }}</span>
                                        <span class="{{ $menuArr['news_category_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                                @if(isset($menuArr['can-news-list']) && $menuArr['can-news-list'])
                                <li class="nav-item {{ $menuArr['news_active'] }} {{ $menuArr['news_open'] }}">
                                    <a title="{{ trans('template.sidebar.news') }}" href="{{ url('powerpanel/news') }}" class="nav-link nav-toggle">
                                        <i class="fa fa-newspaper-o"></i>
                                        <span class="title">{{ trans('template.sidebar.news') }}</span>
                                        <span class="{{ $menuArr['news_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif

                            </ul>
                        </li>
                        @endif

                        @if(isset($menuArr['can-rfps-list']) && $menuArr['can-rfps-list'])
                            <li class="nav-item {{ $menuArr['rfps_active'] }} {{ $menuArr['rfps_open'] }}">
                                <a title="{{ trans('template.sidebar.rfps') }}" href="{{ url('powerpanel/rfps') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-newspaper-o"></i>
                                    <span class="title">{{ trans('template.sidebar.rfps') }}</span>
                                    <span class="{{ $menuArr['rfps_selected'] }}"></span>
                                </a>
                            </li>
                        @endif


                        @if((isset($menuArr['can-blog-category-list']) && $menuArr['can-blog-category-list']) || (isset($menuArr['can-blogs-list']) && $menuArr['can-blogs-list']))
                        <li class="nav-item {{ (isset($menuArr['blogmg']) && $menuArr['blogmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.blog') }}" href="javascript:;" class="nav-link nav-toggle">
                                <i class="ri-calendar-line-check-o"></i>
                                <span class="title">Blogs</span>
                                <span class="arrow {{ (isset($menuArr['blogmg']) && $menuArr['blogmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                                @if(isset($menuArr['can-blog-category-list']) && $menuArr['can-blog-category-list'])
                                <li class="nav-item {{ $menuArr['blogcategory_active'] }} {{ $menuArr['blogcategory_open'] }}">
                                    <a title="{{ trans('template.sidebar.blogcategory') }}" href="{{ url('powerpanel/blog-category') }}" class="nav-link nav-toggle">
                                        <i class="ri-calendar-line-check-o"></i>
                                        <span class="title">{{ trans('template.sidebar.blogcategory') }}</span>
                                        <span class="{{ $menuArr['blogcategory_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                                @if(isset($menuArr['can-blogs-list']) && $menuArr['can-blogs-list'])
                                <li class="nav-item {{ $menuArr['blogs_active'] }} {{ $menuArr['blogs_open'] }}">
                                    <a title="{{ trans('template.sidebar.blog') }}" href="{{ url('powerpanel/blogs') }}" class="nav-link nav-toggle">
                                        <i class="ri-calendar-line-check-o"></i>
                                        <span class="title">{{ trans('template.sidebar.blog') }}</span>
                                        <span class="{{ $menuArr['blogs_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                                
                            </ul>
                        </li>
                        @endif

                        @if((isset($menuArr['can-decision-list']) && $menuArr['can-decision-list']))
                        <li class="nav-item {{ (isset($menuArr['decisionmg']) && $menuArr['decisionmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.decision') }}" href="javascript:;" class="nav-link nav-toggle">
                                <i class="fa fa-american-sign-language-interpreting"></i>
                                <span class="title">Decision</span>
                                <span class="arrow {{ (isset($menuArr['decisionmg']) && $menuArr['decisionmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                            @if(isset($menuArr['can-decision-category-list']) && $menuArr['can-decision-category-list'])
                                <li class="nav-item {{ $menuArr['decisioncategory_active'] }} {{ $menuArr['decisioncategory_open'] }}">
                                    <a title="{{ trans('template.sidebar.decisioncategory') }}" href="{{ url('powerpanel/decision-category') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-american-sign-language-interpreting"></i>
                                        <span class="title">{{ trans('template.sidebar.decisioncategory') }}</span>
                                        <span class="{{ $menuArr['decisioncategory_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                                @if(isset($menuArr['can-decision-list']) && $menuArr['can-decision-list'])
                                <li class="nav-item {{ $menuArr['decision_active'] }} {{ $menuArr['decision_open'] }}">
                                    <a title="{{ trans('template.sidebar.decision') }}" href="{{ url('powerpanel/decision') }}" class="nav-link nav-toggle">
                                        <i class="fa fa-american-sign-language-interpreting"></i>
                                        <span class="title">{{ trans('template.sidebar.decision') }}</span>
                                        <span class="{{ $menuArr['decision_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif


                        @if(isset($menuArr['can-companies-list']) && $menuArr['can-companies-list'])                          
                        <li class="nav-item {{ $menuArr['companies_active'] }} {{ $menuArr['companies_open'] }}">
                            <a title="{{ trans('template.sidebar.Company') }}" href="{{ url('powerpanel/companies') }}" class="nav-link nav-toggle">
                                <i class="fa fa-building-o"></i>
                                <span class="title">{{ trans('template.sidebar.company') }}</span>
                                <span class="{{ $menuArr['department_selected'] }}"></span>
                            </a>
                        </li>
                        @endif

                        @if((isset($menuArr['can-event-category-list']) && $menuArr['can-event-category-list']) || (isset($menuArr['can-events-list']) && $menuArr['can-events-list']))
                        <li class="nav-item {{ (isset($menuArr['eventmg']) && $menuArr['eventmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.events') }}" href="javascript:;" class="nav-link nav-toggle">
                                <i class="ri-calendar-line-check-o"></i>
                                <span class="title">Events</span>
                                <span class="arrow {{ (isset($menuArr['eventmg']) && $menuArr['eventmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                                @if(isset($menuArr['can-event-category-list']) && $menuArr['can-event-category-list'])
                                <li class="nav-item {{ $menuArr['eventcategory_active'] }} {{ $menuArr['eventcategory_open'] }}">
                                    <a title="{{ trans('template.sidebar.eventcategory') }}" href="{{ url('powerpanel/event-category') }}" class="nav-link nav-toggle">
                                        <i class="ri-calendar-line-check-o"></i>
                                        <span class="title">{{ trans('template.sidebar.eventcategory') }}</span>
                                        <span class="{{ $menuArr['eventcategory_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                                @if(isset($menuArr['can-events-list']) && $menuArr['can-events-list'])
                                <li class="nav-item {{ $menuArr['events_active'] }} {{ $menuArr['events_open'] }}">
                                    <a title="{{ trans('template.sidebar.events') }}" href="{{ url('powerpanel/events') }}" class="nav-link nav-toggle">
                                        <i class="ri-calendar-line-check-o"></i>
                                        <span class="title">{{ trans('template.sidebar.events') }}</span>
                                        <span class="{{ $menuArr['events_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(
                        (isset($menuArr['can-publications-list']) && $menuArr['can-publications-list']) ||
                        (isset($menuArr['can-publications-category-list']) && $menuArr['can-publications-category-list'])
                        )
                        <li class="nav-item {{ (isset($menuArr['pubtmg']) && $menuArr['pubtmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.publications') }}" href="javascript:;" class="nav-link nav-toggle">
                                <i class="fa fa-bullhorn"></i>
                                <span class="title"> {{ trans('template.sidebar.publications') }}</span>
                                <span class="arrow {{ (isset($menuArr['pubtmg']) && $menuArr['pubtmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                                @if(isset($menuArr['can-publications-category-list']) && $menuArr['can-publications-category-list'])
                                <li class="nav-item {{ $menuArr['publications_category_active'] }} {{ $menuArr['publications_category_open'] }}">
                                    <a title="{{ trans('template.sidebar.publicationscategory') }}" href="{{ url('powerpanel/publications-category') }}" class="nav-link nav-toggle">
                                        <i class="fa fa-bullhorn"></i>
                                        <span class="title">{{ trans('template.sidebar.publicationscategory') }}</span>
                                        <span class="{{ $menuArr['publications_category_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                                @if(isset($menuArr['can-publications-list']) && $menuArr['can-publications-list'])
                                <li class="nav-item {{ $menuArr['publications_active'] }} {{ $menuArr['publications_open'] }}">
                                    <a title="{{ trans('template.sidebar.publications') }}" href="{{ url('powerpanel/publications') }}" class="nav-link nav-toggle">
                                        <i class="fa fa-bullhorn"></i>
                                        <span class="title">{{ trans('template.sidebar.publications') }}</span>
                                        <span class="{{ $menuArr['publications_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                         @if(
                        (isset($menuArr['can-service-list']) && $menuArr['can-service-list']) ||
                        (isset($menuArr['can-service-category-list']) && $menuArr['can-service-category-list'])
                        )
                        <li class="nav-item {{ (isset($menuArr['sertmg']) && $menuArr['sertmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.services') }}" href="javascript:;" class="nav-link nav-toggle">
                                <i class="fa fa-cogs"></i>
                                <span class="title"> {{ trans('template.sidebar.services') }}</span>
                                <span class="arrow {{ (isset($menuArr['sertmg']) && $menuArr['sertmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                               @if(isset($menuArr['can-service-category-list']) && $menuArr['can-service-category-list'])
						<li class="nav-item {{ $menuArr['service_category_active'] }} {{ $menuArr['service_category_open'] }}">
							<a title="{{ trans('template.sidebar.servicescategory') }}" href="{{ url('powerpanel/service-category') }}" class="nav-link nav-toggle">
								<i class="fa fa-cogs"></i>
								<span class="title">{{ trans('template.sidebar.servicescategory') }}</span>
								<span class="{{ $menuArr['service_category_selected'] }}"></span>
							</a>
						</li>
						@endif
                               @if(isset($menuArr['can-service-list']) && $menuArr['can-service-list'])
						<li class="nav-item {{ $menuArr['services_active'] }} {{ $menuArr['services_open'] }}">
							<a title="{{ trans('template.sidebar.service') }}" href="{{ url('powerpanel/service') }}" class="nav-link nav-toggle">
								<i class="fa fa-cogs"></i>
								<span class="title">{{ trans('template.sidebar.service') }}</span>
								<span class="{{ $menuArr['services_selected'] }}"></span>
							</a>
						</li>
						@endif
                            </ul>
                        </li>
                        @endif
                      @if(
                        (isset($menuArr['can-public-record-category-list']) && $menuArr['can-public-record-category-list'])||
                        (isset($menuArr['can-public-record-list']) && $menuArr['can-public-record-list'])
                        )
                        <li class="nav-item {{ (isset($menuArr['publtmg']) && $menuArr['publtmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.publicrecord') }}" href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-street-view"></i>
                                <span class="title"> {{ trans('template.sidebar.publicrecord') }}</span>
                                <span class="arrow {{ (isset($menuArr['publtmg']) && $menuArr['publtmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                                @if(isset($menuArr['can-public-record-category-list']) && $menuArr['can-public-record-category-list'])
                                <li class="nav-item {{ $menuArr['public-record-category_active'] }} {{ $menuArr['public-record-category_open'] }}">
                                    <a title="{{ trans('template.sidebar.publicrecordcategory') }}" href="{{ url('powerpanel/public-record-category') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-street-view"></i>
                                        <span class="title">{{ trans('template.sidebar.publicrecordcategory') }}</span>
                                        <span class="{{ $menuArr['public-record-category_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                                @if(isset($menuArr['can-public-record-list']) && $menuArr['can-public-record-list'])
                                <li class="nav-item {{ $menuArr['public-record_active'] }} {{ $menuArr['public-record_open'] }}">
                                    <a title="{{ trans('template.sidebar.publicrecordcategory') }}" href="{{ url('powerpanel/public-record') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-street-view"></i>
                                        <span class="title">{{ trans('template.sidebar.publicrecord') }}</span>
                                        <span class="{{ $menuArr['public-record_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                           
                        </li>
                        @endif
                          @if(
                        (isset($menuArr['can-products-list']) && $menuArr['can-products-list']) ||
                        (isset($menuArr['can-products-category-list']) && $menuArr['can-products-category-list'])
                        )
                        <li class="nav-item {{ (isset($menuArr['pcontmg']) && $menuArr['pcontmg']=='active')? 'open active' : '' }}">
                            <a title="Products" href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-graph"></i>
                                <span class="title"> Products</span>
                                <span class="arrow {{ (isset($menuArr['pcontmg']) && $menuArr['pcontmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                             @if(isset($menuArr['can-products-category-list']) && $menuArr['can-products-category-list'])
                        <li class="nav-item {{ $menuArr['products_category_active'] }} {{ $menuArr['products_category_open'] }}">
                            <a title="{{ trans('template.sidebar.productscategory') }}" href="{{ url('powerpanel/product-category') }}" class="nav-link nav-toggle">
                                <i class="icon-graph"></i>
                                <span class="title">{{ trans('template.sidebar.productscategory') }}</span>
                                <span class="{{ $menuArr['products_category_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                            @if(isset($menuArr['can-products-list']) && $menuArr['can-products-list'])
                        <li class="nav-item {{ $menuArr['products_active'] }} {{ $menuArr['products_open'] }}">
                            <a title="Products" href="{{ url('powerpanel/products') }}" class="nav-link nav-toggle">
                                <i class="icon-graph"></i>
                                <span class="title">Products</span>
                                <span class="{{ $menuArr['products_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                            </ul>
                        </li>
                        @endif

                         @if(
                        (isset($menuArr['can-sponsor-list']) && $menuArr['can-sponsor-list']) ||
                        (isset($menuArr['can-sponsor-category-list']) && $menuArr['can-sponsor-category-list'])
                        )
                        <li class="nav-item {{ (isset($menuArr['sponmg']) && $menuArr['sponmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.sponsor') }}" href="javascript:;" class="nav-link nav-toggle">
                                <i class="fa fa-handshake-o"></i>
                                <span class="title"> {{ trans('template.sidebar.sponser') }}</span>
                                <span class="arrow {{ (isset($menuArr['sponmg']) && $menuArr['sponmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                            @if(isset($menuArr['can-sponsor-category-list']) && $menuArr['can-sponsor-category-list'])
						<li class="nav-item {{ $menuArr['sponsor_category_active'] }} {{ $menuArr['sponsor_category_open'] }}">
							<a title="{{ trans('template.sidebar.sponsorcategory') }}" href="{{ url('powerpanel/sponsor-category') }}" class="nav-link nav-toggle">
								<i class="fa fa-handshake-o"></i>
								<span class="title">{{ trans('template.sidebar.sponsorcategory') }}</span>
								<span class="{{ $menuArr['sponsor_category_selected'] }}"></span>
							</a>
						</li>
						@endif
                        @if(isset($menuArr['can-sponsor-list']) && $menuArr['can-sponsor-list'])
						<li class="nav-item {{ $menuArr['sponsor_active'] }} {{ $menuArr['sponsor_open'] }}">
							<a title="{{ trans('template.sidebar.sponser') }}" href="{{ url('powerpanel/sponsor') }}" class="nav-link nav-toggle">
								<i class="fa fa-handshake-o" style="font-size:15px"></i>
								<span class="title">{{ trans('template.sidebar.sponser') }}</span>
								<span class="{{ $menuArr['sponsor_selected'] }}"></span>
							</a>
						</li>
						@endif
                            </ul>
                        </li>
                        @endif


                         @if(
                        (isset($menuArr['can-shows-list']) && $menuArr['can-shows-list']) ||
                        (isset($menuArr['can-show-category-list']) && $menuArr['can-show-category-list'])
                        )
                        <li class="nav-item {{ (isset($menuArr['scontmg']) && $menuArr['scontmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.shows') }}" href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-film"></i>
                                <span class="title"> {{ trans('template.sidebar.shows') }}</span>
                                <span class="arrow {{ (isset($menuArr['scontmg']) && $menuArr['scontmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                          @if(isset($menuArr['can-show-category-list']) && $menuArr['can-show-category-list'])
						<li class="nav-item {{ $menuArr['show_category_active'] }} {{ $menuArr['show_category_open'] }}">
							<a title="{{ trans('template.sidebar.showcategory') }}" href="{{ url('powerpanel/show-category') }}" class="nav-link nav-toggle">
								<i class="icon-film"></i>
								<span class="title">{{ trans('template.sidebar.showcategory') }}</span>
								<span class="{{ $menuArr['show_category_selected'] }}"></span>
							</a>
						</li>
						@endif
                       @if(isset($menuArr['can-shows-list']) && $menuArr['can-shows-list'])
						<li class="nav-item {{ $menuArr['shows_active'] }} {{ $menuArr['shows_open'] }}">
							<a title="{{ trans('template.sidebar.shows') }}" href="{{ url('powerpanel/shows') }}" class="nav-link nav-toggle">
								<i class="icon-film"></i>
								<span class="title">{{ trans('template.sidebar.shows') }}</span>
								<span class="{{ $menuArr['shows_selected'] }}"></span>
							</a>
						</li>
						@endif
                            </ul>
                        </li>
                        @endif

                        @if(
                        (isset($menuArr['can-client-list']) && $menuArr['can-client-list']) ||
                        (isset($menuArr['can-clients-category-list']) && $menuArr['can-clients-category-list'])
                        )
                        <li class="nav-item {{ (isset($menuArr['catmg']) && $menuArr['catmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.client') }}" href="javascript:;" class="nav-link nav-toggle">
                                <i class="fa fa-group"></i>
                                <span class="title"> {{ trans('template.sidebar.client') }}</span>
                                <span class="arrow {{ (isset($menuArr['catmg']) && $menuArr['catmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                          @if(isset($menuArr['can-clients-category-list']) && $menuArr['can-clients-category-list'])
                        <li class="nav-item {{ $menuArr['client_category_active'] }} {{ $menuArr['client_category_open'] }}">
                            <a title="{{ trans('template.sidebar.clientcategory') }}" href="{{ url('powerpanel/client-category') }}" class="nav-link nav-toggle">
                                <i class="fa fa-group"></i>
                                <span class="title">{{ trans('template.sidebar.clientcategory') }}</span>
                                <span class="{{ $menuArr['client_category_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                      @if(isset($menuArr['can-client-list']) && $menuArr['can-client-list'])
                        <li class="nav-item {{ $menuArr['client_active'] }} {{ $menuArr['client_open'] }}">
                            <a title="{{ trans('template.sidebar.client') }}" href="{{ url('powerpanel/client') }}" class="nav-link nav-toggle">
                                <i class="fa fa-group" style="font-size:15px"></i>
                                <span class="title">{{ trans('template.sidebar.client') }}</span>
                                <span class="{{ $menuArr['client_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                            </ul>
                        </li>
                        @endif

                        @if((isset($menuArr['can-projects-category-list']) && $menuArr['can-projects-category-list']) || (isset($menuArr['can-projects-list']) && $menuArr['can-projects-list']))
				<li class="nav-item {{ (isset($menuArr['realmg']) && $menuArr['realmg']=='active')? 'open active' : '' }}">
					<a title="Real Estate" href="javascript:;" class="nav-link nav-toggle">
						<i class="icon-puzzle"></i>
						<span class="title">Real Estate</span>
						<span class="arrow {{ (isset($menuArr['realmg']) && $menuArr['realmg']=='active')? 'open active' : '' }}"></span>
						<span class=""></span>
						<span class=""></span>
					</a>
					<ul class="sub-menu">
						@if(isset($menuArr['can-projects-list']) && $menuArr['can-projects-list'])
						<li class="nav-item {{ $menuArr['projects_active'] }} {{ $menuArr['projects_open'] }}">
							<a title="Projects" href="{{ url('powerpanel/projects') }}" class="nav-link nav-toggle">
								<i class="icon-graph"></i>
								<span class="title">Projects</span>
								<span class="{{ $menuArr['projects_selected'] }}"></span>
							</a>
						</li>
						@endif
						
						@if(isset($menuArr['can-projects-category-list']) && $menuArr['can-projects-category-list'])
						<li class="nav-item {{ $menuArr['projects_category_active'] }} {{ $menuArr['projects_category_open'] }}">
							<a title="{{ trans('template.sidebar.projectscategory') }}" href="{{ url('powerpanel/project-category') }}" class="nav-link nav-toggle">
								<i class="icon-graph"></i>
								<span class="title">{{ trans('template.sidebar.projectscategory') }}</span>
								<span class="{{ $menuArr['projects_category_selected'] }}"></span>
							</a>
						</li>
						@endif
						
					</ul>
				</li>
				@endif

                        @if(
                        (isset($menuArr['can-photo-album-category-list']) && $menuArr['can-photo-album-category-list']) ||
                        (isset($menuArr['can-photo-album-list']) && $menuArr['can-photo-album-list']) ||
                        (isset($menuArr['can-photo-gallery-list']) && $menuArr['can-photo-gallery-list'])
                        )
                        <li class="nav-item {{ (isset($menuArr['photoalmg']) && $menuArr['photoalmg']=='active')? 'open active' : '' }}">
                            <a title="{{ trans('template.sidebar.photogallery') }}" href="javascript:;" class="nav-link nav-toggle">
                                <i class="fa fa-camera-retro"></i>
                                <span class="title"> {{ trans('template.sidebar.photoalbum') }}</span>
                                <span class="arrow {{ (isset($menuArr['photoalmg']) && $menuArr['photoalmg']=='active')? 'open' : '' }}"></span>
                                <span class=""></span>
                                <span class=""></span>
                            </a>
                            <ul class="sub-menu">
                                @if(isset($menuArr['can-photo-album-list']) && $menuArr['can-photo-album-list'])
                                <li class="nav-item {{ $menuArr['photo_album_active'] }} {{ $menuArr['photo_album_open'] }}">
                                    <a title="{{ trans('template.sidebar.photoalbum') }}" href="{{ url('powerpanel/photo-album') }}" class="nav-link nav-toggle">
                                        <i class="fa fa-camera-retro"></i>
                                        <span class="title">{{ trans('template.sidebar.photoalbum') }}</span>
                                        <span class="{{ $menuArr['photo_album_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif
                                @if(isset($menuArr['can-photo-gallery-list']) && $menuArr['can-photo-gallery-list'])
                                <li class="nav-item {{ $menuArr['photo_gallery_active'] }} {{ $menuArr['photo_gallery_open'] }}">
                                    <a title="{{ trans('template.sidebar.photogallery') }}" href="{{ url('powerpanel/photo-gallery') }}" class="nav-link nav-toggle">
                                        <i class="fa fa-camera-retro"></i>
                                        <span class="title">{{ trans('template.sidebar.photogallery') }}</span>
                                        <span class="{{ $menuArr['photo_gallery_selected'] }}"></span>
                                    </a>
                                </li>
                                @endif

                            </ul>
                        </li>
                        @endif @if(isset($menuArr['can-video-gallery-list']) && $menuArr['can-video-gallery-list'])
                        <li class="nav-item {{ $menuArr['video_gallery_active'] }} {{ $menuArr['video_gallery_open'] }}">
                            <a title="{{ trans('template.sidebar.video_gallery') }}" href="{{ url('powerpanel/video-gallery') }}" class="nav-link nav-toggle">
                                <i class="fa fa-video-camera"></i>
                                <span class="title">{{ trans('template.sidebar.video_gallery') }}</span>
                                <span class="{{ $menuArr['video_gallery_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-testimonial-list']) && $menuArr['can-testimonial-list'])
                        <li class="nav-item {{ $menuArr['testimonial_active'] }} {{ $menuArr['testimonial_open'] }}">
                            <a title="{{ trans('template.sidebar.testimonials') }}" href="{{ url('powerpanel/testimonial') }}" class="nav-link nav-toggle">
                                <i class="icon-bubbles"></i>
                                <span class="title">{{ trans('template.sidebar.testimonials') }}</span>
                                <span class="{{ $menuArr['testimonial_selected'] }}"></span>
                            </a>
                        </li>
                        @endif @if(isset($menuArr['can-advertise-list']) && $menuArr['can-advertise-list'])
				<li class="nav-item {{$menuArr['ads_active']}}">
					<a title="{{ trans('template.sidebar.advertisements') }}" href="{{ url('powerpanel/advertise') }}" class="nav-link ">
						<i class="fa fa-assistive-listening-systems"></i>
						<span class="title">{{ trans('template.sidebar.advertisements') }}</span>
						<span class=" {{$menuArr['ads_selected']}}"></span>
					</a>
				</li>
				@endif
                        @if(isset($menuArr['can-team-list']) && $menuArr['can-team-list'])
						<li class="nav-item {{ $menuArr['team_active'] }} {{ $menuArr['team_open'] }}">
							<a title="{{ trans('template.sidebar.team') }}" href="{{ url('powerpanel/team') }}" class="nav-link nav-toggle">
								<i class="fa fa-user-o"></i>
								<span class="title">{{ trans('template.sidebar.team') }}</span>
								<span class="{{ $menuArr['team_selected'] }}"></span>
							</a>
						</li>
						@endif
                        @if(isset($menuArr['can-payonline-list']) && $menuArr['can-payonline-list'])
                            <li class="nav-item {{ $menuArr['payonline_active'] }} {{ $menuArr['payonline_open'] }}">
                                <a title="{{ trans('template.sidebar.payonline') }}" href="{{ url('powerpanel/payonline') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-user-o"></i>
                                    <span class="title">{{ trans('template.sidebar.payonline') }}</span>
                                    <span class="{{ $menuArr['payonline_selected'] }}"></span>
                                </a>
                            </li>
						@endif
                        @if(isset($menuArr['can-careers-list']) && $menuArr['can-careers-list'])
                        <li class="nav-item {{ $menuArr['careers_active'] }} {{ $menuArr['careers_open'] }}">
                            <a title="{{ trans('template.sidebar.careers') }}" href="{{ url('powerpanel/careers') }}" class="nav-link nav-toggle">
                                <i class="fa fa-graduation-cap"></i>
                                <span class="title">{{ trans('template.sidebar.careers') }}</span>
                                <span class="{{ $menuArr['careers_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        
                        @if (Config::get('Constant.DEFAULT_ONLINEPOLLINGFORM') == 'Y')
                            @if(isset($menuArr['can-online-polling-list']) && $menuArr['can-online-polling-list'])       
                                <li class="nav-item {{ $menuArr['online_polling_active'] }} {{ $menuArr['online_polling_open'] }}">
                                    <a title="{{ trans('template.sidebar.onlinepolling') }}" href="{{ url('powerpanel/online-polling') }}" class="nav-link nav-toggle">
                                        <i class="fa fa-hand-o-up"></i>
                                        <span class="title">{{ trans('template.sidebar.onlinepolling') }}</span>
                                        <span class="{{ $menuArr['online_polling_selected'] }}"></span>
                                    </a>
                                </li>
                            @endif
                        @endif

                      @if(isset($menuArr['can-complaint-services-list']) && $menuArr['can-complaint-services-list'])
                        <li class="nav-item {{ $menuArr['complaint-services_active'] }} {{ $menuArr['complaint-services_open'] }}">
                            <a title="{{ trans('template.sidebar.complaintservices') }}" href="{{ url('powerpanel/complaint-services') }}" class="nav-link nav-toggle">
                                 <i class="fa fa-cogs"></i>
                                <span class="title">{{ trans('template.sidebar.complaintservices') }}</span>
                                <span class="{{ $menuArr['complaint-services_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-fmbroadcasting-list']) && $menuArr['can-fmbroadcasting-list'])
                        <li class="nav-item {{ $menuArr['fmbroadcasting_active'] }} {{ $menuArr['fmbroadcasting_open'] }}">
                            <a title="{{ trans('template.sidebar.fmbroadcasting') }}" href="{{ url('powerpanel/fmbroadcasting') }}" class="nav-link nav-toggle">
                                <i class="fa fa-bullhorn"></i>
                                <span class="title">{{ trans('template.sidebar.fmbroadcasting') }}</span>
                                <span class="{{ $menuArr['fmbroadcasting_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-boardofdirectors-list']) && $menuArr['can-boardofdirectors-list'])
                        <li class="nav-item {{ $menuArr['boardofdirectors_active'] }} {{ $menuArr['boardofdirectors_open'] }}">
                            <a title="{{ trans('template.sidebar.boardofdirectors') }}" href="{{ url('powerpanel/boardofdirectors') }}" class="nav-link nav-toggle">
                              <i class="fa fa-user"></i>
                                <span class="title">{{ trans('template.sidebar.boardofdirectors') }}</span>
                                <span class="{{ $menuArr['boardofdirectors_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-register-application-list']) && $menuArr['can-register-application-list'])
                        <li class="nav-item {{ $menuArr['register-application_active'] }} {{ $menuArr['register-application_open'] }}">
                            <a title="{{ trans('template.sidebar.register_application') }}" href="{{ url('powerpanel/register-application') }}" class="nav-link nav-toggle">
                             <i class="fa fa-registered"></i>
                                <span class="title">{{ trans('template.sidebar.register_application') }}</span>
                                <span class="{{ $menuArr['register-application_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-licence-register-list']) && $menuArr['can-licence-register-list'])
                        <li class="nav-item {{ $menuArr['licence-register_active'] }} {{ $menuArr['licence-register_open'] }}">
                            <a title="{{ trans('template.sidebar.licence-register') }}" href="{{ url('powerpanel/licence-register') }}" class="nav-link nav-toggle">
                            <i class="fa fa-id-badge"></i>
                                <span class="title">{{ trans('template.sidebar.licence-register') }}</span>
                                <span class="{{ $menuArr['licence-register_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-forms-and-fees-list']) && $menuArr['can-forms-and-fees-list'])
                        <li class="nav-item {{ $menuArr['forms-and-fees_active'] }} {{ $menuArr['forms-and-fees_open'] }}">
                            <a title="{{ trans('template.sidebar.forms-and-fees') }}" href="{{ url('powerpanel/forms-and-fees') }}" class="nav-link nav-toggle">
                            <i class="fa fa-file-text-o"></i>
                                <span class="title">{{ trans('template.sidebar.forms-and-fees') }}</span>
                                <span class="{{ $menuArr['forms-and-fees_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-popup-list']) && $menuArr['can-popup-list'])
                        <li class="nav-item {{ $menuArr['popup_active'] }} {{ $menuArr['popup_open'] }}">
                            <a title="{{ trans('template.sidebar.popup') }}" href="{{ url('powerpanel/popup') }}" class="nav-link nav-toggle">
                            <i class="fa fa-product-hunt"></i>
                                <span class="title">{{ trans('template.sidebar.popup') }}</span>
                                <span class="{{ $menuArr['popup_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-candwservice-list']) && $menuArr['can-candwservice-list'])
                        <li class="nav-item {{ $menuArr['candwservice_active'] }} {{ $menuArr['candwservice_open'] }}">
                            <a title="{{ trans('template.sidebar.candwservice') }}" href="{{ url('powerpanel/candwservice') }}" class="nav-link nav-toggle">
                            <i class="fa fa-cogs"></i>
                                <span class="title">{{ trans('template.sidebar.candwservice') }}</span>
                                <span class="{{ $menuArr['candwservice_selected'] }}"></span>
                            </a>
                        </li>
                        @endif



                        @if(isset($menuArr['can-tag-list']) && $menuArr['can-tag-list'])       
                        <li class="nav-item {{ $menuArr['tag_active'] }} {{ $menuArr['tag_open'] }}">
                            <a title="{{ trans('template.sidebar.tag') }}" href="{{ url('powerpanel/tag') }}" class="nav-link nav-toggle">
                                <i class="fa fa-tags"></i>
                                <span class="title">{{ trans('template.sidebar.tag') }}</span>
                                <span class="{{ $menuArr['tag_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-maintenance-list']) && $menuArr['can-maintenance-list'])       
                        <li class="nav-item {{ $menuArr['maintenance_active'] }} {{ $menuArr['maintenance_open'] }}">
                            <a title="{{ trans('template.sidebar.maintenance') }}" href="{{ url('powerpanel/maintenance') }}" class="nav-link nav-toggle">
                                <i class="fa fa-book"></i>
                                <span class="title">{{ trans('template.sidebar.maintenance') }}</span>
                                <span class="{{ $menuArr['maintenance_selected'] }}"></span>
                            </a>
                        </li>
                        @endif
                        @if(isset($menuArr['can-rfp-list']) && $menuArr['can-rfp-list'])
                        <li class="nav-item {{ $menuArr['rfp_active'] }} {{ $menuArr['rfp_open'] }}">
                            <a title="{{ trans('template.sidebar.rfp') }}" href="{{ url('powerpanel/rfp') }}" class="nav-link nav-toggle">
                                <i class="fa fa-newspaper-o"></i>
                                <span class="title">{{ trans('template.sidebar.rfp') }}</span>
                                <span class="{{ $menuArr['rfp_selected'] }}"></span>
                            </a>
                        </li>
                        @endif

                    </ul>
                </li>
                @endif
            </ul>
            </li>
            @endif

            @if(isset($menuArr['can-media-manager-list']) && $menuArr['can-media-manager-list'])
            <li class="nav-item {{ $menuArr['mediamanager_active'] }} {{ $menuArr['mediamanager_open'] }}">
                <a title="Media Manager" href="{{ url('powerpanel/media-manager') }}" class="nav-link nav-toggle">
                    <i class="la la-camera-retro"></i>
                    <span class="title">Media Manager</span>
                    <span class="{{ $menuArr['mediamanager_selected'] }}"></span>
                </a>
            </li>
            @endif
            @if (Config::get('Constant.DEFAULT_MESSAGINGSYSTEM') == 'Y')
            @if(isset($menuArr['can-messagingsystem-list']) && $menuArr['can-messagingsystem-list'])       
            <li class="nav-item {{ $menuArr['messagingsystem_active'] }} {{ $menuArr['messagingsystem_open'] }}">
                <a title="{{ trans('template.sidebar.messagingsystem') }}" href="{{ url('powerpanel/messagingsystem') }}" class="nav-link nav-toggle">
                    <i class="la la-wechat"></i>
                    <span class="title">{{ trans('template.sidebar.messagingsystem') }}</span>
                    <span class="{{ $menuArr['messagingsystem_selected'] }}"></span>
                </a>
            </li>
            @endif
            @endif
            @if (Config::get('Constant.DEFAULT_FORMBUILDER') == 'Y')
            @if(isset($menuArr['can-formbuilder-list']) && $menuArr['can-formbuilder-list'])       
            <li class="nav-item {{ $menuArr['formbuilder_active'] }} {{ $menuArr['formbuilder_open'] }}">
                <a title="{{ trans('template.sidebar.formbuilder') }}" href="{{ url('powerpanel/formbuilder') }}" class="nav-link nav-toggle">
                    <i class="la la-server"></i>
                    <span class="title">{{ trans('template.sidebar.formbuilder') }}</span>
                    <span class="{{ $menuArr['formbuilder_selected'] }}"></span>
                </a>
            </li>
            @endif
            @endif
            @if (Config::get('Constant.DEFAULT_PAGETEMPLATE') == 'Y')
            @if(isset($menuArr['can-page_template-list']) && $menuArr['can-page_template-list'])       
            <li class="nav-item {{ $menuArr['page_template_active'] }} {{ $menuArr['page_template_open'] }}">
                <a title="{{ trans('template.sidebar.pagetemplate') }}" href="{{ url('powerpanel/page_template') }}" class="nav-link nav-toggle">
                    <i class="la la-file-text-o"></i>
                    <span class="title">{{ trans('template.sidebar.pagetemplate') }}</span>
                    <span class="{{ $menuArr['page_template_selected'] }}"></span>
                </a>
            </li>
            @endif
            @endif
            @if(isset($menuArr['can-submit-tickets-list']) && $menuArr['can-submit-tickets-list'])
            <li class="nav-item {{ $menuArr['tickets_active'] }}">
                <a title="{{ trans('template.sidebar.submitticketslead') }}" href="{{ url('powerpanel/submit-tickets') }}" class="nav-link nav-toggle">
                    <i class="la la-ticket"></i>
                    <span class="title">{{ trans('template.sidebar.submitticketslead') }}</span>
                    <span class="{{ $menuArr['tickets_selected'] }}"></span>
                </a>
            </li>
            @endif

            @if(
            (isset($menuArr['can-contact-us-list']) && $menuArr['can-contact-us-list']) ||
            (isset($menuArr['can-complaint-list']) && $menuArr['can-complaint-list']) ||
            (isset($menuArr['can-appointment-lead-list']) && $menuArr['can-appointment-lead-list']) ||
            (isset($menuArr['can-getdemo-leads-list']) && $menuArr['can-getdemo-leads-list']) ||
            (isset($menuArr['can-newsletter-lead-list']) && $menuArr['can-newsletter-lead-list'])||
            (isset($menuArr['can-formbuilder-lead-list']) && $menuArr['can-formbuilder-lead-list'])
            )
            <li class="nav-item {{ (isset($menuArr['leadmg']) && $menuArr['leadmg']=='active')? 'open active' : '' }}">
                <a title="{{ trans('template.sidebar.leads') }}" href="javascript:;" class="nav-link nav-toggle">
                    <i class="la la-list-ol"></i>
                    <span class="title">{{ trans('template.sidebar.leads') }}</span>
                    <span class="arrow {{ (isset($menuArr['leadmg']) && $menuArr['leadmg']=='active')? 'open' : '' }}"></span>
                    <span class=""></span>
                    <span class=""></span>
                </a>
                <ul class="sub-menu">
                    @if(isset($menuArr['can-complaint-list']) && $menuArr['can-complaint-list'])
                    <li class="nav-item {{ $menuArr['complaint_active'] }}">
                        <a title="{{ trans('template.sidebar.complaintlead') }}" href="{{ url('powerpanel/complaint') }}" class="nav-link ">
                            <i class="fa fa-phone"></i>
                            <span class="title">{{ trans('template.sidebar.complaintlead') }}</span>
                            <span class="{{ $menuArr['complaint_selected'] }}"></span>
                        </a>
                    </li>
                    @endif
                    @if(isset($menuArr['can-contact-us-list']) && $menuArr['can-contact-us-list'])
                    <li class="nav-item {{ $menuArr['contact_active'] }}">
                        <a title="{{ trans('template.sidebar.contactuslead') }}" href="{{ url('powerpanel/contact-us') }}" class="nav-link ">
                            <i class="fa fa-phone"></i>
                            <span class="title">{{ trans('template.sidebar.contactuslead') }}</span>
                            <span class="{{ $menuArr['contact_selected'] }}"></span>
                        </a>
                    </li>
                    @endif
                    @if(isset($menuArr['can-appointment-lead-list']) && $menuArr['can-appointment-lead-list'])
						<li class="nav-item {{ $menuArr['appointment_active'] }}">
							<a title="{{ trans('template.appointmentleadModule.bookanappointment') }}" href="{{ url('powerpanel/appointment-lead') }}" class="nav-link ">
								<i class="fa fa-phone"></i>
								<span class="title">{{ trans('template.appointmentleadModule.bookanappointment') }}</span>
								<span class="{{ $menuArr['appointment_selected'] }}"></span>
							</a>
						</li>
						@endif
                    @if(isset($menuArr['can-getdemo-leads-list']) && $menuArr['can-getdemo-leads-list'])
                    <li class="nav-item {{ $menuArr['getdemo_active'] }}">
                        <a title="{{ trans('template.sidebar.getdemolead') }}" href="{{ url('powerpanel/getdemo-leads') }}" class="nav-link ">
                            <i class="fa fa-bullhorn"></i>
                            <span class="title">{{ trans('template.sidebar.getdemolead') }}</span>
                            <span class="{{ $menuArr['getdemo_selected'] }}"></span>
                        </a>
                    </li>
                    @endif
                    @if(isset($menuArr['can-newsletter-lead-list']) && $menuArr['can-newsletter-lead-list'])
                    <li class="nav-item {{ $menuArr['news_letter_active'] }}">
                        <a title="{{ trans('template.sidebar.newsletterleads') }}" href="{{ url('powerpanel/newsletter-lead') }}" class="nav-link ">
                            <i class="fa fa-newspaper-o"></i>
                            <span class="title">{{ trans('template.sidebar.newsletterleads') }}</span>
                            <span class="{{ $menuArr['news_letter_selected'] }}"></span>
                        </a>
                    </li>
                    @endif
                    @if(isset($menuArr['can-formbuilder-lead-list']) && $menuArr['can-formbuilder-lead-list'])
                    <li class="nav-item {{ $menuArr['form_builder_active'] }}">
                        <a title="{{ trans('template.sidebar.formbuilderleads') }}" href="{{ url('powerpanel/formbuilder-lead') }}" class="nav-link ">
                            <i class="fa fa-file-text-o"></i>
                            <span class="title">{{ trans('template.sidebar.formbuilderleads') }}</span>
                            <span class="{{ $menuArr['form_builder_selected'] }}"></span>
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif

            @if(
            (isset($menuArr['can-roles-list']) && $menuArr['can-roles-list']) ||
            (isset($menuArr['can-users-list']) && $menuArr['can-users-list']) ||
            (isset($menuArr['can-workflow-list']) && $menuArr['can-workflow-list'])
            )
            <li class="nav-item {{ (isset($menuArr['usermg']) && $menuArr['usermg']=='active')? 'open active' : '' }}">
                <a title="{{ trans('template.sidebar.users') }}" href="javascript:;" class="nav-link nav-toggle">
                    <i class="la la-male"></i>
                    <span class="title">{{ trans('template.sidebar.users') }}</span>
                    <span class="arrow {{ (isset($menuArr['usermg']) && $menuArr['usermg']=='active')? 'open' : '' }}"></span>
                    <span class=""></span>
                    <span class=""></span>
                </a>
                <ul class="sub-menu">
                    @if(isset($menuArr['can-roles-list']) && $menuArr['can-roles-list'])
                    <li class="nav-item {{ $menuArr['roles_active'] }} {{ $menuArr['roles_open'] }}">
                        <a title="{{ trans('template.sidebar.rolemanager') }}" href="{{ url('/powerpanel/roles') }}" class="nav-link ">
                            <i class="icon-docs"></i>
                            <span class="title">{{ trans('template.sidebar.rolemanager') }}</span>
                            <span class="{{ $menuArr['roles_selected'] }}"></span>
                        </a>
                    </li>
                    @endif
                    @if(isset($menuArr['can-workflow-list']) && $menuArr['can-workflow-list'])						
                    <li class="nav-item {{ $menuArr['workflow_active'] }} {{ $menuArr['workflow_open'] }}">
                        <a title="{{ trans('template.sidebar.workflow') }}" href="{{ url('powerpanel/workflow') }}" class="nav-link nav-toggle">
                            <i class="fa fa-sitemap"></i>
                            <span class="title">{{ trans('template.sidebar.workflow') }}</span>
                            <span class="{{ $menuArr['workflow_selected'] }}"></span>
                        </a>
                    </li>
                    @endif
                    @if(isset($menuArr['can-users-list']) && $menuArr['can-users-list'])
                    <li class="nav-item {{ $menuArr['users_active'] }} {{ $menuArr['users_open'] }}">
                        <a title="{{ trans('template.sidebar.usermanagement') }}" href="{{ url('/powerpanel/users') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">{{ trans('template.sidebar.usermanagement') }}</span>
                            <span class="{{ $menuArr['users_selected'] }}"></span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif


            @if(
            (isset($menuArr['search-statictics-list']) && $menuArr['search-statictics-list']) ||
            (isset($menuArr['hits-report-list']) && $menuArr['hits-report-list']) ||
            (isset($menuArr['document-report-list']) && $menuArr['document-report-list'])
            )
            <li class="nav-item {{ (isset($menuArr['reportmg']) && $menuArr['reportmg']=='active')? 'open active' : '' }}">
                <a title="{{ trans('template.sidebar.report') }}" href="javascript:;" class="nav-link nav-toggle">
                    <i class="la la-area-chart"></i>
                    <span class="title">{{ trans('template.sidebar.report') }}</span>
                    <span class="arrow {{ (isset($menuArr['reportmg']) && $menuArr['reportmg']=='active')? 'open' : '' }}"></span>
                    <span class=""></span>
                    <span class=""></span>
                </a>
                <ul class="sub-menu">
                    @if(isset($menuArr['search-statictics-list']) && $menuArr['search-statictics-list'])
                    <li class="nav-item {{ $menuArr['searchstatictics_active'] }} {{ $menuArr['searchstatictics_open'] }}">
                        <a title="Search Statistics" href="{{ url('powerpanel/search-statictics') }}" class="nav-link nav-toggle">
                            <i class="ri-search-line"></i>
                            <span class="title">Search Statistics</span>
                            <span class="{{ $menuArr['searchstatictics_selected'] }}"></span>
                        </a>
                    </li>
                    @endif
                    @if(isset($menuArr['hits-report-list']) && $menuArr['hits-report-list'])
                    <li class="nav-item {{ $menuArr['hitsreport_active'] }} {{ $menuArr['hitsreport_open'] }}">
                        <a title="Hits Report" href="{{ url('powerpanel/hits-report') }}" class="nav-link nav-toggle">
                            <i class="ri-search-line"></i>
                            <span class="title">Hits Report</span>
                            <span class="{{ $menuArr['hitsreport_selected'] }}"></span>
                        </a>
                    </li>
                    @endif
                    @if(isset($menuArr['document-report-list']) && $menuArr['document-report-list'])
                    <li class="nav-item {{ $menuArr['documentreport_active'] }} {{ $menuArr['documentreport_open'] }}">
                        <a title="Documents Report" href="{{ url('powerpanel/document-report') }}" class="nav-link nav-toggle">
                            <i class="ri-search-line"></i>
                            <span class="title">Documents Report</span>
                            <span class="{{ $menuArr['documentreport_selected'] }}"></span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @if(isset($menuArr['can-blockedip-list']) && $menuArr['can-blockedip-list'])
            <li class="nav-item {{ $menuArr['blockedip_active'] }} {{ $menuArr['blockedip_open'] }}">
                <a title="Blocked IPs" href="{{ url('powerpanel/blocked-ips') }}" class="nav-link nav-toggle">
                    <i class="la la-lock"></i>
                    <span class="title">Blocked IPs</span>
                    <span class="{{ $menuArr['blockedip_selected'] }}"></span>
                </a>
            </li>
            @endif
            @if(
            (isset($menuArr['can-email-log-list']) && $menuArr['can-email-log-list']) ||
            (isset($menuArr['can-log-list']) && $menuArr['can-log-list'])
            )
            <li class="nav-item {{ (isset($menuArr['logmg']) && $menuArr['logmg']=='active')? 'open active' : '' }}">
                <a title="{{ trans('template.sidebar.logs') }}" href="javascript:;" class="nav-link nav-toggle">
                    <i class="la la-envelope-o"></i>
                    <span class="title" title="{{ trans('template.sidebar.logs') }}">{{ trans('template.sidebar.logs') }}</span>
                    <span class="arrow {{ (isset($menuArr['logmg']) && $menuArr['logmg']=='active')? 'open' : '' }}"></span>
                    <span class=""></span>
                    <span class=""></span>
                </a>
                <ul class="sub-menu">
                    @if(isset($menuArr['can-log-list']) && $menuArr['can-log-list'])
                    <li class="nav-item {{ $menuArr['log_active'] }} {{ $menuArr['log_open'] }}">
                        <a title="{{ trans('template.sidebar.logmanager') }}" href="{{ url('powerpanel/log') }}" class="nav-link nav-toggle">
                            <i class="fa fa-key"></i>
                            <span class="title">{{ trans('template.sidebar.logmanager') }}</span>
                            <span class="{{ $menuArr['log_selected'] }}"></span>
                        </a>
                    </li>
                    @endif
                    @if(isset($menuArr['can-email-log-list']) && $menuArr['can-email-log-list'])
                    <li class="nav-item {{ $menuArr['email_active'] }} {{ $menuArr['email_open'] }}">
                        <a title="{{ trans('template.sidebar.emaillog') }}" href="{{ url('powerpanel/email-log') }}" class="nav-link nav-toggle">
                            <i class="icon-envelope-letter"></i>
                            <span class="title" title="{{ trans('template.sidebar.emaillogs') }}">{{ trans('template.sidebar.emaillogs') }}</span>
                            <span class="{{ $menuArr['email_selected'] }}"></span>
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
            @if(isset($menuArr['can-login-history']) && $menuArr['can-login-history'])
            <li class="nav-item {{ $menuArr['login_history_active'] }} {{ $menuArr['login_history_open'] }}">
                <a href="{{ url('powerpanel/login-history') }}" title="{{ trans('Login History') }}" class="nav-link nav-toggle">
                    <i class="la la-key"></i>
                    <span class="title">{{ trans('Login History') }}</span>
                    <span class="{{ $menuArr['login_history_selected'] }}"></span>
                </a>
            </li>
            @endif
            @if(isset($menuArr['can-recent-updates-list']) && $menuArr['can-recent-updates-list'])
            <li class="nav-item {{ (isset($menuArr['recmg']) && $menuArr['recmg']=='active')? 'open active' : '' }}">
                <a title="{{ trans('template.sidebar.recentupdates') }}" href="{{ url('powerpanel/recent-updates') }}" class="nav-link nav-toggle">
                    <i class="icon-bell"></i>
                    <span class="title">{{ trans('template.sidebar.recentupdates') }}</span>
                    <span class="{{ $menuArr['recent_selected'] }}"></span>
                </a>
            </li>
            @endif
            </ul>
        </div>
    </div>
</div>