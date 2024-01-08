<?php

namespace App\Helpers;

use Auth;
use App\Helpers\MyLibrary;
use Illuminate\Support\Facades\Request;

class PowerPanelSidebarConfig {

    public static function getConfig() {

        $userIsAdmin = false;
        $currentUserRoleData = Mylibrary::getCurrentUserRoleDatils();
        if (!empty($currentUserRoleData)) {
            if ($currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        $user = Auth::user();
        $permissionObj = $user->getAllPermissions();
        // print_r($permissionObj);die;
        $permissionArr = array();
        if($permissionObj->count() > 0){
            foreach($permissionObj as $key => $value){
                $permissionArr[$key] = $value->name;
            }
        }

        $menuArr = [];
        if (empty(Request::segment(2)) || Request::segment(2) == 'dashboard') {
            $menuArr['dashboard_active'] = 'active';
            $menuArr['dashboard_open'] = 'open';
            $menuArr['dashboard_selected'] = 'selected';
        } else {
            $menuArr['dashboard_active'] = '';
            $menuArr['dashboard_open'] = '';
            $menuArr['dashboard_selected'] = '';
        }

        if (in_array('menu-list', $permissionArr)) {
            $menuArr['can-menu-list'] = true;
            if (Request::segment(2) == 'menu') {
                $menuArr['menu_active'] = 'active';
                $menuArr['menu_open'] = 'open';
                $menuArr['menu_selected'] = 'selected';
            } else {
                $menuArr['menu_active'] = '';
                $menuArr['menu_open'] = '';
                $menuArr['menu_selected'] = '';
            }
        }

        if (in_array('workflow-list', $permissionArr)) {
            $menuArr['can-workflow-list'] = true;
            if (Request::segment(2) == 'workflow') {
                $menuArr['workflow_active'] = 'active';
                $menuArr['workflow_open'] = 'open';
                $menuArr['workflow_selected'] = 'selected';
            } else {
                $menuArr['workflow_active'] = '';
                $menuArr['workflow_open'] = '';
                $menuArr['workflow_selected'] = '';
            }
        }

        if (in_array('banners-list', $permissionArr)) {
            $menuArr['can-banner-list'] = true;
            if (Request::segment(2) == 'banners') {
                $menuArr['banner_active'] = 'active';
                $menuArr['banner_open'] = 'open';
                $menuArr['banner_selected'] = 'selected';
                $menuArr['pagemenu'] = 'active';
            } else {
                $menuArr['banner_active'] = '';
                $menuArr['banner_open'] = '';
                $menuArr['banner_selected'] = '';
                $menuArr['banner_active'] = '';
            }
        }

        if (in_array('pages-list', $permissionArr)) {
            $menuArr['can-pages-list'] = true;
            if (Request::segment(2) == 'pages') {
                $menuArr['page_active'] = 'active';
                $menuArr['page_open'] = 'open';
                $menuArr['page_selected'] = 'selected';
                $menuArr['pagemenu'] = 'active';
                
            } else {
                $menuArr['page_active'] = '';
                $menuArr['page_open'] = '';
                $menuArr['page_selected'] = '';
                $menuArr['page_active'] = '';
            }
        }

        if (in_array('static-block-list', $permissionArr)) {
            $menuArr['can-static-block'] = true;
            if (Request::segment(2) == 'static-block') {
                $menuArr['staticblocks_active'] = 'active';
                $menuArr['staticblocks_open'] = 'open';
                $menuArr['staticblocks_selected'] = 'selected';
            } else {
                $menuArr['staticblocks_active'] = '';
                $menuArr['staticblocks_open'] = '';
                $menuArr['staticblocks_selected'] = '';
                $menuArr['staticblocks_active'] = '';
            }
        }

        if (in_array('popup-list', $permissionArr)) {
            $menuArr['can-popup-list'] = true;
            if (Request::segment(2) == 'popup') {
                $menuArr['popup_active'] = 'active';
                $menuArr['popup_open'] = 'open';
                $menuArr['popup_selected'] = 'selected';
                $menuArr['popupmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['popup_active'] = '';
                $menuArr['popup_open'] = '';
                $menuArr['popup_selected'] = '';
            }
        }
        if (in_array('candwservice-list', $permissionArr)) {
            $menuArr['can-candwservice-list'] = true;
            if (Request::segment(2) == 'candwservice') {
                $menuArr['candwservice_active'] = 'active';
                $menuArr['candwservice_open'] = 'open';
                $menuArr['candwservice_selected'] = 'selected';
                $menuArr['candwservicemg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['candwservice_active'] = '';
                $menuArr['candwservice_open'] = '';
                $menuArr['candwservice_selected'] = '';
            }
        }
        if (in_array('appointment-lead-list', $permissionArr)) {
            $menuArr['can-appointment-lead-list'] = true;
            if (Request::segment(2) == 'appointment-lead') {
                $menuArr['appointment_active'] = 'active';
                $menuArr['appointment_open'] = 'open';
                $menuArr['appointment_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['appointment_active'] = '';
                $menuArr['appointment_open'] = '';
                $menuArr['appointment_selected'] = '';
            }
        }

        if (in_array('contact-info-list', $permissionArr)) {
            $menuArr['can-contact-list'] = true;
            if (Request::segment(2) == 'contact-info') {
                $menuArr['contact_info_active'] = 'active';
                $menuArr['contact_info_open'] = 'open';
                $menuArr['contact_info_selected'] = 'selected';
                $menuArr['settings'] = 'active';
            } else {
                $menuArr['contact_info_active'] = '';
                $menuArr['contact_info_open'] = '';
                $menuArr['contact_info_selected'] = '';
            }
        }
        if (in_array('contact-us-list', $permissionArr)) {
            $menuArr['can-contact-us-list'] = true;
            if (Request::segment(2) == 'contact-lead') {
                $menuArr['contact_active'] = 'active';
                $menuArr['contact_open'] = 'open';
                $menuArr['contact_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['contact_active'] = '';
                $menuArr['contact_open'] = '';
                $menuArr['contact_selected'] = '';
            }
        }
         if (in_array('whitelabel-list', $permissionArr)) {
            $menuArr['can-whitelabel-list'] = true;
            if (Request::segment(2) == 'white-label') {
                $menuArr['whitelabel_active'] = 'active';
                $menuArr['whitelabel_open'] = 'open';
                $menuArr['whitelabel_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['whitelabel_active'] = '';
                $menuArr['whitelabel_open'] = '';
                $menuArr['whitelabel_selected'] = '';
            }
        }
        if (in_array('cbd-store-lead-list', $permissionArr)) {
            $menuArr['can-cbd-store-lead-list'] = true;
            if (Request::segment(2) == 'cbd-store') {
                $menuArr['cbd-store_active'] = 'active';
                $menuArr['cbd-store_open'] = 'open';
                $menuArr['cbd-store_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['cbd-store_active'] = '';
                $menuArr['cbd-store_open'] = '';
                $menuArr['cbd-store_selected'] = '';
            }
        }
        if (in_array('liquorshop-list', $permissionArr)) {
            $menuArr['can-liquorshop-list'] = true;
            if (Request::segment(2) == 'liquorshop-lead') {
                $menuArr['liquorshop_active'] = 'active';
                $menuArr['liquorshop_open'] = 'open';
                $menuArr['liquorshop_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['liquorshop_active'] = '';
                $menuArr['liquorshop_open'] = '';
                $menuArr['liquorshop_selected'] = '';
            }
        }
        if (in_array('career-list', $permissionArr)) {
            $menuArr['can-career-list'] = true;
            if (Request::segment(2) == 'career-lead') {
                $menuArr['career_active'] = 'active';
                $menuArr['career_open'] = 'open';
                $menuArr['career_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['career_active'] = '';
                $menuArr['career_open'] = '';
                $menuArr['career_selected'] = '';
            }
        }
        if (in_array('error-tracking-list', $permissionArr)) {
            $menuArr['can-error-tracking-list'] = true;
            if (Request::segment(2) == 'error-tracking') {
                $menuArr['error_tracking_active'] = 'active';
                $menuArr['error_tracking_open'] = 'open';
                $menuArr['error_tracking_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['error_tracking_active'] = '';
                $menuArr['error_tracking_open'] = '';
                $menuArr['error_tracking_selected'] = '';
            }
        }

        if (in_array('getdemo-leads-list', $permissionArr)) {
            $menuArr['can-getdemo-leads-list'] = true;
            if (Request::segment(2) == 'getdemo-leads') {
                $menuArr['getdemo_active'] = 'active';
                $menuArr['getdemo_open'] = 'open';
                $menuArr['getdemo_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['getdemo_active'] = '';
                $menuArr['getdemo_open'] = '';
                $menuArr['getdemo_selected'] = '';
            }
        }
        
        if (in_array('reseller-leads-list', $permissionArr)) {
            $menuArr['can-reseller-leads-list'] = true;
            if (Request::segment(2) == 'reseller-leads') {
                $menuArr['reseller_active'] = 'active';
                $menuArr['reseller_open'] = 'open';
                $menuArr['reseller_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['reseller_active'] = '';
                $menuArr['reseller_open'] = '';
                $menuArr['reseller_selected'] = '';
            }
        }

        if (in_array('events-lead-list', $permissionArr)) {
            $menuArr['can-events-lead-list'] = true;
            if (Request::segment(2) == 'events-lead') {
                $menuArr['events_lead_active'] = 'active';
                $menuArr['events_lead_open'] = 'open';
                $menuArr['events_lead_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['events_lead_active'] = '';
                $menuArr['events_lead_open'] = '';
                $menuArr['events_lead_selected'] = '';
            }
        }

        if (in_array('careers-lead-list', $permissionArr)) {
            $menuArr['can-careers-lead-list'] = true;
            if (Request::segment(2) == 'careers-lead') {
                $menuArr['careers_lead_active'] = 'active';
                $menuArr['careers_lead_open'] = 'open';
                $menuArr['careers_lead_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['careers_lead_active'] = '';
                $menuArr['careers_lead_open'] = '';
                $menuArr['careers_lead_selected'] = '';
            }
        }

        if (in_array('online-polling-lead-list', $permissionArr)) {
            $menuArr['can-online-polling-lead-list'] = true;
            if (Request::segment(2) == 'online-polling-lead') {
                $menuArr['online_polling_active'] = 'active';
                $menuArr['online_polling_open'] = 'open';
                $menuArr['online_polling_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['online_polling_active'] = '';
                $menuArr['online_polling_open'] = '';
                $menuArr['online_polling_selected'] = '';
            }
        }

        if (in_array('interconnections-list', $permissionArr)) {
            $menuArr['can-interconnections-list'] = true;
            if (Request::segment(2) == 'interconnections') {
                $menuArr['interconnections_active'] = 'active';
                $menuArr['interconnections_open'] = 'open';
                $menuArr['interconnections_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['interconnections_active'] = '';
                $menuArr['interconnections_open'] = '';
                $menuArr['interconnections_selected'] = '';
            }
        }

        if (in_array('number-allocation-list', $permissionArr)) {
            $menuArr['can-number-allocation'] = true;
            if (Request::segment(2) == 'number-allocation') {
                $menuArr['number_allocation_active'] = 'active';
                $menuArr['number_allocation_open'] = 'open';
                $menuArr['number_allocation_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['number_allocation_active'] = '';
                $menuArr['number_allocation_open'] = '';
                $menuArr['number_allocation_selected'] = '';
            }
        }

        if (in_array('consultations-list', $permissionArr)) {
            $menuArr['can-consultations'] = true;
            if (Request::segment(2) == 'consultations') {
                $menuArr['consultations_active'] = 'active';
                $menuArr['consultations_open'] = 'open';
                $menuArr['consultations_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['consultations_active'] = '';
                $menuArr['consultations_open'] = '';
                $menuArr['consultations_selected'] = '';
            }
        }

        if (in_array('team-list', $permissionArr)) {
            $menuArr['can-team-list'] = true;
            if (Request::segment(2) == 'team') {
                $menuArr['team_active'] = 'active';
                $menuArr['team_open'] = 'open';
                $menuArr['team_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['team_active'] = '';
                $menuArr['team_open'] = '';
                $menuArr['team_selected'] = '';
            }
        }

        if (Auth::user()->can('payonline-list')) {
            $menuArr['can-payonline-list'] = true;
            if (Request::segment(2) == 'payonline') {
                $menuArr['payonline_active'] = 'active';
                $menuArr['payonline_open'] = 'open';
                $menuArr['payonline_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['payonline_active'] = '';
                $menuArr['payonline_open'] = '';
                $menuArr['payonline_selected'] = '';
            }
        }

        if (Auth::user()->can('referearn-lead-list')) {
            $menuArr['can-referearn-lead-list'] = true;
            if (Request::segment(2) == 'referearn-lead') {
                $menuArr['news_letter_active'] = 'active';
                $menuArr['news_letter_open'] = 'open';
                $menuArr['news_letter_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['news_letter_active'] = '';
                $menuArr['news_letter_open'] = '';
                $menuArr['news_letter_selected'] = '';
            }
        }
        if (Auth::user()->can('order-lead-list')) {
            $menuArr['can-order-lead-list'] = true;
            if (Request::segment(2) == 'order-lead') {
                $menuArr['order_active'] = 'active';
                $menuArr['order_open'] = 'open';
                $menuArr['order_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['order_active'] = '';
                $menuArr['order_open'] = '';
                $menuArr['order_selected'] = '';
            }
        }
        if (Auth::user()->can('submit-tickets-list')) {
            $menuArr['can-submit-tickets-list'] = true;
            if (Request::segment(2) == 'submit-tickets') {
                $menuArr['tickets_active'] = 'active';
                $menuArr['tickets_open'] = 'open';
                $menuArr['tickets_selected'] = 'selected';
                $menuArr['leadmg'] = 'active';
            } else {
                $menuArr['tickets_active'] = '';
                $menuArr['tickets_open'] = '';
                $menuArr['tickets_selected'] = '';
            }
        }

        if (Auth::user()->can('publications-list')) {
            $menuArr['can-publications-list'] = true;
            if (Request::segment(2) == 'publications') {
                $menuArr['publications_active'] = 'active';
                $menuArr['publications_open'] = 'open';
                $menuArr['publications_selected'] = 'selected';
                $menuArr['pubtmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['publications_active'] = '';
                $menuArr['publications_open'] = '';
                $menuArr['publications_selected'] = '';
            }
        }

        if (Auth::user()->can('advertise-list')) {
            $menuArr['can-advertise-list'] = true;
            if (Request::segment(2) == 'advertise') {
                $menuArr['ads_active'] = 'active';
                $menuArr['ads_selected'] = 'open';
                $menuArr['ad_selected'] = 'selected';
                $menuArr['admanager'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['ads_active'] = '';
                $menuArr['ads_selected'] = '';
                $menuArr['ad_selected'] = '';
            }
        }

        if (Auth::user()->can('testimonial-list')) {
            $menuArr['can-testimonial-list'] = true;
            if (Request::segment(2) == 'testimonial') {
                $menuArr['testimonial_active'] = 'active';
                $menuArr['testimonial_open'] = 'open';
                $menuArr['testimonial_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['testimonial_active'] = '';
                $menuArr['testimonial_open'] = '';
                $menuArr['testimonial_selected'] = '';
            }
        }

        if (Auth::user()->can('client-category-list')) {
            $menuArr['can-clients-category-list'] = true;
            if (Request::segment(2) == 'client-category') {
                $menuArr['client_category_active'] = 'active';
                $menuArr['client_category_open'] = 'open';
                $menuArr['client_category_selected'] = 'selected';
                $menuArr['catmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['client_category_active'] = '';
                $menuArr['client_category_open'] = '';
                $menuArr['client_category_selected'] = '';
            }
        }

        if (Auth::user()->can('client-list')) {
            $menuArr['can-client-list'] = true;
            if (Request::segment(2) == 'client') {
                $menuArr['client_active'] = 'active';
                $menuArr['client_open'] = 'open';
                $menuArr['client_selected'] = 'selected';
                $menuArr['catmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['client_active'] = '';
                $menuArr['client_open'] = '';
                $menuArr['client_selected'] = '';
            }
        }

        if (Auth::user()->can('news-list')) {
            $menuArr['can-news-list'] = true;
            if (Request::segment(2) == 'news') {
                $menuArr['news_active'] = 'active';
                $menuArr['news_open'] = 'open';
                $menuArr['news_selected'] = 'selected';
                $menuArr['newsmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['news_active'] = '';
                $menuArr['news_open'] = '';
                $menuArr['news_selected'] = '';
            }
        }
        if (Auth::user()->can('rfps-list')) {
            $menuArr['can-rfps-list'] = true;
            if (Request::segment(2) == 'rfps') {
                $menuArr['rfps_active'] = 'active';
                $menuArr['rfps_open'] = 'open';
                $menuArr['rfps_selected'] = 'selected';
                $menuArr['rfpsmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['rfps_active'] = '';
                $menuArr['rfps_open'] = '';
                $menuArr['rfps_selected'] = '';
            }
        }
        if (Auth::user()->can('rfp-list')) {
            $menuArr['can-rfp-list'] = true;
            if (Request::segment(2) == 'rfp') {
                $menuArr['rfp_active'] = 'active';
                $menuArr['rfp_open'] = 'open';
                $menuArr['rfp_selected'] = 'selected';
                $menuArr['rfpmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['rfp_active'] = '';
                $menuArr['rfp_open'] = '';
                $menuArr['rfp_selected'] = '';
            }
        }
        if (Auth::user()->can('alerts-list')) {
            $menuArr['can-alerts-list'] = true;
            if (Request::segment(2) == 'alerts') {
                $menuArr['alerts_active'] = 'active';
                $menuArr['alerts_open'] = 'open';
                $menuArr['alerts_selected'] = 'selected';
                $menuArr['pubDtmg1'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['alerts_active'] = '';
                $menuArr['alerts_open'] = '';
                $menuArr['alerts_selected'] = '';
            }
        }
        if (Auth::user()->can('news-category-list')) {
            $menuArr['can-news-category-list'] = true;
            if (Request::segment(2) == 'news-category') {
                $menuArr['news_category_active'] = 'active';
                $menuArr['news_category_open'] = 'open';
                $menuArr['news_category_selected'] = 'selected';
                $menuArr['newsmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['news_category_active'] = '';
                $menuArr['news_category_open'] = '';
                $menuArr['news_category_selected'] = '';
            }
        }

        if (Auth::user()->can('online-polling-list')) {
            $menuArr['can-online-polling-list'] = true;
            if (Request::segment(2) == 'online-polling') {
                $menuArr['online_polling_active'] = 'active';
                $menuArr['online_polling_open'] = 'open';
                $menuArr['online_polling_selected'] = 'selected';
                $menuArr['online_pollingmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['online_polling_active'] = '';
                $menuArr['online_polling_open'] = '';
                $menuArr['online_polling_selected'] = '';
            }
        }

        if (Auth::user()->can('project-category-list')) {
            $menuArr['can-projects-category-list'] = true;
            if (Request::segment(2) == 'project-category') {
                $menuArr['projects_category_active'] = 'active';
                $menuArr['projects_category_open'] = 'open';
                $menuArr['projects_category_selected'] = 'selected';
                $menuArr['realmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['projects_category_active'] = '';
                $menuArr['projects_category_open'] = '';
                $menuArr['projects_category_selected'] = '';
            }
        }

        if (Auth::user()->can('projects-list')) {
            $menuArr['can-projects-list'] = true;
            if (Request::segment(2) == 'projects') {
                $menuArr['projects_active'] = 'active';
                $menuArr['projects_open'] = 'open';
                $menuArr['projects_selected'] = 'selected';
                $menuArr['realmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['projects_active'] = '';
                $menuArr['projects_open'] = '';
                $menuArr['projects_selected'] = '';
            }
        }


        if (Auth::user()->can('links-category-list')) {
            $menuArr['can-links-category-list'] = true;
            if (Request::segment(2) == 'links-category') {
                $menuArr['linkscategory_active'] = 'active';
                $menuArr['linkscategory_open'] = 'open';
                $menuArr['linkscategory_selected'] = 'selected';
                $menuArr['linkmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['linkscategory_active'] = '';
                $menuArr['linkscategory_open'] = '';
                $menuArr['linkscategory_selected'] = '';
            }
        }

        if (Auth::user()->can('links-list')) {
            $menuArr['can-links-list'] = true;
            if (Request::segment(2) == 'links') {
                $menuArr['links_active'] = 'active';
                $menuArr['links_open'] = 'open';
                $menuArr['links_selected'] = 'selected';
                $menuArr['linkmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['links_active'] = '';
                $menuArr['links_open'] = '';
                $menuArr['links_selected'] = '';
            }
        }

        if (Auth::user()->can('product-category-list')) {
            $menuArr['can-products-category-list'] = true;
            if (Request::segment(2) == 'product-category') {
                $menuArr['products_category_active'] = 'active';
                $menuArr['products_category_open'] = 'open';
                $menuArr['products_category_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
                $menuArr['pcontmg'] = 'active';
            } else {
                $menuArr['products_category_active'] = '';
                $menuArr['products_category_open'] = '';
                $menuArr['products_category_selected'] = '';
            }
        }

        if (Auth::user()->can('products-list')) {
            $menuArr['can-products-list'] = true;
            if (Request::segment(2) == 'products') {
                $menuArr['products_active'] = 'active';
                $menuArr['products_open'] = 'open';
                $menuArr['products_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
                $menuArr['pcontmg'] = 'active';
            } else {
                $menuArr['products_active'] = '';
                $menuArr['products_open'] = '';
                $menuArr['products_selected'] = '';
            }
        }

        if (Auth::user()->can('show-category-list')) {
            $menuArr['can-show-category-list'] = true;
            if (Request::segment(2) == 'show-category') {
                $menuArr['show_category_active'] = 'active';
                $menuArr['show_category_open'] = 'open';
                $menuArr['show_category_selected'] = 'selected';
                $menuArr['catmg'] = 'active';
                $menuArr['contmg'] = 'active';
                $menuArr['scontmg'] = 'active';
            } else {
                $menuArr['show_category_active'] = '';
                $menuArr['show_category_open'] = '';
                $menuArr['show_category_selected'] = '';
            }
        }

        if (Auth::user()->can('shows-list')) {
            $menuArr['can-shows-list'] = true;
            if (Request::segment(2) == 'shows') {
                $menuArr['shows_active'] = 'active';
                $menuArr['shows_open'] = 'open';
                $menuArr['shows_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
                $menuArr['catmg'] = 'active';
                $menuArr['scontmg'] = 'active';
            } else {
                $menuArr['shows_active'] = '';
                $menuArr['shows_open'] = '';
                $menuArr['shows_selected'] = '';
            }
        }

        if (Auth::user()->can('sponsor-category-list')) {
            $menuArr['can-sponsor-category-list'] = true;
            if (Request::segment(2) == 'sponsor-category') {
                $menuArr['sponsor_category_active'] = 'active';
                $menuArr['sponsor_category_open'] = 'open';
                $menuArr['sponsor_category_selected'] = 'selected';
                $menuArr['sponmg'] = 'active';
                $menuArr['catmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['sponsor_category_active'] = '';
                $menuArr['sponsor_category_open'] = '';
                $menuArr['sponsor_category_selected'] = '';
            }
        }

        if (Auth::user()->can('sponsor-list')) {
            $menuArr['can-sponsor-list'] = true;
            if (Request::segment(2) == 'sponsor') {
                $menuArr['sponsor_active'] = 'active';
                $menuArr['sponsor_open'] = 'open';
                $menuArr['sponsor_selected'] = 'selected';
                $menuArr['sponmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['sponsor_active'] = '';
                $menuArr['sponsor_open'] = '';
                $menuArr['sponsor_selected'] = '';
            }
        }

        if (Auth::user()->can('video-gallery-list')) {
            $menuArr['can-video-gallery-list'] = true;
            if (Request::segment(2) == 'video-gallery') {
                $menuArr['video_gallery_active'] = 'active';
                $menuArr['video_gallery_open'] = 'open';
                $menuArr['video_gallery_selected'] = 'selected';
                $menuArr['video_gallerymg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['video_gallery_active'] = '';
                $menuArr['video_gallery_open'] = '';
                $menuArr['video_gallery_selected'] = '';
            }
        }

        if (Auth::user()->can('photo-gallery-list')) {
            $menuArr['can-photo-gallery-list'] = true;
            if (Request::segment(2) == 'photo-gallery') {
                $menuArr['photo_gallery_active'] = 'active';
                $menuArr['photo_gallery_open'] = 'open';
                $menuArr['photo_gallery_selected'] = 'selected';
                $menuArr['photo_gallerymg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['photo_gallery_active'] = '';
                $menuArr['photo_gallery_open'] = '';
                $menuArr['photo_gallery_selected'] = '';
            }
        }

        if (Auth::user()->can('careers-list')) {
            $menuArr['can-careers-list'] = true;
            if (Request::segment(2) == 'careers') {
                $menuArr['careers_active'] = 'active';
                $menuArr['careers_open'] = 'open';
                $menuArr['careers_selected'] = 'selected';
                $menuArr['careersmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['careers_active'] = '';
                $menuArr['careers_open'] = '';
                $menuArr['careers_selected'] = '';
            }
        }
        if (Auth::user()->can('netcareers-list')) {
            $menuArr['can-netcareers-list'] = true;
            if (Request::segment(2) == 'netcareers') {
                $menuArr['netcareers_active'] = 'active';
                $menuArr['netcareers_open'] = 'open';
                $menuArr['netcareers_selected'] = 'selected';
                $menuArr['netcareersmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['netcareers_active'] = '';
                $menuArr['netcareers_open'] = '';
                $menuArr['netcareers_selected'] = '';
            }
        }
        if (Auth::user()->can('career-services-list')) {
            $menuArr['can-career-services-list'] = true;
            if (Request::segment(2) == 'career-services') {
                $menuArr['career-services_active'] = 'active';
                $menuArr['career-services_open'] = 'open';
                $menuArr['career-services_selected'] = 'selected';
                $menuArr['career-servicesmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['career-services_active'] = '';
                $menuArr['career-services_open'] = '';
                $menuArr['career-services_selected'] = '';
            }
        }
        if (Auth::user()->can('fmbroadcasting-list')) {
            $menuArr['can-fmbroadcasting-list'] = true;
            if (Request::segment(2) == 'fmbroadcasting') {
                $menuArr['fmbroadcasting_active'] = 'active';
                $menuArr['fmbroadcasting_open'] = 'open';
                $menuArr['fmbroadcasting_selected'] = 'selected';
                $menuArr['fmbroadcastingmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['fmbroadcasting_active'] = '';
                $menuArr['fmbroadcasting_open'] = '';
                $menuArr['fmbroadcasting_selected'] = '';
            }
        }
        if (Auth::user()->can('boardofdirectors-list')) {
            $menuArr['can-boardofdirectors-list'] = true;
            if (Request::segment(2) == 'boardofdirectors') {
                $menuArr['boardofdirectors_active'] = 'active';
                $menuArr['boardofdirectors_open'] = 'open';
                $menuArr['boardofdirectors_selected'] = 'selected';
                $menuArr['boardofdirectorsmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['boardofdirectors_active'] = '';
                $menuArr['boardofdirectors_open'] = '';
                $menuArr['boardofdirectors_selected'] = '';
            }
        }
        if (Auth::user()->can('register-application-list')) {
            $menuArr['can-register-application-list'] = true;
            if (Request::segment(2) == 'register-application') {
                $menuArr['register-application_active'] = 'active';
                $menuArr['register-application_open'] = 'open';
                $menuArr['register-application_selected'] = 'selected';
                $menuArr['register-applicationmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['register-application_active'] = '';
                $menuArr['register-application_open'] = '';
                $menuArr['register-application_selected'] = '';
            }
        }
        if (Auth::user()->can('licence-register-list')) {
            $menuArr['can-licence-register-list'] = true;
            if (Request::segment(2) == 'licence-register') {
                $menuArr['licence-register_active'] = 'active';
                $menuArr['licence-register_open'] = 'open';
                $menuArr['licence-register_selected'] = 'selected';
                $menuArr['licence-registermg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['licence-register_active'] = '';
                $menuArr['licence-register_open'] = '';
                $menuArr['licence-register_selected'] = '';
            }
        }
        if (Auth::user()->can('forms-and-fees-list')) {
            $menuArr['can-forms-and-fees-list'] = true;
            if (Request::segment(2) == 'forms-and-fees') {
                $menuArr['forms-and-fees_active'] = 'active';
                $menuArr['forms-and-fees_open'] = 'open';
                $menuArr['forms-and-fees_selected'] = 'selected';
                $menuArr['forms-and-feesmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['forms-and-fees_active'] = '';
                $menuArr['forms-and-fees_open'] = '';
                $menuArr['forms-and-fees_selected'] = '';
            }
        }
        if (Auth::user()->can('popup-list')) {
            $menuArr['can-popup-list'] = true;
            if (Request::segment(2) == 'popup') {
                $menuArr['popup_active'] = 'active';
                $menuArr['popup_open'] = 'open';
                $menuArr['popup_selected'] = 'selected';
                $menuArr['popupmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['popup_active'] = '';
                $menuArr['popup_open'] = '';
                $menuArr['popup_selected'] = '';
            }
        }

        if (Auth::user()->can('blogs-list')) {
            $menuArr['can-blogs-list'] = true;
            if (Request::segment(2) == 'blogs') {
                $menuArr['blogs_active'] = 'active';
                $menuArr['blogs_open'] = 'open';
                $menuArr['blogs_selected'] = 'selected';
                $menuArr['blogsmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['blogs_active'] = '';
                $menuArr['blogs_open'] = '';
                $menuArr['blogs_selected'] = '';
            }
        }

        if (Auth::user()->can('netblogs-list')) {
            $menuArr['can-netblogs-list'] = true;
            if (Request::segment(2) == 'netblogs') {
                $menuArr['netblogs_active'] = 'active';
                $menuArr['netblogs_open'] = 'open';
                $menuArr['netblogs_selected'] = 'selected';
                $menuArr['netblogsmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['netblogs_active'] = '';
                $menuArr['netblogs_open'] = '';
                $menuArr['netblogs_selected'] = '';
            }
        }

        if (Auth::user()->can('companies-list')) {
            $menuArr['can-companies-list'] = true;
            if (Request::segment(2) == 'companies') {
                $menuArr['companies_active'] = 'active';
                $menuArr['companies_open'] = 'open';
                $menuArr['companies_selected'] = 'selected';
                $menuArr['companiesmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['companies_active'] = '';
                $menuArr['companies_open'] = '';
                $menuArr['companies_selected'] = '';
            }
        }

        if (Auth::user()->can('public-record-category-list')) {
            $menuArr['can-public-record-category-list'] = true;
            if (Request::segment(2) == 'public-record-category') {
                $menuArr['public-record-category_active'] = 'active';
                $menuArr['public-record-category_open'] = 'open';
                $menuArr['public-record-category_selected'] = 'selected';
                $menuArr['publtmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['public-record-category_active'] = '';
                $menuArr['public-record-category_open'] = '';
                $menuArr['public-record-category_selected'] = '';
            }
        }
        if (Auth::user()->can('public-record-list')) {
            $menuArr['can-public-record-list'] = true;
            if (Request::segment(2) == 'public-record') {
                $menuArr['public-record_active'] = 'active';
                $menuArr['public-record_open'] = 'open';
                $menuArr['public-record_selected'] = 'selected';
                $menuArr['publtmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['public-record_active'] = '';
                $menuArr['public-record_open'] = '';
                $menuArr['public-record_selected'] = '';
            }
        }

        if (Auth::user()->can('service-category-list')) {
            $menuArr['can-service-category-list'] = true;
            if (Request::segment(2) == 'service-category') {
                $menuArr['service_category_active'] = 'active';
                $menuArr['service_category_open'] = 'open';
                $menuArr['service_category_selected'] = 'selected';
                $menuArr['sertmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['service_category_active'] = '';
                $menuArr['service_category_open'] = '';
                $menuArr['service_category_selected'] = '';
            }
        }

        if (Auth::user()->can('service-list')) {
            $menuArr['can-service-list'] = true;
            if (Request::segment(2) == 'service') {
                $menuArr['service_active'] = 'active';
                $menuArr['service_open'] = 'open';
                $menuArr['service_selected'] = 'selected';
                $menuArr['sertmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['service_active'] = '';
                $menuArr['service_open'] = '';
                $menuArr['service_selected'] = '';
            }
        }

        if (Auth::user()->can('faq-category-list')) {
            $menuArr['can-faq-category-list'] = true;
            if (Request::segment(2) == 'faq-category') {
                $menuArr['faqcategory_active'] = 'active';
                $menuArr['faqcategory_open'] = 'open';
                $menuArr['faqcategory_selected'] = 'selected';
                $menuArr['faqmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['faqcategory_active'] = '';
                $menuArr['faqcategory_open'] = '';
                $menuArr['faqcategory_selected'] = '';
            }
        }
        if (Auth::user()->can('faq-list')) {
            $menuArr['can-faq-list'] = true;
            if (Request::segment(2) == 'faq') {
                $menuArr['faq_active'] = 'active';
                $menuArr['faq_open'] = 'open';
                $menuArr['faq_selected'] = 'selected';
                $menuArr['faqmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['faq_active'] = '';
                $menuArr['faq_open'] = '';
                $menuArr['faq_selected'] = '';
            }
        }

        if (Auth::user()->can('department-list')) {
            $menuArr['can-department-list'] = true;
            if (Request::segment(2) == 'department') {
                $menuArr['department_active'] = 'active';
                $menuArr['department_open'] = 'open';
                $menuArr['department_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['department_active'] = '';
                $menuArr['department_open'] = '';
                $menuArr['department_selected'] = '';
            }
        }

        if (Auth::user()->can('messagingsystem-list')) {
            $menuArr['can-messagingsystem-list'] = true;
            if (Request::segment(2) == 'messagingsystem') {
                $menuArr['messagingsystem_active'] = 'active';
                $menuArr['messagingsystem_open'] = 'open';
                $menuArr['messagingsystem_selected'] = 'selected';
                $menuArr['message_contmg'] = 'active';
            } else {
                $menuArr['messagingsystem_active'] = '';
                $menuArr['messagingsystem_open'] = '';
                $menuArr['messagingsystem_selected'] = '';
            }
        }
        if (Auth::user()->can('formbuilder-list')) {
            $menuArr['can-formbuilder-list'] = true;
            if (Request::segment(2) == 'formbuilder') {
                $menuArr['formbuilder_active'] = 'active';
                $menuArr['formbuilder_open'] = 'open';
                $menuArr['formbuilder_selected'] = 'selected';
                $menuArr['formbuilder_contmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['formbuilder_active'] = '';
                $menuArr['formbuilder_open'] = '';
                $menuArr['formbuilder_selected'] = '';
            }
        }
        if (Auth::user()->can('page_template-list')) {
            $menuArr['can-page_template-list'] = true;
            if (Request::segment(2) == 'page_template') {
                $menuArr['page_template_active'] = 'active';
                $menuArr['page_template_open'] = 'open';
                $menuArr['page_template_selected'] = 'selected';
                $menuArr['page_template_contmg'] = 'active';
                $menuArr['pagemenu'] = 'active';
            } else {
                $menuArr['page_template_active'] = '';
                $menuArr['page_template_open'] = '';
                $menuArr['page_template_selected'] = '';
            }
        }
        if (Auth::user()->can('tag-list')) {
            $menuArr['can-tag-list'] = true;
            if (Request::segment(2) == 'tag') {
                $menuArr['tag_active'] = 'active';
                $menuArr['tag_open'] = 'open';
                $menuArr['tag_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['tag_active'] = '';
                $menuArr['tag_open'] = '';
                $menuArr['tag_selected'] = '';
            }
        }
        if (Auth::user()->can('maintenance-list')) {
            $menuArr['can-maintenance-list'] = true;
            if (Request::segment(2) == 'maintenance') {
                $menuArr['maintenance_active'] = 'active';
                $menuArr['maintenance_open'] = 'open';
                $menuArr['maintenance_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['maintenance_active'] = '';
                $menuArr['maintenance_open'] = '';
                $menuArr['maintenance_selected'] = '';
            }
        }


        if (Auth::user()->can('organizations-list')) {
            $menuArr['can-organizations-list'] = true;
            if (Request::segment(2) == 'organizations') {
                $menuArr['organizations_active'] = 'active';
                $menuArr['organizations_open'] = 'open';
                $menuArr['organizations_selected'] = 'selected';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['organizations_active'] = '';
                $menuArr['organizations_open'] = '';
                $menuArr['organizations_selected'] = '';
            }
        }
        if (Auth::user()->can('quick-links-list')) {
            $menuArr['can-quick-links-list'] = true;
            if (Request::segment(2) == 'quick-links') {
                $menuArr['quicklinks_active'] = 'active';
                $menuArr['quicklinks_open'] = 'open';
                $menuArr['quicklinks_selected'] = 'selected';
                $menuArr['linkmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['quicklinks_active'] = '';
                $menuArr['quicklinks_open'] = '';
                $menuArr['quicklinks_selected'] = '';
            }
        }
//        if (Auth::user()->can('useful-links-list')) {
//            $menuArr['can-useful-links-list'] = true;
//            if (Request::segment(2) == 'useful-links') {
//                $menuArr['usefullinks_active'] = 'active';
//                $menuArr['usefullinks_open'] = 'open';
//                $menuArr['usefullinks_selected'] = 'selected';
//                $menuArr['linkmg'] = 'active';
//                $menuArr['contmg'] = 'active';
//            } else {
//                $menuArr['usefullinks_active'] = '';
//                $menuArr['usefullinks_open'] = '';
//                $menuArr['usefullinks_selected'] = '';
//            }
//        }
        if (Auth::user()->can('publications-category-list')) {
            $menuArr['can-publications-category-list'] = true;
            if (Request::segment(2) == 'publications-category') {
                $menuArr['publications_category_active'] = 'active';
                $menuArr['publications_category_open'] = 'open';
                $menuArr['publications_category_selected'] = 'selected';
                $menuArr['pubtmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['publications_category_active'] = '';
                $menuArr['publications_category_open'] = '';
                $menuArr['publications_category_selected'] = '';
            }
        }

        if (Auth::user()->can('photo-album-list')) {
            $menuArr['can-photo-album-list'] = true;
            if (Request::segment(2) == 'photo-album') {
                $menuArr['photo_album_active'] = 'active';
                $menuArr['photo_album_open'] = 'open';
                $menuArr['photo_album_selected'] = 'selected';
                $menuArr['photoalmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['photo_album_active'] = '';
                $menuArr['photo_album_open'] = '';
                $menuArr['photo_album_selected'] = '';
            }
        }
        if (Auth::user()->can('photo-gallery-list')) {
            $menuArr['can-photo-gallery-list'] = true;
            if (Request::segment(2) == 'photo-gallery') {
                $menuArr['photo_gallery_active'] = 'active';
                $menuArr['photo_gallery_open'] = 'open';
                $menuArr['photo_gallery_selected'] = 'selected';
                $menuArr['photoalmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['photo_gallery_active'] = '';
                $menuArr['photo_gallery_open'] = '';
                $menuArr['photo_gallery_selected'] = '';
            }
        }

        if (Auth::user()->can('blog-category-list')) {
            $menuArr['can-blog-category-list'] = true;
            if (Request::segment(2) == 'blog-category') {
                $menuArr['blogcategory_active'] = 'active';
                $menuArr['blogcategory_open'] = 'open';
                $menuArr['blogcategory_selected'] = 'selected';
                $menuArr['blogmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['blogcategory_active'] = '';
                $menuArr['blogcategory_open'] = '';
                $menuArr['blogcategory_selected'] = '';
            }
        }
        if (Auth::user()->can('blogs-list')) {
            $menuArr['can-blogs-list'] = true;
            if (Request::segment(2) == 'blogs') {
                $menuArr['blogs_active'] = 'active';
                $menuArr['blogs_open'] = 'open';
                $menuArr['blogs_selected'] = 'selected';
                $menuArr['blogmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['blogs_active'] = '';
                $menuArr['blogs_open'] = '';
                $menuArr['blogs_selected'] = '';
            }
        }
        if (Auth::user()->can('decision-category-list')) {
            $menuArr['can-decision-category-list'] = true;
            if (Request::segment(2) == 'decision-category') {
                $menuArr['decisioncategory_active'] = 'active';
                $menuArr['decisioncategory_open'] = 'open';
                $menuArr['decisioncategory_selected'] = 'selected';
                $menuArr['decisionmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['decisioncategory_active'] = '';
                $menuArr['decisioncategory_open'] = '';
                $menuArr['decisioncategory_selected'] = '';
            }
        }
        if (Auth::user()->can('decision-list')) {
            $menuArr['can-decision-list'] = true;
            if (Request::segment(2) == 'decision') {
                $menuArr['decision_active'] = 'active';
                $menuArr['decision_open'] = 'open';
                $menuArr['decision_selected'] = 'selected';
                $menuArr['decisionmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['decision_active'] = '';
                $menuArr['decision_open'] = '';
                $menuArr['decision_selected'] = '';
            }
        }

        if (Auth::user()->can('career-category-list')) {
            $menuArr['can-career-category-list'] = true;
            if (Request::segment(2) == 'career-category') {
                $menuArr['careercategory_active'] = 'active';
                $menuArr['careercategory_open'] = 'open';
                $menuArr['careercategory_selected'] = 'selected';
                $menuArr['careermg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['careercategory_active'] = '';
                $menuArr['careercategory_open'] = '';
                $menuArr['careercategory_selected'] = '';
            }
        }
        if (Auth::user()->can('careers-list')) {
            $menuArr['can-careers-list'] = true;
            if (Request::segment(2) == 'careers') {
                $menuArr['careers_active'] = 'active';
                $menuArr['careers_open'] = 'open';
                $menuArr['careers_selected'] = 'selected';
                $menuArr['careermg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['careers_active'] = '';
                $menuArr['careers_open'] = '';
                $menuArr['careers_selected'] = '';
            }
        }



        if (Auth::user()->can('event-category-list')) {
            $menuArr['can-event-category-list'] = true;
            if (Request::segment(2) == 'event-category') {
                $menuArr['eventcategory_active'] = 'active';
                $menuArr['eventcategory_open'] = 'open';
                $menuArr['eventcategory_selected'] = 'selected';
                $menuArr['eventmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['eventcategory_active'] = '';
                $menuArr['eventcategory_open'] = '';
                $menuArr['eventcategory_selected'] = '';
            }
        }
        if (Auth::user()->can('events-list')) {
            $menuArr['can-events-list'] = true;
            if (Request::segment(2) == 'events') {
                $menuArr['events_active'] = 'active';
                $menuArr['events_open'] = 'open';
                $menuArr['events_selected'] = 'selected';
                $menuArr['eventmg'] = 'active';
                $menuArr['contmg'] = 'active';
            } else {
                $menuArr['events_active'] = '';
                $menuArr['events_open'] = '';
                $menuArr['events_selected'] = '';
            }
        }

//			}
        if (Auth::user()->can('roles-list')) {
            $menuArr['can-roles-list'] = true;
            if (Request::segment(2) == 'roles') {
                $menuArr['roles_active'] = 'active';
                $menuArr['roles_open'] = 'open';
                $menuArr['roles_selected'] = 'selected';
                $menuArr['usermg'] = 'active';
            } else {
                $menuArr['roles_active'] = '';
                $menuArr['roles_open'] = '';
                $menuArr['roles_selected'] = '';
            }
        }
        if (Auth::user()->can('users-list')) {
            $menuArr['can-users-list'] = true;
            if (Request::segment(2) == 'users') {
                $menuArr['users_active'] = 'active';
                $menuArr['users_open'] = 'open';
                $menuArr['users_selected'] = 'selected';
                $menuArr['usermg'] = 'active';
            } else {
                $menuArr['users_active'] = '';
                $menuArr['users_open'] = '';
                $menuArr['users_selected'] = '';
            }
        }
        // if ($userIsAdmin) {
        if (Auth::user()->can('email-log-list')) {
            $menuArr['can-email-log-list'] = true;
            if (Request::segment(2) == 'email-log') {
                $menuArr['email_active'] = 'active';
                $menuArr['email_open'] = 'open';
                $menuArr['email_selected'] = 'selected';
                $menuArr['settings'] = 'active';
                $menuArr['logmg'] = 'active';
            } else {
                $menuArr['email_active'] = '';
                $menuArr['email_open'] = '';
                $menuArr['email_selected'] = '';
            }
        }

        if (Auth::user()->can('error-logs-list')) {
            $menuArr['can-error-logs-list'] = true;
            if (Request::segment(2) == 'error-logs') {
                $menuArr['error_logs_active'] = 'active';
                $menuArr['error_logs_open'] = 'open';
                $menuArr['error_logs_selected'] = 'selected';
                $menuArr['settings'] = 'active';
                $menuArr['logmg'] = 'active';
            } else {
                $menuArr['error_logs_active'] = '';
                $menuArr['error_logs_open'] = '';
                $menuArr['error_logs_selected'] = '';
            }
        }

        if (Auth::user()->can('log-list')) {
            $menuArr['can-log-list'] = true;
            if (Request::segment(2) == 'log') {
                $menuArr['log_active'] = 'active';
                $menuArr['log_open'] = 'open';
                $menuArr['log_selected'] = 'selected';
                $menuArr['settings'] = 'active';
                $menuArr['logmg'] = 'active';
            } else {
                $menuArr['log_active'] = '';
                $menuArr['log_open'] = '';
                $menuArr['log_selected'] = '';
            }
        }
        if (Auth::user()->can('login-history-list')) {
            $menuArr['can-login-history'] = true;
            if (Request::segment(2) == 'login-history') {
                $menuArr['login_history_active'] = 'active';
                $menuArr['login_history_open'] = 'open';
                $menuArr['login_history_selected'] = 'selected';
                $menuArr['login_historymg'] = 'active';
                $menuArr['settings'] = 'active';
            } else {
                $menuArr['login_history_active'] = '';
                $menuArr['login_history_open'] = '';
                $menuArr['login_history_selected'] = '';
            }
        }
        if (Auth::user()->can('recent-updates-list')) {
            $menuArr['can-recent-updates-list'] = true;
            if (Request::segment(2) == 'recent-updates') {
                $menuArr['recent_active'] = 'active';
                $menuArr['recent_open'] = 'open';
                $menuArr['recent_selected'] = 'selected';
                $menuArr['recmg'] = 'active';
            } else {
                $menuArr['recent_active'] = '';
                $menuArr['recent_open'] = '';
                $menuArr['recent_selected'] = '';
            }
        }
        // }
        if (Auth::user()->can('search-statictics-list')) {
            $menuArr['search-statictics-list'] = true;
            if (Request::segment(2) == 'search-statictics') {
                $menuArr['searchstatictics_active'] = 'active';
                $menuArr['searchstatictics_open'] = 'open';
                $menuArr['searchstatictics_selected'] = 'selected';
                $menuArr['reportmg'] = 'active';
            } else {
                $menuArr['searchstatictics_active'] = '';
                $menuArr['searchstatictics_open'] = '';
                $menuArr['searchstatictics_selected'] = '';
            }
        }
        if (Auth::user()->can('hits-report-list')) {
            $menuArr['hits-report-list'] = true;
            if (Request::segment(2) == 'hits-report') {
                $menuArr['hitsreport_active'] = 'active';
                $menuArr['hitsreport_open'] = 'open';
                $menuArr['hitsreport_selected'] = 'selected';
                $menuArr['reportmg'] = 'active';
            } else {
                $menuArr['hitsreport_active'] = '';
                $menuArr['hitsreport_open'] = '';
                $menuArr['hitsreport_selected'] = '';
            }
        }
        if (Auth::user()->can('document-report-list')) {
            $menuArr['document-report-list'] = true;
            if (Request::segment(2) == 'document-report') {
                $menuArr['documentreport_active'] = 'active';
                $menuArr['documentreport_open'] = 'open';
                $menuArr['documentreport_selected'] = 'selected';
                $menuArr['reportmg'] = 'active';
            } else {
                $menuArr['documentreport_active'] = '';
                $menuArr['documentreport_open'] = '';
                $menuArr['documentreport_selected'] = '';
            }
        }
        if ($userIsAdmin) {
            $menuArr['can-blockedip-list'] = true;
            if (Request::segment(2) == 'blocked-ips') {
                $menuArr['blockedip_active'] = 'active';
                $menuArr['blockedip_open'] = 'open';
                $menuArr['blockedip_selected'] = 'selected';
                $menuArr['settings'] = 'active';
            } else {
                $menuArr['blockedip_active'] = '';
                $menuArr['blockedip_open'] = '';
                $menuArr['blockedip_selected'] = '';
            }
        }

        $menuArr['can-media-manager-list'] = true;
        if (Request::segment(2) == 'media-manager') {
            $menuArr['mediamanager_active'] = 'active';
            $menuArr['mediamanager_open'] = 'open';
            $menuArr['mediamanager_selected'] = 'selected';
            $menuArr['settings'] = 'active';
        } else {
            $menuArr['mediamanager_active'] = '';
            $menuArr['mediamanager_open'] = '';
            $menuArr['mediamanager_selected'] = '';
        }

        if ($userIsAdmin) {
            $menuArr['can-liveuser-list'] = true;
            if (Request::segment(2) == 'live-user') {
                $menuArr['liveuser_active'] = 'active';
                $menuArr['liveuser_open'] = 'open';
                $menuArr['liveuser_selected'] = 'selected';
            } else {
                $menuArr['liveuser_active'] = '';
                $menuArr['liveuser_open'] = '';
                $menuArr['liveuser_selected'] = '';
            }
        }

        $menuArr['can-security-list'] = true;
        if (Request::segment(2) == 'security-settings') {
            $menuArr['security_active'] = 'active';
            $menuArr['security_open'] = 'open';
            $menuArr['security_selected'] = 'selected';
        } else {
            $menuArr['security_active'] = '';
            $menuArr['security_open'] = '';
            $menuArr['security_selected'] = '';
        }

        if (null == Request::segment(2)) {
        }
        return $menuArr;
    }

}
