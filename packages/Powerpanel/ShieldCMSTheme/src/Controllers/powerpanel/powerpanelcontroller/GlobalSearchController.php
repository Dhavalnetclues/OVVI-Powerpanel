<?php

namespace Powerpanel\ShieldCMSTheme\Controllers\Powerpanel\powerpanelcontroller;

use App\Http\Controllers\PowerpanelController;
use config;
use Request;
use Request as CustomRequest;
use Illuminate\Support\Facades\Redirect;
use App\Modules;
use DB;
use App\Helpers\MyLibrary;
use App\Helpers\DocumentHelper;
use App\Helpers\GlobalSearch_hits;
use Powerpanel\ShieldCMSTheme\Models\GlobalSearch;
use Powerpanel\CmsPage\Models\CmsPage;
use App\Alias;
use App\Helpers\FileToText;
use App\Helpers\RemoteFileToText;
use Validator;

class GlobalSearchController extends PowerpanelController {
    /*
     * Create a new controller instance.
     *
     * @return void
     */

    public static $ignoreCommonWords = array('the', 'and', 'are');

    public function __construct() {
        parent::__construct();
    }

    /**
     * This method loads index of Search Page
     * @return  View
     * @since   2018-09-15
     * @author  NetQuick
     */
    public function index() {
        $postData = Request::get();
        $term = Request::get('searchValue');
        if ($term == "") {
            return redirect('/');
        }
        $data = [];
        $detailPageTitle = 'Serach';
        $data['detailPageTitle'] = $detailPageTitle;
        return view('frontsearch', $data);
    }

    public function autoComplete() {
        $response = '';
        $term = Request::post('searchValue');
        $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
        $term = preg_replace('!\s+!', ' ', $term);
        $dataResult = array();
        if (strlen($term) > 2 && trim($onlystring) != "" && !in_array(strtolower($term), self::$ignoreCommonWords)) {
            $dataResult = GlobalSearch::autocomplete_termSeach($term);
        }
        $retrunArray=[];
        if (!empty($dataResult) && count($dataResult)>0) {
            $response .= Self::generateAutoSuggestionLink($dataResult, $term);
            $retrunArray=[
	        		"resultcount"=> count($dataResult),
	        		"resulthtml" => $response
	        	];
        } else {
        	$response = '<a href="javascript:void(0);" class="dropdown-item notify-item text-center"><span>No result found</span></a>';
        	$retrunArray=[
        		"resultcount"=> 0,
        		"resulthtml" => $response
        	];
        }

        return json_encode($retrunArray);
    }

