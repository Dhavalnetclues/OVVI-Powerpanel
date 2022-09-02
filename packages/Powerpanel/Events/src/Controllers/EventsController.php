<?php

namespace Powerpanel\Events\Controllers;

use App\Helpers\Email_sender;
use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\EventCategory\Models\EventCategory;
use Powerpanel\Events\Models\EventLead;
use Powerpanel\Events\Models\Events;
use Powerpanel\RoleManager\Models\Role;
use Request;
use Validator;

class EventsController extends FrontController
{

    use slug;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $data = array();
        
        $sector = false;
        $sector_slug = '';
        $segment1 = Request::segment(1);
        $segment2 = Request::segment(2);

        if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
            $sector = true;
            $sector_slug = Request::segment(1);
        }

        if ($sector) {
            $pagename = $segment2;
        } else {
            $pagename = $segment1;
        }

        $aliasId = slug::resolve_alias($pagename, $sector_slug, 3);

        if (Request::segment(3) == 'preview') {
            $cmsPageId = Request::segment(2);
            $pageContent = CmsPage::getPageByPageId($cmsPageId, false);
        } else {
            $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
        }

        // Start CMS PAGE Front Private, Password Prottected Code
        if (isset(auth()->user()->id)) {
            $user_id = auth()->user()->id;
            $role = Role::getRecordById(Role_user::getRecordBYModelId($user_id)->role_id)->name;
        } else {
            $user_id = '';
            $role = '';
        }

        $eventCategories = EventCategory::getCategorys();

        $data['PageData'] = '';
        if (isset($pageContent) && $pageContent->chrPageActive == 'PR') {
            if ($pageContent->UserID == $user_id || $role == 'netquick_admin') {
                if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                    $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                }
            } else {
                return redirect(url('/'));
            }
        } else if (isset($pageContent) && $pageContent->chrPageActive == 'PP') {
            $data['PassPropage'] = 'PP';
            $data['tablename'] = 'cms_page';
            $data['Pageid'] = $pageContent->id;
            $content = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription)['response'];
            $data['isContent'] = (isset($content) && !empty($content)) ? true : false;
        } else {
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
            }
            $data['pageContent'] = $pageContent;
        }

        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }

        // End CMS PAGE Front Private, Password Prottected Code
        $data['eventCategories'] = $eventCategories;
        $data['txtDescription'] = json_encode($pageContent->toArray());
        $data['today'] = date('d-m-y H:i:s');        

        return view('events::frontview.events', $data);

    }

    public function detail($alias) {
        $catid = false;
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if (is_numeric($alias)) {
            $events = Events::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $events = Events::getFrontDetail($id);
        }

        $today = date('Y-m-d');
        if (!empty($events)) {
            if (isset($events->dtDateTime) && !empty($events->dtDateTime)) {
                $eventData = json_decode($events->dtDateTime);
                
                foreach ($eventData as $key => $value) {
                    $eventData[$key]->attendeeRegistered = array();
                    foreach ($value->timeSlotFrom as $valueKey => $data) {
                        $eventLeadCount = EventLead::getEventAttendeeCount($events->id, $value->startDate, $value->endDate, $data, $value->timeSlotTo[$valueKey]);
                        array_push($eventData[$key]->attendeeRegistered, $eventLeadCount);
                    }
                }

                $eventRSVP = 'N';
                foreach ($eventData as $key => $value) {
                    foreach ($value->timeSlotFrom as $valueKey => $data) {
                        // [{"startDate":"2022-08-03","endDate":"2022-09-08","timeSlotFrom":["3:00 PM"],"timeSlotTo":["11:00 AM"],"attendees":["5"]}]
                        if ($value->startDate >= $today || $today <= $value->endDate) {
                            $time = date('H:i');
                            $startTime = date('H:i', strtotime($data));
                            if($value->endDate > $today) {
                                if($value->attendees[$valueKey] > $value->attendeeRegistered[$valueKey]) {  
                                    $eventRSVP = 'Y';
                                    break;
                                }
                            }else{
                                if($time < $startTime) {
                                    if($value->attendees[$valueKey] > $value->attendeeRegistered[$valueKey]) {
                                        $eventRSVP = 'Y';
                                        break;
                                    } 
                                }
                            }
                        } else {
                            $eventRSVP = 'N';
                        }
                    }
                }

                $events->isRSVP = $eventRSVP; 
                $events->eventDateTime = $eventData;
                $events->dtDateTime = json_encode($eventData);
            }

            $relatedCategoryEvents = Events::getFrontDetailByCategory($events->intFKCategory, $events->id);

            $relatedEvents = array();
            if (count($relatedCategoryEvents) > 0) {
                foreach ($relatedCategoryEvents as $key => $event) {
                    $eventData = json_decode($event->dtDateTime);
                    foreach ($eventData as $eventKey => $value) {
                        if ($value->startDate >= $today && $today <= $value->endDate) {
                            array_push($relatedEvents, $event);
                            goto OuterLoop;
                        }
                    }
                    OuterLoop:
                    continue;
                }
            }
            
            $metaInfo = array('varMetaTitle' => $events->varMetaTitle, 'varMetaKeyword' => $events->varMetaKeyword, 'varMetaDescription' => $events->varMetaDescription);
            if (isset($events->varMetaTitle) && !empty($events->varMetaTitle)) {
                view()->share('META_TITLE', $events->varMetaTitle);
            }
            if (isset($events->varMetaKeyword) && !empty($events->varMetaKeyword)) {
                view()->share('META_KEYWORD', $events->varMetaKeyword);
            }
            if (isset($events->varMetaDescription) && !empty($events->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($events->varMetaDescription), 0, 500));
            }
            if (isset($events->fkIntImgId) && !empty($events->fkIntImgId)) {
                $imageLink = \App\Helpers\resize_image::resize($events->fkIntImgId);
                view()->share('ogImage', $imageLink);
            }

            $breadcrumb = [];

            $data = [];
            $moduelFrontPageUrl = MyLibrary::getFront_Uri('events')['uri'];
            $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl : $moduelFrontPageUrl;

            $breadcrumb['title'] = (!empty($events->varTitle)) ? ucwords($events->varTitle) : '';
            $breadcrumb['inner_title'] = (!empty($events->varTitle)) ? ucwords($events->varTitle) : '';
            $breadcrumb['url'] = MyLibrary::getFront_Uri('events')['uri'];

            $breadcrumb = $breadcrumb;            
            $data['moduleTitle'] = 'Events';
            $eventsAllCategoriesArr = EventCategory::getAllCategoriesFrontSidebarList();
            $data['eventsAllCategoriesArr'] = $eventsAllCategoriesArr;
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;
            $data['events'] = $events;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['breadcrumb'] = $breadcrumb;
            $data['detailPageTitle'] = $events->varTitle;
            $data['varEventLocation'] = $events->varEventLocation;
            $data['latestList'] = Events::getLatestList($events->id);
            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($events->txtDescription)['response'];

            return view('events::frontview.events-detail', $data);
        } else {
            abort(404);
        }
    }

    // public function fetchData(Request $request)
    // {
    //     $requestArr = Request::all();
    //     if (isset($requestArr['pageName']) && !empty($requestArr['pageName'])) {
    //         if (is_numeric($requestArr['pageName']) && (int) $requestArr['pageName'] > 0) {
    //             $aliasId = $requestArr['pageName'];
    //         } else {
    //             $aliasId = slug::resolve_alias($requestArr['pageName']);
    //         }

    //         if (is_numeric($aliasId)) {
    //             $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
    //             if (!isset($pageContent->id)) {
    //                 $pageContent = CmsPage::getPageByPageId($aliasId, false);
    //             }
    //         }

    //         $pageContentcms = CmsPage::getPageContentByPageAlias($aliasId);

    //         $data['PageData'] = '';
    //         if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
    //             $txtDesc = json_decode($pageContent->txtDescription);

    //             foreach ($txtDesc as $key => $value) {
    //                 if ($value->type == 'events_template') {
    //                     if (isset($requestArr['category']) && !empty($requestArr['category'])) {
    //                         $value->val->filter['category'] = $requestArr['category'];
    //                     }

    //                     if (isset($requestArr['eventCategory']) && !empty($requestArr['eventCategory'])) {
    //                         $value->val->filter['eventCategory'] = $requestArr['eventCategory'];
    //                     }

    //                     if (isset($requestArr['dateFilter']) && !empty($requestArr['dateFilter'])) {
    //                         $value->val->filter['dateFilter'] = $requestArr['dateFilter'];
    //                     }

    //                     if (isset($requestArr['sortVal']) && !empty($requestArr['sortVal'])) {
    //                         $value->val->filter['sortVal'] = $requestArr['sortVal'];
    //                     }
    //                 }
    //             }

    //             $pageContent->txtDescription = json_encode($txtDesc);

    //             $response = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
    //             return $response;
    //         }

    //     }
    // }

    public function fetchData()
    {
        $requestArr = request()->all();

        if(request()->ajax()){
            $searchText = request()->input('search_action');
        }
        if (isset($requestArr['pageName']) && !empty($requestArr['pageName'])) {
            if (is_numeric($requestArr['pageName']) && (int) $requestArr['pageName'] > 0) {
                $aliasId = $requestArr['pageName'];
            } else {
                $aliasId = slug::resolve_alias($requestArr['pageName']);
            }

            if (is_numeric($aliasId)) {
                $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
                if (!isset($pageContent->id)) {
                    $pageContent = CmsPage::getPageByPageId($aliasId, false);
                }
            }

            // $pageContentcms = CmsPage::getPageContentByPageAlias($aliasId);
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                $txtDesc = json_decode($pageContent->txtDescription);

                foreach ($txtDesc as $key => $value) {
                    if ($value->type == 'events_template') {
                        if (isset($requestArr['category']) && !empty($requestArr['category'])) {
                            $value->val->filter['category'] = $requestArr['category'];
                        }
                        if (isset($requestArr['year']) && !empty($requestArr['year'])) {
                            $value->val->filter['year'] = $requestArr['year'];
                        }
                         if (isset($requestArr['limits']) && !empty($requestArr['limits'])) {
                                $value->val->filter['limits'] = $requestArr['limits'];
                            }
                        if (isset($requestArr['pageNumber']) && !empty($requestArr['pageNumber'])) {
                            $value->val->filter['pageNumber'] = $requestArr['pageNumber'];
                        }
                        if (isset($requestArr['sortVal']) && !empty($requestArr['sortVal'])) {
                            $value->val->filter['sortVal'] = $requestArr['sortVal'];
                        }
                        if (isset($searchText) && !empty($searchText)) {
                            $value->val->filter['search_action'] = $searchText;
                        }
                    }
                }

                $pageContent->txtDescription = json_encode($txtDesc);

                $response = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                return $response;
            }

        }
    }

    public function getCategorySector(Request $request) {
        $requestArr = Request::all();
        $sectorCategory = $requestArr['category'];
        $recordSelect = '';
        if ($sectorCategory == 'All') {
            $recordSelect .= '<option  value="all" >All</option>';
            $eventcategories = EventCategory::getCategorys();
        } else {
            $eventcategories = EventCategory::getCategorySectorwise($sectorCategory);
        }

        foreach ($eventcategories as $record) {

            $recordSelect .= '<option  value="' . $record->id . '" >' . ucwords($record->varTitle) . '</option>';
        }
        return $recordSelect;
    }

    public function store()
    {
        $data = Request::all();
        $messsages = array(
            'event_time.required' => 'Please select event time',
            'event_date.required' => 'Please select event date',
            'no_of_attendee' => 'Please select no of attendee',
            'attendee' => 'Please enter attendee full name and email',
            'g-recaptcha-response.required' => 'Captcha is required',
            'message.handle_xss' => 'Please Enter Valid Input',
        );

        $rules = array(
            'event_time' => 'required',
            'event_date' => 'required',
            'no_of_attendee' => 'required',
            'attendee' => 'required',
            'message' => 'required|handle_xss',
            'g-recaptcha-response' => 'required',
        );

        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $event_lead = new EventLead;
            $event_lead->eventId = (isset($data['eventId']) && !empty($data['eventId'])) ? $data['eventId'] : '';
            if (isset($data['event_date']) && !empty($data['event_date'])) {
                $eventDate = json_decode($data['event_date']);
                $data['eventDate'] = $eventDate;

                $event_lead->startDate = $eventDate->startDate;
                $event_lead->endDate = $eventDate->endDate;
            }

            // if(isset($data['event_time']) && !empty($data['event_time'])) {

            $eventTime = explode("_", $data['event_time']);
            $data['eventTime'] = $eventTime;

            $event_lead->startTime = $eventTime[0];
            $event_lead->endTime = $eventTime[1];

            // }
            if (isset($data['attendee']) && !empty($data['attendee'])) {
                $event_lead->attendeeDetail = json_encode($data['attendee']);
            }

            if (isset($data['message']) && !empty($data['message'])) {
                // $event_lead->message = json_encode($data['message']);
                $event_lead->message = $data['message'];
            }

            if (isset($data['no_of_attendee']) && !empty($data['no_of_attendee'])) {
                $event_lead->noOfAttendee = $data['no_of_attendee'];
            }
            $event_lead->varIpAddress = MyLibrary::get_client_ip();
            $event_lead->save();

            if (!empty($event_lead->id)) {
                $recordID = $event_lead->id;
                Email_sender::eventMail($data, $recordID);
                return redirect('/thankyou')->with(['form_submit' => true, 'message' => 'Your have successfully registered to this event']);
            } else {
                return redirect('/events')->withErrors($validator)->withInput();
            }
        } else {
            return redirect('/events')->withErrors($validator)->withInput();
        }
    }
}
