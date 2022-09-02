<?php

namespace App\Http\Controllers;

use App\Alias;
use App\GlobalSearch;
use App\Helpers\GlobalSearch_hits;
use App\Helpers\MyLibrary;
use App\Helpers\RemoteFileToText;
use App\Modules;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\CmsPage\Models\CmsPage;
use Request;
use Request as CustomRequest;
use Validator;

class SearchController extends FrontController
{
    /*
     * Create a new controller instance.
     *
     * @return void
     */

    public static $ignoreCommonWords = array('the', 'and', 'are');

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This method loads index of Search Page
     * @return  View
     * @since   2018-09-15
     * @author  NetQuick
     */
    public function index()
    {
        $term = Request::get('globalSearch');
        if ($term == "") {
            return redirect('/');
        }
        return view('search');
    }

    public function autoComplete()
    {
        $response = '';
        $term = Request::post('term');
        $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
        $term = preg_replace('!\s+!', ' ', $term);
        $dataResult = array();
        if (strlen($term) > 2 && trim($onlystring) != "" && !in_array(strtolower($term), self::$ignoreCommonWords)) {
            $dataResult = GlobalSearch::autocomplete_termSeach($term);
        }
        if (!empty($dataResult)) {
            /*foreach ($dataResult as $record) {
            $liDispTerm = str_replace("\xE2\x80\x8B", "", $record->term);
            $response .= '<li>' . $liDispTerm . '</li>';
            }*/
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
        $limit = 10;
        $rules = array('globalSearch' => 'required|handle_xss');
        $messsages = array(
            'globalSearch.required' => 'Search term is required',
            'globalSearch.handle_xss' => 'Please enter valid search',
        );
        $validator = Validator::make($data, $rules, $messsages);
        if (!$validator->passes()) {
            return Redirect::to($data['current_page'])->withErrors($validator)->withInput();
        }
        $term = urldecode(strip_tags($data['globalSearch']));
        $term = preg_replace('!\s+!', ' ', $term);
      
        $dataResult = array();
        $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
        if (strlen($term) > 2 && $onlystring != "" && !in_array(strtolower($term), self::$ignoreCommonWords)) {
            $dataResult = GlobalSearch::termSeach($term, $limit);
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

            if ($result->intFKCategory == 'na' || $result->slug === null || $result->slug == '') {
                #Page alias================================
                if ($result->moduleId != 4) {
                    if ($result->varModuleName == "publications") {
                        $dataResult[$index]->pageAlias = 'publications';
                    } elseif ($result->varModuleName == "forms-and-fees") {
                        $dataResult[$index]->pageAlias = 'forms-and-fees';
                    } elseif ($result->varModuleName == "decision") {
                        $dataResult[$index]->pageAlias = 'decisions';
                    } elseif ($result->varModuleName == "faq") {
                        $dataResult[$index]->pageAlias = 'ict/faq';
                    } elseif ($result->varModuleName == "interconnections") {
                        $dataResult[$index]->pageAlias = 'ict/interconnections';
                    } elseif ($result->varModuleName == "candwservice") {
                        $dataResult[$index]->pageAlias = 'ict/cw-service-filings';
                    } elseif ($result->varModuleName == "public-record") {
                        $dataResult[$index]->pageAlias = 'ict/public-record-of-key-topics';
                    } else {
                        $pageAlias = CmsPage::select('alias.varAlias')
                            ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                            ->where('alias.id', $result->aliasId)
                            ->where('cms_page.chrMain', 'Y')
                            ->where('cms_page.chrPublish', 'Y')
                            ->where('cms_page.chrDelete', 'N')
                            ->first();
                        if (isset($pageAlias->varAlias)) {
                            $dataResult[$index]->pageAlias = $pageAlias->varAlias;
                        }
                    }

                }
                if ($result->intFKCategory != 'na') {
                    $MODEL = '\\App\\' . $result->varModelName;

                    $categoryRecordAlias = Mylibrary::getRecordAliasByModuleNameRecordId($result->varModuleName, $result->intFKCategory);
                    $dataResult[$index]->pageAlias = $dataResult[$index]->pageAlias . "/" . $categoryRecordAlias;
                }

                #./Page alias================================
            } else {
                if ($result->varModuleName == "news") {
                    $categoryModuleObj = Modules::where('varModuleName', 'news')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "news";
                } else if ($result->varModuleName == "events") {
                    $categoryModuleObj = Modules::where('varModuleName', 'events')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "events";
                } else if ($result->varModuleName == "publications") {
                    $categoryModuleObj = Modules::where('varModuleName', 'publications')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "publications";
                } else if ($result->varModuleName == "decision") {
                    $categoryModuleObj = Modules::where('varModuleName', 'decision')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "decision";
                } else if ($result->varModuleName == "register-application") {
                    $categoryModuleObj = Modules::where('varModuleName', 'register-application')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "register-application";
                } else if ($result->varModuleName == "blogs") {
                    $categoryModuleObj = Modules::where('varModuleName', 'blogs')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "blogs";
                } else if ($result->varModuleName == "careers") {
                    $categoryModuleObj = Modules::where('varModuleName', 'careers')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "careers";
                } else if ($result->varModuleName == "consultations") {
                    $categoryModuleObj = Modules::where('varModuleName', 'consultations')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "consultations";
                } else if ($result->varModuleName == "forms-and-fees") {
                    $categoryModuleObj = Modules::where('varModuleName', 'forms-and-fees')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "forms-and-fees";
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
            }
        }

        $data = array();
        $data['similarWords'] = array_unique($similarWords);
        $data['searchResults'] = $dataResult;
        $data['searchFoundCounter'] = (!empty($dataResult)) ? GlobalSearch::termSeach($term, $limit, true) : 0;
        $data['searchTerm'] = $term;
        $data['ajaxModuleUrl'] = url('/search');
        $data['currentPage'] = $currentPage;
        $data['lastPage'] = ceil($data['searchFoundCounter'] / $limit);
        //$data['searchDocs'] = $this->docSearch($term);
        if (strlen($term) > 2 && $onlystring != "" && !in_array(strtolower($term), self::$ignoreCommonWords)) {
            $data['searchDocs'] = $this->documentsTableSearch($term);
        }
        // echo '<pre>';print_r($data);die;
        view()->share('META_TITLE', "Search Result - The Utility Regulation and Competition Office in the Cayman Islands");
        view()->share('META_KEYWORD', "search The Utility Regulation and Competition Office in the Cayman Islands website, The Utility Regulation and Competition Office in the Cayman Islands website search option, search on The Utility Regulation and Competition Office in the Cayman Islands");
        view()->share('META_DESCRIPTION', "Can't find what you are looking for on the The Utility Regulation and Competition Office in the Cayman Islands website? Enter your keywords in the search bar and get a list of all the matching pages quickly. Search now!");

        if (CustomRequest::ajax()) {
            $returnRepsonse = array();
            $returnHtml = view('search-found-ajax', $data)->render();
            $returnRepsonse = array('html' => $returnHtml, 'lastpage' => $data['lastPage'], 'currentpage' => $data['currentPage']);
            if ($data['lastPage'] > $data['currentPage']) {
                $returnRepsonse['loadmoreHtml'] = '<div class="col-sm-12 load-more">
											<a href="javascript:;" id="load-more" title="Load More" class="btn-load">
													Load More<i class="fa fa-plus" aria-hidden="true"></i>
											</a>
									</div>';
            }
            return $returnRepsonse;
        } else {
            return view('search', $data);
        }

    }

    public function documentsTableSearch($term)
    {
        $response = [];
        $records = GlobalSearch::getDocumentsSearchByTerm($term);
        $AWSContants = MyLibrary::getAWSconstants();
        $_APP_URL = $AWSContants['CDN_PATH'];

        if (!empty($records)) {
            foreach ($records as $record) {
                $link = "";
                if ($AWSContants['BUCKET_ENABLED']) {
                    $link = $_APP_URL . $AWSContants['S3_MEDIA_BUCKET_DOCUMENT_PATH'] . '/' . $record->txtSrcDocumentName . '.' . $record->varDocumentExtension;
                } else {
                    $link = url('/documents/' . $record->txtSrcDocumentName . '.' . $record->varDocumentExtension);
                }
                $response[$record->id]['id'] = $record->id;
                $response[$record->id]['link'] = $link;
                $response[$record->id]['ext'] = $record->varDocumentExtension;
                $response[$record->id]['filename'] = $record->txtDocumentName;
                $response[$record->id]['txtSrcDocumentName'] = $record->txtSrcDocumentName;
            }
        }

        return $response;
    }

    /**
     * This method genrate link for autosuggestion Search result list view
     * @return  View
     * @since   2018-09-15
     * @author  NetQuick
     */
    public static function generateAutoSuggestionLink($dataResult, $searchTerm)
    {
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

                    $categoryRecordAlias = Mylibrary::getRecordAliasByModuleNameRecordId($result->varModuleName, $result->intFKCategory);
                    $dataResult[$index]->pageAlias = $dataResult[$index]->pageAlias . "/" . $categoryRecordAlias;
                }

                #./Page alias================================
            } else {

                if ($result->varModuleName == "news") {
                    $categoryModuleObj = Modules::where('varModuleName', 'news-category')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "news-category";
                } else if ($result->varModuleName == "events") {
                    $categoryModuleObj = Modules::where('varModuleName', 'event-category')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "event-category";
                } else if ($result->varModuleName == "publications") {
                    $categoryModuleObj = Modules::where('varModuleName', 'publications-category')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "publications-category";
                } else if ($result->varModuleName == "decision") {
                    $categoryModuleObj = Modules::where('varModuleName', 'decision-category')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "decision-category";
                } else if ($result->varModuleName == "photo-album") {
                    $categoryModuleObj = Modules::where('varModuleName', 'photo-album-category')->first();
                    $pagemodulecode = $categoryModuleObj->id;
                    $catModuleName = "photo-album-category";
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
                    if ($result->varModuleName == "decision") {
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

                        if ($result->moduleTitle == 'Event Category') {
                            $mtitle = 'Events';
                        } elseif ($result->moduleTitle == 'News Category') {
                            $mtitle = 'News';
                        } elseif ($result->moduleTitle == 'Photo Album Category') {
                            $mtitle = 'Photo Album';
                        } elseif ($result->moduleTitle == 'Publications Category') {
                            $mtitle = 'Publications';
                        } elseif ($result->moduleTitle == 'decision Category') {
                            $mtitle = 'decision';
                        } elseif ($result->moduleTitle == 'FAQ Category') {
                            $mtitle = 'FAQs';
                        } else {
                            $mtitle = $result->moduleTitle;
                        }

                        $returnHtml .= '<li>' . '<a href="' . $url . '">' . $result->term . ' - <span class="mtitle">' . ucfirst($mtitle) . '</span>' . '</a>' . '</li>';

                    }
                } else {
                    if (isset($result->pageAlias)) {
                        if ($result->slug != 'null') {
                            $licenceignoreids = array('2', '3', '4');
                            if ($result->varModelName == "LicensedEntities") {
                                if (in_array($result->intFKCategory, $licenceignoreids)) {
                                    $url = url('/' . $result->pageAlias);
                                } else {
                                    $url = url('/' . $result->pageAlias . '/' . $result->slug);
                                }
                            } else {
                                $url = url('/' . $result->pageAlias . '/' . $result->slug);
                            }
                        } else {
                            $url = url('/' . $result->pageAlias);
                            if ($result->moduleId == 47) {
                                $url = $url . '/?title=' . $searchTerm;
                            }
                        }
                    } else {
                        $url = url('/' . $result->slug);
                    }

                    if ($result->moduleTitle == 'Event Category') {
                        $mtitle = 'Events';
                    } elseif ($result->moduleTitle == 'News Category') {
                        $mtitle = 'News';
                    } elseif ($result->moduleTitle == 'Photo Album Category') {
                        $mtitle = 'Photo Album';
                    } elseif ($result->moduleTitle == 'Publications Category') {
                        $mtitle = 'Publications';
                    } elseif ($result->moduleTitle == 'decision Category') {
                        $mtitle = 'decision';
                    } elseif ($result->moduleTitle == 'FAQ Category') {
                        $mtitle = 'FAQs';
                    } else {
                        $mtitle = $result->moduleTitle;
                    }

                    $returnHtml .= '<li>' . '<a href="' . $url . '">' . $result->term . ' - <span class="mtitle">' . ucfirst($mtitle) . '</span>' . '</a>' . '</li>';
                }
            }
        }

        return $returnHtml;

    }

    public function searchContentPdf($fileSource = false, $searchString = false)
    {
        if ($fileSource) {
            //$docObj = new FileToText($fileSource);
            //$filecontent = $docObj->convertToText();
            /*new code */
            $docObj = new RemoteFileToText($fileSource);
            $filecontent = $docObj->convertToText();

            $filecontent = $this->get_string_between($filecontent, $searchString);
            $filecontent = (!empty($filecontent)) ? $filecontent . "..." : '';
            return $filecontent;
        }
    }

    public function get_string_between($string, $start, $end = false)
    {
        $string = ' ' . $string;
        $string = strtolower($string);
        $start = strtolower($start);
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }

        if ($end == false) {
            $len = $ini + 50;
        } else {
            $len = strpos($string, $end, $ini) - $ini;
        }
        return substr($string, $ini, $len);
    }

    public function is_url_exist($url)
    {
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

    public static function cleanString($string)
    {
        return addslashes($string);
    }

}
