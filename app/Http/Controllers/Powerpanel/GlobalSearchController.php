<?php

namespace App\Http\Controllers\Powerpanel;

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
use Powerpanel\SearchStaticticsReport\Models\GlobalSearch;
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
        $term = Request::post('term');
        $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
        $term = preg_replace('!\s+!', ' ', $term);
        $dataResult = array();
        if (strlen($term) > 2 && trim($onlystring) != "" && !in_array(strtolower($term), self::$ignoreCommonWords)) {
            $dataResult = GlobalSearch::autocomplete_termSeach($term);
        }
        if (!empty($dataResult)) {
            $response .= Self::generateAutoSuggestionLink($dataResult, $term);
        }
        return $response;
    }

    /**
     * This method loads Search result list view
     * @return  View
     * @since   2018-09-15
     * @author  NetQuick
     */
    public function search(Request $request) {
        $data = Request::post();
        $currentPage = (null !== (Request::post('page'))) ? Request::post('page') : 1;
        $limit = '';
        $rules = array('searchValue' => 'required|handle_xss');
        $messsages = array(
            'searchValue.required' => 'Search term is required',
            'searchValue.handle_xss' => 'Please enter valid search'
        );
        $validator = Validator::make($data, $rules, $messsages);
        if (!$validator->passes()) {
            return Redirect::to($data['current_page'])->withErrors($validator)->withInput();
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

        foreach ($dataResult as $index => $result) {
            if ($result->pageAliasId != 'na') {
                $pageAlias = Alias::select('alias.varAlias')
                        ->where('alias.id', $result->pageAliasId)
                        ->first();
                if (isset($pageAlias->varAlias)) {
                    $dataResult[$index]->pageAlias = $pageAlias->varAlias;
                }
                continue;
            }
            if ($result->intFKCategory == 'na' || $result->slug === null) {

                #Page alias================================
                if ($result->moduleId != 4) {
                    $pageAlias = CmsPage::select('alias.varAlias')
                            ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                            ->where('cms_page.intFKModuleCode', $result->moduleId)
                            ->where('cms_page.chrMain', 'Y')
                            ->where('cms_page.chrPublish', 'Y')
                            ->where('cms_page.chrDelete', 'N')
                            ->first();
                    if (isset($pageAlias->varAlias)) {
                        $dataResult[$index]->pageAlias = $pageAlias->varAlias;
                    }
                }

                if ($result->intFKCategory != 'na') {
                    $MODEL = '\\App\\' . $result->varModelName;
                    if ($result->varModelName != 'Faq') {
                        $categoryRecordAlias = Mylibrary::getRecordAliasByModuleNameRecordId($result->varModuleName, $result->intFKCategory);
                        $dataResult[$index]->pageAlias = $dataResult[$index]->pageAlias . "/" . $categoryRecordAlias;
                    } else {
                        $dataResult[$index]->pageAlias = $dataResult[$index]->pageAlias;
                    }
                }

                #./Page alias================================
            } else {

                if ($result->varModuleName == "news") {
                    $categoryModuleObj = Modules::where('varModuleName', 'news-category')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "news-category";
                } else {
                    $pagemodulecode = $result->moduleId;
                }

                if (isset($catModuleName)) {
                    $pageAlias = CmsPage::select('alias.varAlias')
                            ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                            ->where('cms_page.intFKModuleCode', $pagemodulecode)
                            ->where('cms_page.chrMain', 'Y')
                            ->where('cms_page.chrPublish', 'Y')
                            ->where('cms_page.chrDelete', 'N')
                            ->first();
                    if (isset($pageAlias->varAlias)) {
                        $dataResult[$index]->pageAlias = $pageAlias->varAlias;
                    }
                }

                #Category page alias================================
                if (isset($catModuleName)) {
                    $categoryRecordAlias = Mylibrary::getRecordAliasByModuleNameRecordId($catModuleName, $result->intFKCategory);
                    $dataResult[$index]->pageAlias = $dataResult[$index]->pageAlias . "/" . $categoryRecordAlias;
                    if ($result->varModuleName == "publications") {
                        $dataResult[$index]->searchView = "docdownload";
                    }
                }
            }
        }
        $data = array();
        $data['similarWords'] = array_unique($similarWords);
        $data['searchResults'] = $dataResult;
        $data['searchFoundCounter'] = (!empty($dataResult)) ? GlobalSearch::termSeach($term, $limit, true, 'P') : 0;
        $data['searchTerm'] = $term;
        $data['ajaxModuleUrl'] = url('/powerpanel');
        if (CustomRequest::ajax()) {
            $returnRepsonse = array();
            $returnHtml = view('powerpanelsearch-found-ajax', $data)->render();
            return $returnHtml;
        } else {
            return view('search-found', $data);
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

        foreach ($dataResult as $index => $result) {
            if ($result->pageAliasId != 'na') {
                $pageAlias = Alias::select('alias.varAlias')
                        ->where('alias.id', $result->pageAliasId)
                        ->first();
                if (isset($pageAlias->varAlias)) {
                    $dataResult[$index]->pageAlias = $pageAlias->varAlias;
                    if ($result->varModuleName == "publications-category") {
                        $dataResult[$index]->searchView = "docdownload";
                    }
                }
                continue;
            }
            if ($result->intFKCategory == 'na' || $result->slug === null) {

                #Page alias================================
                if ($result->moduleId != 4) {
                    $pageAlias = CmsPage::select('alias.varAlias')
                            ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                            ->where('cms_page.intFKModuleCode', $result->moduleId)
                            ->where('cms_page.chrMain', 'Y')
                            ->where('cms_page.chrPublish', 'Y')
                            ->where('cms_page.chrDelete', 'N')
                            ->first();
                    if (isset($pageAlias->varAlias)) {
                        $dataResult[$index]->pageAlias = $pageAlias->varAlias;
                    }
                }

                if ($result->intFKCategory != 'na') {
                    $MODEL = '\\App\\' . $result->varModelName;
                    if ($result->varModelName != 'Faq') {
                        $categoryRecordAlias = Mylibrary::getRecordAliasByModuleNameRecordId($result->varModuleName, $result->intFKCategory);
                        $dataResult[$index]->pageAlias = $dataResult[$index]->pageAlias . "/" . $categoryRecordAlias;
                    } else {
                        $dataResult[$index]->pageAlias = $dataResult[$index]->pageAlias;
                    }
                }

                #./Page alias================================
            } else {

                if ($result->varModuleName == "news") {
                    $categoryModuleObj = Modules::where('varModuleName', 'news-category')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "news-category";
                } else {
                    $pagemodulecode = $result->moduleId;
                }

                if (isset($catModuleName)) {
                    $pageAlias = CmsPage::select('alias.varAlias')
                            ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                            ->where('cms_page.intFKModuleCode', $pagemodulecode)
                            ->where('cms_page.chrMain', 'Y')
                            ->where('cms_page.chrPublish', 'Y')
                            ->where('cms_page.chrDelete', 'N')
                            ->first();
                    if (isset($pageAlias->varAlias)) {
                        $dataResult[$index]->pageAlias = $pageAlias->varAlias;
                    }
                }

                #Category page alias================================
                if (isset($catModuleName)) {
                    $categoryRecordAlias = Mylibrary::getRecordAliasByModuleNameRecordId($catModuleName, $result->intFKCategory);
                    $dataResult[$index]->pageAlias = $dataResult[$index]->pageAlias . "/" . $categoryRecordAlias;
                    if ($result->varModuleName == "publications") {
                        $dataResult[$index]->searchView = "docdownload";
                    }
                }
            }
        }

        //code for genrate link for result
        $searchResults = $dataResult;
        if (!empty($searchResults) && count($searchResults) > 0) {
            foreach ($searchResults as $result) {
                if (is_array($result->pageAlias)) {
                    foreach ($result->pageAlias as $alias) {
                        if ($result->slug != 'null') {
                            $url = url('/' . $alias . '/' . $result->slug);
                        } else {
                            $url = url('/' . $alias);
                        }
                        $mtitle = $result->moduleTitle;
                        $returnHtml .= '<li>' . '<a href="' . $url . '">' . $result->term . ' - <span class="mtitle">' . ucfirst($mtitle) . '</span>' . '</a>' . '</li>';
                    }
                } else {
                    if (isset($result->pageAlias)) {
                        if ($result->slug != 'null') {
                            $licenceignoreids = array('2', '3', '4');
                            $url = url('/' . $result->pageAlias . '/' . $result->slug);
                        } else {
                            $url = url('/' . $result->pageAlias);
                            if ($result->moduleId == 47) {
                                $url = $url . '/?title=' . $searchTerm;
                            }
                        }
                    } else {
                        $url = url('/' . $result->slug);
                    }


                    $mtitle = $result->moduleTitle;
                    $returnHtml .= '<li>' . '<a href="' . $url . '">' . $result->term . ' - <span class="mtitle">' . ucfirst($mtitle) . '</span>' . '</a>' . '</li>';
                }
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