    /**
     * This method loads Search result list view
     * @return  View
     * @since   2018-09-15
     * @author  NetQuick
     */
    public function search(Request $request) {
        $data = Request::all();
        $data['searchValue'] = urldecode($data['searchValue']);
        $currentPage = (null !== (Request::get('page'))) ? Request::get('page') : 1;
        $limit = 2;
        $rules = array('searchValue' => 'required|handle_xss');
        $messsages = array(
            'searchValue.required' => 'Search term is required',
            'searchValue.handle_xss' => 'Please enter valid search'
        );
        $validator = Validator::make($data, $rules, $messsages);
        if (!$validator->passes()) {
            //return Redirect::to($data['current_page'])->withErrors($validator)->withInput();
        }
        $term = $data['searchValue'];
        $term = preg_replace('!\s+!', ' ', $term);
        $dataResult = array();
        $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
        if (strlen($term) > 2 && $onlystring != "" && !in_array(strtolower($term), self::$ignoreCommonWords)) {
            $dataResult = GlobalSearch::termSeach($term, $limit, '', 'P');
        }
        
        $similarWords = array();
        if (!empty($dataResult) && count($dataResult) > 0) {
            GlobalSearch_hits::insertSearchHits($term);
        } else {
            //code for get top 5 similar word from data
            if (strlen($term) > 2 && trim($onlystring) != "" && !in_array(strtolower($term), self::$ignoreCommonWords)) {
                $getTop5Words = GlobalSearch::getTopSimilarWords(self::cleanString($term));
                if (!empty($getTop5Words)) {
                    foreach ($getTop5Words as $key => $value) {
                        $similarWords[] = $value->varTitle;
                    }
                }
            }
        }

        $data = array();
        $data['similarWords'] = array_unique($similarWords);
        $data['searchResults'] = $dataResult;
        $data['searchFoundCounter'] = (!empty($dataResult)) ? GlobalSearch::termSeach($term, $limit, true) : 0;
        $data['searchTerm'] = $term;
        $data['ajaxModuleUrl'] = url('/powerpanel');
        $data['breadcrumb'] = array('title'=>'search');
        $data['currentPage'] = $currentPage;
        $data['lastPage'] = ceil($data['searchFoundCounter'] / $limit);
        if (CustomRequest::ajax()) {
            $returnRepsonse = array();

            $returnHtml = view('shiledcmstheme::powerpanel.partials.powerpanelsearch-found-ajax', $data)->render();
            $returnRepsonse = array('html' => $returnHtml, 'lastpage' => $data['lastPage'], 'currentpage' => $data['currentPage']);
            if ($data['lastPage'] > $data['currentPage']) {
							$returnRepsonse['loadmoreHtml'] = '<div class="ajaxLoadmorebtn">
																	<div class="load-more text-center mt-4 mb-1">
																			<a href="javascript:;" id="load-more" title="Load More" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
																				<div class="flex-shrink-0">
                                            <i class="ri-loader-2-line label-icon align-middle fs-20 me-2 loadicon"></i>
                                        </div> Load More	                  
																			</a>
																	</div>
																</div>';
						}
            return $returnRepsonse;
        } else {
            return view('shiledcmstheme::powerpanel.partials.powerpanelsearch-found', $data);
        }
    }

    /**
     * This method genrate link for autosuggestion Search result list view
     * @return  View
     * @since   2018-09-15
     * @author  NetQuick
     */
    public function generateAutoSuggestionLink($dataResult, $searchTerm) {
        $returnHtml = "";

        //code for genrate link for result
        $searchResults = $dataResult;
        if (!empty($searchResults) && count($searchResults) > 0) {
            foreach ($searchResults as $result) {
              $url = url('/powerpanel/'.$result->varModuleName);
              $url = $url.'/?term='.urlencode($result->term);
              $mtitle = $result->moduleTitle;
              //$returnHtml .= '<li>' . '<a href="' . $url . '">' . $result->term . ' - <span class="mtitle">' . ucfirst($mtitle) . '</span>' . '</a>' . '</li>';
              $returnHtml .= '<a target="_blank" href="'.$url.'" class="dropdown-item notify-item">
                    <i class="ri-bubble-chart-line align-middle fs-18 text-muted me-2"></i>
                    <span>'.substr($result->term, 0, 40).' - <strong class="mtitle">(' . ucfirst($mtitle) . ')</strong>'.'</span>
                </a>';
            }
        }

        return $returnHtml;
    }

    public function searchContentPdf($fileSource = false, $searchString = false) {
        if ($fileSource) {
            //$docObj = new FileToText($fileSource);
            //$filecontent = $docObj->convertToText();
            /* new code */
            $docObj = new RemoteFileToText($fileSource);
            $filecontent = $docObj->convertToText();

            $filecontent = $this->get_string_between($filecontent, $searchString);
            $filecontent = (!empty($filecontent)) ? $filecontent . "..." : '';
            return $filecontent;
        }
    }

    public function get_string_between($string, $start, $end = false) {
        $string = ' ' . $string;
        $string = strtolower($string);
        $start = strtolower($start);
        $ini = strpos($string, $start);
        if ($ini == 0)
            return '';
        if ($end == false) {
            $len = $ini + 50;
        } else {
            $len = strpos($string, $end, $ini) - $ini;
        }
        return substr($string, $ini, $len);
    }

    function is_url_exist($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    public static function cleanString($string) {
        return addslashes($string);
    }

}
