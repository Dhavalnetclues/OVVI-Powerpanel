@php 
$menuArr = App\Helpers\PowerPanelSidebarConfig::getConfig(); 
@endphp

<div class="page-fixsidebar">
    <div class="cover">
        <ul class="first-menu cm-menu text-center">
            @if((isset($menuArr['dashboard_active']) && $menuArr['dashboard_active'] == 'active'))
                <li><a class="links" href="javascript:void(0)" id="dashboard-sidebar" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ trans('template.sidebar.dashboard') }}"><i class="ri-dashboard-2-line"></i></a></li>
            @endif
            <li><a class="links" href="javascript:void(0)" id="activity-sidebar" data-bs-toggle="tooltip" data-bs-placement="left" title="Recent Activity"><i class="ri-pulse-line"></i></a></li>
            <li><a class="links" href="javascript:void(0)" id="notification-sidebar" data-bs-toggle="tooltip" data-bs-placement="left" title="Notification"><i class="ri-notification-3-line"></i></a></li>
        </ul>
        <hr />
        <div class="drag-note">
            <div class="sm-title text-center" data-bs-toggle="tooltip" data-bs-placement="left" title="Drag & Drop Menu">
                <i class="ri-drag-drop-line fs-22"></i>
            </div>
        </div>
        <div class="pagemenu" data-simplebar>
            <ul id="dropper" class="connectedSortable second-menu cm-menu text-center">
                {{-- <li>
                    <a href="javascript:void(0)" class="close-menu" title="Remove"><i class="ri-close-circle-fill"></i></a>
                    <a class="links" href="{{ url('/powerpanel/page_template') }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Page Templates"><i class="ri-apps-2-line"></i></a>
                </li>
                <li>
                	<a href="javascript:void(0)" class="close-menu" title="Remove"><i class="ri-close-circle-fill"></i></a>
                	<a class="links" href="{{ url('/powerpanel/formbuilder') }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Form Builder"><i class="ri-layout-3-line"></i></a>
                </li> --}}
            </ul>
        </div>
    </div>
</div>

<!-- Right Sidebar offcanvas -->
<div class="offcanvas offcanvas-end rightsidebar-popup" tabindex="-1" id="RightSidebar" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header p-3 pb-1">
        <h5 id="offcanvasRightLabel"></h5>
        <button type="button" class="btn-close close-popup text-reset fs-10" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="rounded-0"> <!-- h-100 -->
            <div class="card-body p-0" id="sidebar-html"></div>
        </div>
    </div>
</div>
<!-- Right Sidebar offcanvas End -->