<?php

namespace Powerpanel\SearchStaticticsReport\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules;
use Powerpanel\CmsPage\Models\CmsPage;
use DB;
use Carbon\Carbon;
use Crypt;
use Request;

class GlobalSearch extends Model {

    protected $table = 'globalsearches';
    public static $ignoreCommonWords = array('the', 'and', 'are');

    /**
     * This method handels retrival of global search data
     * @return  Object
     * @since   2018-09-18
     * @author  NetQuick
     */
    public static function getFrontList() {
        $response = false;
        $searchFields = [
            'id',
            'varTitle',
        ];
        $searchRelFields = ['id', 'fkSearchRecordId', 'varBrowserInfo', 'isWeb', 'varSessionId', 'varIpAddress'];
        $response = Cache::tags(['GlobalSearches'])->get('getGlobalSearchesList');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $searchRelFields)
                    ->deleted()
                    ->publish();
            Cache::tags(['GlobalSearches'])->forever('getGlobalSearchesList', $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of Global search records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getFrontRecords($searchFields = false, $searchRelFields = false) {
        $response = false;
        $data = [];
        if ($searchRelFields != false) {
            $data = [
                'searchrel' => function ($query) use ($searchRelFields) {
                    $query->select($searchRelFields)->publish();
                },
            ];
        }
        $response = self::select($searchFields)->with($data);
        return $response;
    }

    public static function getTopSimilarWords($term = false) {
        $response = false;
        $termMatch = '';
        $termMatchQuery = self::select('varTitle')
                //->whereRaw("SOUNDEX(varTitle) = SOUNDEX('".$term."')")
                ->whereRaw("SOUNDEX(varTitle) LIKE CONCAT(TRIM(TRAILING '0' FROM SOUNDEX('" . $term . "')), '%')")
                ->orWhere('varTitle', "%" . $term . "%")
                ->orderByRaw("LENGTH(varTitle)")
                ->first();
        if (!empty($termMatchQuery)) {
            $termMatch = $termMatchQuery->varTitle;
        }
        $repsonse = self::select(
                        'globalsearches.id', 'globalsearches.varTitle', 'GRl.fkSearchRecordId', 'globalsearches.chrPublish', DB::raw("count('GRl.fkSearchRecordId') as maxcount")
                )
                ->leftjoin("globalsearches_rel as GRl", function($join) {
                    $join->on("GRl.fkSearchRecordId", "=", "globalsearches.id");
                })
                ->whereRaw('soundex(varTitle) = soundex("' . $term . '")');
        if (!empty($termMatch)) {
            $repsonse = $repsonse->orWhere('globalsearches.varTitle', 'like', "%" . $termMatch . "%");
        }
        $repsonse = $repsonse->where('GRl.chrPublish', 'Y')
                ->where('GRl.chrDelete', 'N')
                ->where('globalsearches.chrPublish', 'Y')
                ->where('globalsearches.chrDelete', 'N')
                ->orderBy('maxcount', 'DESC')
                ->groupBy('globalsearches.id')
                ->limit(10)
                ->get();
        return $repsonse;
    }

    /**
     * This method handels search relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function searchrel() {
        return $this->hasMany('App\GlobalSearchRel', 'fkSearchRecordId', 'id');
    }

    public static function news($term) {
        #News==============================
        $terms = str_word_count($term, 1);
        $newsModuleObj = Modules::where('varModuleName', 'news')->first();
        $news = DB::table('news')
                ->select(
                        'news.intSearchRank', 'news.varTitle as term', 'news.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'news.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'news.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'news.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $newsModuleObj->id)
                ->where('news.chrPublish', '=', 'Y')
                ->where('news.chrDelete', '=', 'N')
                ->where('news.chrMain', 'Y')
                ->where('news.chrIsPreview', 'N');
        $rawstring = '(nq_news.varTitle = "' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $news = $news->whereRaw($rawstring);
        $news = $news->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $news;
        #.\News==============================
    }

    public static function news_liketitle($term) {
        #News==============================
        $terms = str_word_count($term, 1);
        $newsModuleObj = Modules::where('varModuleName', 'news')->first();
        $news = DB::table('news')
                ->select(
                        'news.intSearchRank', 'news.varTitle as term', 'news.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'news.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'news.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'news.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $newsModuleObj->id)
                ->where('news.chrPublish', '=', 'Y')
                ->where('news.chrDelete', '=', 'N')
                ->where('news.chrMain', 'Y')
                ->where('news.chrIsPreview', 'N');
        $rawstring = '(nq_news.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $news = $news->whereRaw($rawstring);
        $news = $news->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $news;
        #.\News==============================
    }

    public static function news_splitWordTitle($term) {
        #News==============================
        $terms = str_word_count($term, 1);
        $newsModuleObj = Modules::where('varModuleName', 'news')->first();
        $news = DB::table('news')
                ->select(
                        'news.intSearchRank', 'news.varTitle as term', 'news.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'news.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'news.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'news.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $newsModuleObj->id)
                ->where('news.chrPublish', '=', 'Y')
                ->where('news.chrDelete', '=', 'N')
                ->where('news.chrMain', 'Y')
                ->where('news.chrIsPreview', 'N');
        $rawstring = '(nq_news.varTitle like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_news.varTitle like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $news = $news->whereRaw($rawstring);
        $news = $news->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $news;
        #.\News==============================
    }

    public static function news_splitWordDescription($term) {
        #News==============================
        $terms = str_word_count($term, 1);
        $newsModuleObj = Modules::where('varModuleName', 'news')->first();
        $news = DB::table('news')
                ->select(
                        'news.intSearchRank', 'news.varTitle as term', 'news.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'news.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'news.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'news.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $newsModuleObj->id)
                ->where('news.chrPublish', '=', 'Y')
                ->where('news.chrDelete', '=', 'N')
                ->where('news.chrMain', 'Y')
                ->where('news.chrIsPreview', 'N');
        $rawstring = '(nq_news.txtDescription like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_news.txtDescription like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $news = $news->whereRaw($rawstring);
        $news = $news->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $news;
        #.\News==============================
    }

    public static function pages($term) {
        #CMS Pages==============================
        $terms = str_word_count($term, 1);
        $cmsPagesModuleObj = Modules::where('varModuleName', 'pages')->first();
        $ignoreId = [0];
        $pages = CmsPage::select(
                        'cms_page.intSearchRank', 'cms_page.varTitle as term', 'cms_page.txtDescription as info', //item has desc
                        'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'cms_page.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $cmsPagesModuleObj->id)
                ->where('cms_page.chrPublish', '=', 'Y')
                ->where('cms_page.chrMain', 'Y')
                ->where('cms_page.chrDelete', '=', 'N')
                ->where('cms_page.chrIsPreview', 'N')
                ->whereNotIn('cms_page.id', $ignoreId);
        $rawstring = '( nq_cms_page.varTitle="' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $pages = $pages->whereRaw($rawstring);

        $pages = $pages->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');

        #.\CMS Pages==============================
        return $pages;
    }

    public static function pages_liketitle($term) {
        #CMS Pages==============================
        $terms = str_word_count($term, 1);
        $cmsPagesModuleObj = Modules::where('varModuleName', 'pages')->first();
        $ignoreId = [0];
        $pages = CmsPage::select(
                        'cms_page.intSearchRank', 'cms_page.varTitle as term', 'cms_page.txtDescription as info', //item has desc
                        'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'cms_page.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $cmsPagesModuleObj->id)
                ->where('cms_page.chrPublish', '=', 'Y')
                ->where('cms_page.chrMain', 'Y')
                ->where('cms_page.chrDelete', '=', 'N')
                ->where('cms_page.chrIsPreview', 'N')
                ->whereNotIn('cms_page.id', $ignoreId);
        $rawstring = '( nq_cms_page.varTitle like "%' . self::cleanString($term) . '%" ';
        $rawstring .= ')';
        $pages = $pages->whereRaw($rawstring);
        //->whereRaw('(nq_cms_page.varTitle like "%'.$term.'%" or nq_cms_page.txtDescription like "%'.$term.'%")')
        $pages = $pages->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
//        $pages = $pages->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))')->orderBy('cms_page.intSearchRank', 'ASC');
        #.\CMS Pages==============================
        return $pages;
    }

    public static function pages_splitWordTitle($term) {
        #CMS Pages==============================
        $terms = str_word_count($term, 1);
        $cmsPagesModuleObj = Modules::where('varModuleName', 'pages')->first();
        $ignoreId = [0];
        $pages = CmsPage::select(
                        'cms_page.intSearchRank', 'cms_page.varTitle as term', 'cms_page.txtDescription as info', //item has desc
                        'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'cms_page.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $cmsPagesModuleObj->id)
                ->where('cms_page.chrPublish', '=', 'Y')
                ->where('cms_page.chrMain', 'Y')
                ->where('cms_page.chrDelete', '=', 'N')
                ->where('cms_page.chrIsPreview', 'N')
                ->whereNotIn('cms_page.id', $ignoreId);
        $rawstring = '(nq_cms_page.varTitle like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_cms_page.varTitle like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $pages = $pages->whereRaw($rawstring);
        //->whereRaw('(nq_cms_page.varTitle like "%'.$term.'%" or nq_cms_page.txtDescription like "%'.$term.'%")')
        $pages = $pages->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
//        $pages = $pages->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))')->orderBy('cms_page.intSearchRank', 'ASC');
        #.\CMS Pages==============================
        return $pages;
    }

    public static function pages_splitWordDescription($term) {
        #CMS Pages==============================
        $terms = str_word_count($term, 1);
        $cmsPagesModuleObj = Modules::where('varModuleName', 'pages')->first();
        $ignoreId = [0];
        $pages = CmsPage::select(
                        'cms_page.intSearchRank', 'cms_page.varTitle as term', 'cms_page.txtDescription as info', //item has desc
                        'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'cms_page.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $cmsPagesModuleObj->id)
                ->where('cms_page.chrPublish', '=', 'Y')
                ->where('cms_page.chrMain', 'Y')
                ->where('cms_page.chrDelete', '=', 'N')
                ->where('cms_page.chrIsPreview', 'N')
                ->whereNotIn('cms_page.id', $ignoreId);
        $rawstring = '(nq_cms_page.txtDescription like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_cms_page.txtDescription like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $pages = $pages->whereRaw($rawstring);
        //->whereRaw('(nq_cms_page.varTitle like "%'.$term.'%" or nq_cms_page.txtDescription like "%'.$term.'%")')
        $pages = $pages->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
//        $pages = $pages->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))')->orderBy('cms_page.intSearchRank', 'ASC');
        #.\CMS Pages==============================
        return $pages;
    }

//==============================================================================================

    public static function careers($term) {
        #careers==============================
        $terms = str_word_count($term, 1);
        $careersModuleObj = Modules::where('varModuleName', 'careers')->first();
        $careers = DB::table('careers')
                ->select(
                        'careers.intSearchRank', 'careers.varTitle as term', 'careers.varShortDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'careers.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'careers.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $careersModuleObj->id)
                ->where('careers.chrPublish', '=', 'Y')
                ->where('careers.chrDelete', '=', 'N')
                ->where('careers.chrMain', 'Y')
                ->where('careers.chrIsPreview', 'N');
        $rawstring = '(nq_careers.varTitle = "' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $careers = $careers->whereRaw($rawstring);
        $careers = $careers->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $careers;
        #.\careers==============================
    }

    public static function careers_liketitle($term) {
        #careers==============================
        $terms = str_word_count($term, 1);
        $careersModuleObj = Modules::where('varModuleName', 'careers')->first();
        $careers = DB::table('careers')
                ->select(
                        'careers.intSearchRank', 'careers.varTitle as term', 'careers.varShortDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'careers.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'careers.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $careersModuleObj->id)
                ->where('careers.chrPublish', '=', 'Y')
                ->where('careers.chrDelete', '=', 'N')
                ->where('careers.chrMain', 'Y')
                ->where('careers.chrIsPreview', 'N');
        $rawstring = '(nq_careers.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $careers = $careers->whereRaw($rawstring);
        $careers = $careers->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $careers;
        #.\careers==============================
    }

    public static function careers_splitWordTitle($term) {
        #careers==============================
        $terms = str_word_count($term, 1);
        $careersModuleObj = Modules::where('varModuleName', 'careers')->first();
        $careers = DB::table('careers')
                ->select(
                        'careers.intSearchRank', 'careers.varTitle as term', 'careers.varShortDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'careers.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'careers.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $careersModuleObj->id)
                ->where('careers.chrPublish', '=', 'Y')
                ->where('careers.chrDelete', '=', 'N')
                ->where('careers.chrMain', 'Y')
                ->where('careers.chrIsPreview', 'N');
        $rawstring = '(nq_careers.varTitle like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_careers.varTitle like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $careers = $careers->whereRaw($rawstring);
        $careers = $careers->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $careers;
        #.\careers==============================
    }

    public static function careers_splitWordDescription($term) {
        #careers==============================
        $terms = str_word_count($term, 1);
        $careersModuleObj = Modules::where('varModuleName', 'careers')->first();
        $careers = DB::table('careers')
                ->select(
                        'careers.intSearchRank', 'careers.varTitle as term', 'careers.varShortDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'careers.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'careers.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $careersModuleObj->id)
                ->where('careers.chrPublish', '=', 'Y')
                ->where('careers.chrDelete', '=', 'N')
                ->where('careers.chrMain', 'Y')
                ->where('careers.chrIsPreview', 'N');
        $rawstring = '(nq_careers.varShortDescription like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_careers.varShortDescription like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $careers = $careers->whereRaw($rawstring);
        $careers = $careers->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');

        return $careers;
        #.\careers==============================
    }

//=======================================================================================
    public static function faqCategory($term) {
        #faq category==============================
        $terms = str_word_count($term, 1);
        $faqCategoryModuleObj = Modules::where('varModuleName', 'faq-category')->first();
        $faqCategories = DB::table('faq_category')
                ->select(
                        'faq_category.intSearchRank', 'faq_category.varTitle as term', 'faq_category.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'faq_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'faq_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $faqCategoryModuleObj->id)
                ->where('faq_category.chrPublish', '=', 'Y')
                ->where('faq_category.chrDelete', '=', 'N')
                ->where('faq_category.chrMain', 'Y')
                ->where('faq_category.chrIsPreview', 'N');
        $rawstring = '(nq_faq_category.varTitle = "' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $faqCategories = $faqCategories->whereRaw($rawstring);
        $faqCategories = $faqCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqCategories;
        #.\faq category==============================
    }

    public static function faqCategory_liketitle($term) {
        #faq category==============================
        $terms = str_word_count($term, 1);
        $faqCategoryModuleObj = Modules::where('varModuleName', 'faq-category')->first();
        $faqCategories = DB::table('faq_category')
                ->select(
                        'faq_category.intSearchRank', 'faq_category.varTitle as term', 'faq_category.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'faq_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'faq_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $faqCategoryModuleObj->id)
                ->where('faq_category.chrPublish', '=', 'Y')
                ->where('faq_category.chrDelete', '=', 'N')
                ->where('faq_category.chrMain', 'Y')
                ->where('faq_category.chrIsPreview', 'N');
        $rawstring = '(nq_faq_category.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $faqCategories = $faqCategories->whereRaw($rawstring);
        $faqCategories = $faqCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqCategories;
        #.\faq category==============================
    }

    public static function faqCategory_splitWordTitle($term) {
        #faq category==============================
        $terms = str_word_count($term, 1);
        $faqCategoryModuleObj = Modules::where('varModuleName', 'faq-category')->first();
        $faqCategories = DB::table('faq_category')
                ->select(
                        'faq_category.intSearchRank', 'faq_category.varTitle as term', 'faq_category.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'faq_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'faq_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $faqCategoryModuleObj->id)
                ->where('faq_category.chrPublish', '=', 'Y')
                ->where('faq_category.chrDelete', '=', 'N')
                ->where('faq_category.chrMain', 'Y')
                ->where('faq_category.chrIsPreview', 'N');
        $rawstring = '(nq_faq_category.varTitle like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_faq_category.varTitle like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $faqCategories = $faqCategories->whereRaw($rawstring);
        $faqCategories = $faqCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqCategories;
        #.\faq category==============================
    }

    public static function faqCategory_splitWordDescription($term) {
        #faq category==============================
        $terms = str_word_count($term, 1);
        $faqCategoryModuleObj = Modules::where('varModuleName', 'faq-category')->first();
        $faqCategories = DB::table('faq_category')
                ->select(
                        'faq_category.intSearchRank', 'faq_category.varTitle as term', 'faq_category.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'faq_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'faq_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $faqCategoryModuleObj->id)
                ->where('faq_category.chrPublish', '=', 'Y')
                ->where('faq_category.chrDelete', '=', 'N')
                ->where('faq_category.chrMain', 'Y')
                ->where('faq_category.chrIsPreview', 'N');
        $rawstring = '(nq_faq_category.txtDescription like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_faq_category.txtDescription like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $faqCategories = $faqCategories->whereRaw($rawstring);
        $faqCategories = $faqCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqCategories;
        #.\faq category==============================
    }

//============================================================================================
    public static function faqs($term) {
        #faq category==============================
        $terms = str_word_count($term, 1);
        $faqsModuleObj = Modules::where('varModuleName', 'faq-category')->first();
        $faqs = DB::table('faq')
                ->select(
                        'faq.intSearchRank', 'faq.varTitle as term', 'faq.txtDescription as info', DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $faqsModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $faqsModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $faqsModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw(' "FAQs" as moduleTitle'), 'faq.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), 'faq.intFKCategory'
                )
                ->where('faq.chrPublish', '=', 'Y')
                ->where('faq.chrDelete', '=', 'N')
                ->where('faq.chrMain', 'Y');
        $rawstring = '(nq_faq.varTitle = "' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $faqs = $faqs->whereRaw($rawstring);
        $faqs = $faqs->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqs;
        #.\faq category==============================
    }

    public static function faqs_liketitle($term) {
        #faq category==============================
        $terms = str_word_count($term, 1);
        $faqsModuleObj = Modules::where('varModuleName', 'faq-category')->first();
        $faqs = DB::table('faq')
                ->select(
                        'faq.intSearchRank', 'faq.varTitle as term', 'faq.txtDescription as info', DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $faqsModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $faqsModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $faqsModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw(' "FAQs" as moduleTitle'), 'faq.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), 'faq.intFKCategory'
                )
                ->where('faq.chrPublish', '=', 'Y')
                ->where('faq.chrDelete', '=', 'N')
                ->where('faq.chrMain', 'Y');
        $rawstring = '(nq_faq.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $faqs = $faqs->whereRaw($rawstring);
        $faqs = $faqs->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqs;
        #.\faq category==============================
    }

    public static function faqs_splitWordTitle($term) {
        #faq category==============================
        $terms = str_word_count($term, 1);
        $faqsModuleObj = Modules::where('varModuleName', 'faq-category')->first();
        $faqs = DB::table('faq')
                ->select(
                        'faq.intSearchRank', 'faq.varTitle as term', 'faq.txtDescription as info', DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $faqsModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $faqsModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $faqsModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw(' "FAQs" as moduleTitle'), 'faq.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), 'faq.intFKCategory'
                )
                ->where('faq.chrPublish', '=', 'Y')
                ->where('faq.chrDelete', '=', 'N')
                ->where('faq.chrMain', 'Y');
        $rawstring = '(nq_faq.varTitle like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_faq.varTitle like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $faqs = $faqs->whereRaw($rawstring);
        $faqs = $faqs->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqs;
        #.\faq category==============================
    }

    public static function faqs_splitWordDescription($term) {
        #faq category==============================
        $terms = str_word_count($term, 1);
        $faqsModuleObj = Modules::where('varModuleName', 'faq-category')->first();
        $faqs = DB::table('faq')
                ->select(
                        'faq.intSearchRank', 'faq.varTitle as term', 'faq.txtDescription as info', DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $faqsModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $faqsModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $faqsModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw(' "FAQs" as moduleTitle'), 'faq.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), 'faq.intFKCategory'
                )
                ->where('faq.chrPublish', '=', 'Y')
                ->where('faq.chrDelete', '=', 'N')
                ->where('faq.chrMain', 'Y');
        $rawstring = '(nq_faq.txtDescription like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_faq.txtDescription like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $faqs = $faqs->whereRaw($rawstring);
        $faqs = $faqs->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqs;
        #.\faq category==============================
    }

//==============================================================================================
    public static function videoGallery($term) {
        #videoGallery==============================
        $terms = str_word_count($term, 1);
        $videoGalleryModuleObj = Modules::where('varModuleName', 'video-gallery')->first();

        $videoGallery = DB::table('video_gallery')
                ->select(
                        'video_gallery.intSearchRank', 'video_gallery.varTitle as term', DB::raw('null as info'), DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $videoGalleryModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $videoGalleryModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $videoGalleryModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw("'" . $videoGalleryModuleObj->varTitle . "'" . ' as moduleTitle'), 'video_gallery.id', DB::raw(' "na" as fkIntDocId'), 'video_gallery.fkIntImgId', DB::raw(' "na" as intFKCategory')
                )
                ->where('video_gallery.chrPublish', '=', 'Y')
                ->where('video_gallery.chrDelete', '=', 'N')
                ->where('video_gallery.chrMain', 'Y');
        $rawstring = '(nq_video_gallery.varTitle="' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $videoGallery = $videoGallery->whereRaw($rawstring);
        $videoGallery = $videoGallery->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');

        #.\videoGallery==============================
        return $videoGallery;
    }

    public static function videoGallery_liketitle($term) {
        #videoGallery==============================
        $terms = str_word_count($term, 1);
        $videoGalleryModuleObj = Modules::where('varModuleName', 'video-gallery')->first();

        $videoGallery = DB::table('video_gallery')
                ->select(
                        'video_gallery.intSearchRank', 'video_gallery.varTitle as term', DB::raw('null as info'), DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $videoGalleryModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $videoGalleryModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $videoGalleryModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw("'" . $videoGalleryModuleObj->varTitle . "'" . ' as moduleTitle'), 'video_gallery.id', DB::raw(' "na" as fkIntDocId'), 'video_gallery.fkIntImgId', DB::raw(' "na" as intFKCategory')
                )
                ->where('video_gallery.chrPublish', '=', 'Y')
                ->where('video_gallery.chrDelete', '=', 'N')
                ->where('video_gallery.chrMain', 'Y');
        $rawstring = '(nq_video_gallery.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $videoGallery = $videoGallery->whereRaw($rawstring);
        $videoGallery = $videoGallery->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');

        #.\videoGallery==============================
        return $videoGallery;
    }

    public static function videoGallery_splitWordTitle($term) {
        #videoGallery==============================
        $terms = str_word_count($term, 1);
        $videoGalleryModuleObj = Modules::where('varModuleName', 'video-gallery')->first();

        $videoGallery = DB::table('video_gallery')
                ->select(
                        'video_gallery.intSearchRank', 'video_gallery.varTitle as term', DB::raw('null as info'), DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $videoGalleryModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $videoGalleryModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $videoGalleryModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw("'" . $videoGalleryModuleObj->varTitle . "'" . ' as moduleTitle'), 'video_gallery.id', DB::raw(' "na" as fkIntDocId'), 'video_gallery.fkIntImgId', DB::raw(' "na" as intFKCategory')
                )
                ->where('video_gallery.chrPublish', '=', 'Y')
                ->where('video_gallery.chrDelete', '=', 'N')
                ->where('video_gallery.chrMain', 'Y');
        $rawstring = '(nq_video_gallery.varTitle like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_video_gallery.varTitle like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $videoGallery = $videoGallery->whereRaw($rawstring);
        $videoGallery = $videoGallery->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');

        #.\videoGallery==============================
        return $videoGallery;
    }

//==============================================================================================

    public static function newsCategory($term) {
        #news category==============================
        $terms = str_word_count($term, 1);
        $newsCategoryModuleObj = Modules::where('varModuleName', 'news-category')->first();
        $newsCategories = DB::table('news_category')
                ->select(
                        'news_category.intSearchRank', 'news_category.varTitle as term', DB::raw('null as info'), 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'news_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'news_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $newsCategoryModuleObj->id)
                ->where('news_category.chrPublish', '=', 'Y')
                ->where('news_category.chrDelete', '=', 'N')
                ->where('news_category.chrMain', 'Y')
                ->where('news_category.chrIsPreview', 'N');
        $rawstring = '(nq_news_category.varTitle = "' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $newsCategories = $newsCategories->whereRaw($rawstring);
        $newsCategories = $newsCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $newsCategories;
        #.\news category==============================
    }

    public static function newsCategory_liketitle($term) {
        #news category==============================
        $terms = str_word_count($term, 1);
        $newsCategoryModuleObj = Modules::where('varModuleName', 'news-category')->first();
        $newsCategories = DB::table('news_category')
                ->select(
                        'news_category.intSearchRank', 'news_category.varTitle as term', DB::raw('null as info'), 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'news_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'news_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $newsCategoryModuleObj->id)
                ->where('news_category.chrPublish', '=', 'Y')
                ->where('news_category.chrDelete', '=', 'N')
                ->where('news_category.chrMain', 'Y')
                ->where('news_category.chrIsPreview', 'N');
        $rawstring = '(nq_news_category.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $newsCategories = $newsCategories->whereRaw($rawstring);
        $newsCategories = $newsCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $newsCategories;
        #.\news category==============================
    }

    public static function newsCategory_splitWordTitle($term) {
        #news category==============================
        $terms = str_word_count($term, 1);
        $newsCategoryModuleObj = Modules::where('varModuleName', 'news-category')->first();
        $newsCategories = DB::table('news_category')
                ->select(
                        'news_category.intSearchRank', 'news_category.varTitle as term', DB::raw('null as info'), 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'news_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'news_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $newsCategoryModuleObj->id)
                ->where('news_category.chrPublish', '=', 'Y')
                ->where('news_category.chrDelete', '=', 'N')
                ->where('news_category.chrMain', 'Y')
                ->where('news_category.chrIsPreview', 'N');
        $rawstring = '(nq_news_category.varTitle like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_news_category.varTitle like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $newsCategories = $newsCategories->whereRaw($rawstring);
        $newsCategories = $newsCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $newsCategories;
        #.\news category==============================
    }

//==============================================================================================

    public static function eventCategory($term) {
        #event category==============================
        $terms = str_word_count($term, 1);
        $eventCategoryModuleObj = Modules::where('varModuleName', 'event-category')->first();
        $eventCategories = DB::table('event_category')
                ->select(
                        'event_category.intSearchRank', 'event_category.varTitle as term', 'event_category.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'event_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'event_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $eventCategoryModuleObj->id)
                ->where('event_category.chrPublish', '=', 'Y')
                ->where('event_category.chrDelete', '=', 'N')
                ->where('event_category.chrMain', 'Y')
                ->where('event_category.chrIsPreview', 'N');
        $rawstring = '(nq_event_category.varTitle = "' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $eventCategories = $eventCategories->whereRaw($rawstring);
        $eventCategories = $eventCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $eventCategories;
        #.\event category==============================
    }

    public static function eventCategory_liketitle($term) {
        #event category==============================
        $terms = str_word_count($term, 1);
        $eventCategoryModuleObj = Modules::where('varModuleName', 'event-category')->first();
        $eventCategories = DB::table('event_category')
                ->select(
                        'event_category.intSearchRank', 'event_category.varTitle as term', 'event_category.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'event_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'event_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $eventCategoryModuleObj->id)
                ->where('event_category.chrPublish', '=', 'Y')
                ->where('event_category.chrDelete', '=', 'N')
                ->where('event_category.chrMain', 'Y')
                ->where('event_category.chrIsPreview', 'N');
        $rawstring = '(nq_event_category.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $eventCategories = $eventCategories->whereRaw($rawstring);
        $eventCategories = $eventCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $eventCategories;
        #.\event category==============================
    }

    public static function eventCategory_splitWordTitle($term) {
        #event category==============================
        $terms = str_word_count($term, 1);
        $eventCategoryModuleObj = Modules::where('varModuleName', 'event-category')->first();
        $eventCategories = DB::table('event_category')
                ->select(
                        'event_category.intSearchRank', 'event_category.varTitle as term', 'event_category.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'event_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'event_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $eventCategoryModuleObj->id)
                ->where('event_category.chrPublish', '=', 'Y')
                ->where('event_category.chrDelete', '=', 'N')
                ->where('event_category.chrMain', 'Y')
                ->where('event_category.chrIsPreview', 'N');
        $rawstring = '(nq_event_category.varTitle like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_event_category.varTitle like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $eventCategories = $eventCategories->whereRaw($rawstring);
        $eventCategories = $eventCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $eventCategories;
        #.\event category==============================
    }

    public static function eventCategory_splitWordDescription($term) {
        #event category==============================
        $terms = str_word_count($term, 1);
        $eventCategoryModuleObj = Modules::where('varModuleName', 'event-category')->first();
        $eventCategories = DB::table('event_category')
                ->select(
                        'event_category.intSearchRank', 'event_category.varTitle as term', 'event_category.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'event_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'event_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $eventCategoryModuleObj->id)
                ->where('event_category.chrPublish', '=', 'Y')
                ->where('event_category.chrDelete', '=', 'N')
                ->where('event_category.chrMain', 'Y')
                ->where('event_category.chrIsPreview', 'N');
        $rawstring = '(nq_event_category.txtDescription like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_event_category.txtDescription like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $eventCategories = $eventCategories->whereRaw($rawstring);
        $eventCategories = $eventCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $eventCategories;
        #.\event category==============================
    }

//==============================================================================================

    public static function events($term) {
        #bank super vision==============================
        $terms = str_word_count($term, 1);
        $eventsModuleObj = Modules::where('varModuleName', 'events')->first();
        $eventsObj = DB::table('events')
                ->select(
                        'events.intSearchRank', 'events.varTitle as term', 'events.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'events.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'events.intFKCategory as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'events.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $eventsModuleObj->id)
                ->where('events.chrPublish', '=', 'Y')
                ->where('events.chrDelete', '=', 'N')
                ->where('events.chrMain', 'Y')
                ->where('events.chrIsPreview', 'N');
        $rawstring = '(nq_events.varTitle = "' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $eventsObj = $eventsObj->whereRaw($rawstring);
        $eventsObj = $eventsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $eventsObj;
        #.\bank suprevision==============================
    }

    public static function events_liketitle($term) {
        #bank super vision==============================
        $terms = str_word_count($term, 1);
        $eventsModuleObj = Modules::where('varModuleName', 'events')->first();
        $eventsObj = DB::table('events')
                ->select(
                        'events.intSearchRank', 'events.varTitle as term', 'events.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'events.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'events.intFKCategory as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'events.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $eventsModuleObj->id)
                ->where('events.chrPublish', '=', 'Y')
                ->where('events.chrDelete', '=', 'N')
                ->where('events.chrMain', 'Y')
                ->where('events.chrIsPreview', 'N');
        $rawstring = '(nq_events.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $eventsObj = $eventsObj->whereRaw($rawstring);
        $eventsObj = $eventsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $eventsObj;
        #.\bank suprevision==============================
    }

    public static function events_splitWordTitle($term) {
        #bank super vision==============================
        $terms = str_word_count($term, 1);
        $eventsModuleObj = Modules::where('varModuleName', 'events')->first();
        $eventsObj = DB::table('events')
                ->select(
                        'events.intSearchRank', 'events.varTitle as term', 'events.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'events.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'events.intFKCategory as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'events.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $eventsModuleObj->id)
                ->where('events.chrPublish', '=', 'Y')
                ->where('events.chrDelete', '=', 'N')
                ->where('events.chrMain', 'Y')
                ->where('events.chrIsPreview', 'N');
        $rawstring = '(nq_events.varTitle like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_events.varTitle like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $eventsObj = $eventsObj->whereRaw($rawstring);
        $eventsObj = $eventsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $eventsObj;
        #.\bank suprevision==============================
    }

    public static function events_splitWordDescription($term) {
        #bank super vision==============================
        $terms = str_word_count($term, 1);
        $eventsModuleObj = Modules::where('varModuleName', 'events')->first();
        $eventsObj = DB::table('events')
                ->select(
                        'events.intSearchRank', 'events.varTitle as term', 'events.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'events.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'events.intFKCategory as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'events.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $eventsModuleObj->id)
                ->where('events.chrPublish', '=', 'Y')
                ->where('events.chrDelete', '=', 'N')
                ->where('events.chrMain', 'Y')
                ->where('events.chrIsPreview', 'N');
        $rawstring = '(nq_events.txtDescription like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_events.txtDescription like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $eventsObj = $eventsObj->whereRaw($rawstring);
        $eventsObj = $eventsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $eventsObj;
        #.\bank suprevision==============================
    }

//==============================================================================================

    public static function publicationsCategories($term) {
        #publicationsCategories ==============================
        $terms = str_word_count($term, 1);
        $publicationsCategoryModuleObj = Modules::where('varModuleName', 'publications-category')->first();
        $publicationsCategoriesListing = DB::table('publications_category')
                ->select(
                        'publications_category.intSearchRank', 'publications_category.varTitle as term', DB::raw('null as info'), 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'publications_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'publications_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $publicationsCategoryModuleObj->id)
                ->where('publications_category.chrPublish', '=', 'Y')
                ->where('publications_category.chrDelete', '=', 'N')
                ->where('publications_category.chrMain', 'Y')
                ->where('publications_category.chrIsPreview', 'N');
        $rawstring = '(nq_publications_category.varTitle="' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $publicationsCategoriesListing = $publicationsCategoriesListing->whereRaw($rawstring);

        $publicationsCategoriesListing = $publicationsCategoriesListing->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $publicationsCategoriesListing;
        #.\publicationsCategories==============================
    }

    public static function publicationsCategories_liketitle($term) {
        #publicationsCategories ==============================
        $terms = str_word_count($term, 1);
        $publicationsCategoryModuleObj = Modules::where('varModuleName', 'publications-category')->first();
        $publicationsCategoriesListing = DB::table('publications_category')
                ->select(
                        'publications_category.intSearchRank', 'publications_category.varTitle as term', DB::raw('null as info'), 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'publications_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'publications_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $publicationsCategoryModuleObj->id)
                ->where('publications_category.chrPublish', '=', 'Y')
                ->where('publications_category.chrDelete', '=', 'N')
                ->where('publications_category.chrMain', 'Y')
                ->where('publications_category.chrIsPreview', 'N');
        $rawstring = '(nq_publications_category.varTitle like "%' . self::cleanString($term) . '%" ';
        $rawstring .= ')';
        $publicationsCategoriesListing = $publicationsCategoriesListing->whereRaw($rawstring);
        //->whereRaw('(nq_publications_category.varTitle like "%' . $term . '%" )')
        $publicationsCategoriesListing = $publicationsCategoriesListing->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $publicationsCategoriesListing;
        #.\publicationsCategories==============================
    }

    public static function publicationsCategories_splitWordTitle($term) {
        #publicationsCategories ==============================
        $terms = str_word_count($term, 1);
        $publicationsCategoryModuleObj = Modules::where('varModuleName', 'publications-category')->first();
        $publicationsCategoriesListing = DB::table('publications_category')
                ->select(
                        'publications_category.intSearchRank', 'publications_category.varTitle as term', DB::raw('null as info'), 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'publications_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'publications_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $publicationsCategoryModuleObj->id)
                ->where('publications_category.chrPublish', '=', 'Y')
                ->where('publications_category.chrDelete', '=', 'N')
                ->where('publications_category.chrMain', 'Y')
                ->where('publications_category.chrIsPreview', 'N');
        $rawstring = '(nq_publications_category.varTitle like "%' . self::cleanString($term) . '%" ';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_publications_category.varTitle like "%' . self::cleanString($term) . '%" ';
                }
            }
        }
        $rawstring .= ')';
        $publicationsCategoriesListing = $publicationsCategoriesListing->whereRaw($rawstring);
        //->whereRaw('(nq_publications_category.varTitle like "%' . $term . '%" )')
        $publicationsCategoriesListing = $publicationsCategoriesListing->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $publicationsCategoriesListing;
        #.\publicationsCategories==============================
    }

//==============================================================================================
    public static function publications($term) {
        #publications==============================
        $terms = str_word_count($term, 1);
        $publicationsModuleObj = Modules::where('varModuleName', 'publications')->first();
        $publicationsObj = DB::table('publications')
                ->select(
                        'publications.intSearchRank', 'publications.varTitle as term', 'publications.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'publications.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'publications.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'publications.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $publicationsModuleObj->id)
                ->where('publications.chrPublish', '=', 'Y')
                ->where('publications.chrDelete', '=', 'N')
                ->where('publications.chrMain', 'Y')
                ->where('publications.chrIsPreview', 'N');
        $rawstring = '(nq_publications.varTitle = "' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $publicationsObj = $publicationsObj->whereRaw($rawstring);
        $publicationsObj = $publicationsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $publicationsObj;
        #.\publications==============================
    }

    public static function publications_liketitle($term) {
        #publications==============================
        $terms = str_word_count($term, 1);
        $publicationsModuleObj = Modules::where('varModuleName', 'publications')->first();
        $publicationsObj = DB::table('publications')
                ->select(
                        'publications.intSearchRank', 'publications.varTitle as term', 'publications.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'publications.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'publications.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'publications.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $publicationsModuleObj->id)
                ->where('publications.chrPublish', '=', 'Y')
                ->where('publications.chrDelete', '=', 'N')
                ->where('publications.chrMain', 'Y')
                ->where('publications.chrIsPreview', 'N');
        $rawstring = '(nq_publications.varTitle like "%' . self::cleanString($term) . '%" ';
        $rawstring .= ')';
        $publicationsObj = $publicationsObj->whereRaw($rawstring);
        $publicationsObj = $publicationsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $publicationsObj;
        #.\publications==============================
    }

    public static function publications_splitWordTitle($term) {
        #publications==============================
        $terms = str_word_count($term, 1);
        $publicationsModuleObj = Modules::where('varModuleName', 'publications')->first();
        $publicationsObj = DB::table('publications')
                ->select(
                        'publications.intSearchRank', 'publications.varTitle as term', 'publications.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'publications.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'publications.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'publications.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $publicationsModuleObj->id)
                ->where('publications.chrPublish', '=', 'Y')
                ->where('publications.chrDelete', '=', 'N')
                ->where('publications.chrMain', 'Y')
                ->where('publications.chrIsPreview', 'N');
        $rawstring = '(nq_publications.varTitle like "%' . self::cleanString($term) . '%" ';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_publications.varTitle like "%' . self::cleanString($term) . '%" ';
                }
            }
        }
        $rawstring .= ')';
        $publicationsObj = $publicationsObj->whereRaw($rawstring);
        $publicationsObj = $publicationsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $publicationsObj;
        #.\publications==============================
    }

    public static function publications_splitWordDescription($term) {
        #publications==============================
        $terms = str_word_count($term, 1);
        $publicationsModuleObj = Modules::where('varModuleName', 'publications')->first();
        $publicationsObj = DB::table('publications')
                ->select(
                        'publications.intSearchRank', 'publications.varTitle as term', 'publications.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'publications.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'publications.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'publications.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $publicationsModuleObj->id)
                ->where('publications.chrPublish', '=', 'Y')
                ->where('publications.chrDelete', '=', 'N')
                ->where('publications.chrMain', 'Y')
                ->where('publications.chrIsPreview', 'N');
        $rawstring = '(nq_publications.txtDescription like "%' . self::cleanString($term) . '%" ';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_publications.txtDescription like "%' . self::cleanString($term) . '%" ';
                }
            }
        }
        $rawstring .= ')';
        $publicationsObj = $publicationsObj->whereRaw($rawstring);
        $publicationsObj = $publicationsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $publicationsObj;
        #.\publications==============================
    }

//==============================================================================================
    public static function photoAlbums($term) {
        #photoAlbums==============================
        $terms = str_word_count($term, 1);
        $photoAlbumsModuleObj = Modules::where('varModuleName', 'photo-album')->first();
        $photoAlbumsObj = DB::table('photo_album')
                ->select(
                        'photo_album.intSearchRank', 'photo_album.varTitle as term', 'photo_album.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'photo_album.id', DB::raw(' "na" as fkIntDocId'), 'photo_album.fkIntImgId', DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'photo_album.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $photoAlbumsModuleObj->id)
                ->where('photo_album.chrPublish', '=', 'Y')
                ->where('photo_album.chrDelete', '=', 'N')
                ->where('photo_album.chrMain', 'Y')
                ->where('photo_album.chrIsPreview', 'N');
        $rawstring = '(nq_photo_album.varTitle = "' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $photoAlbumsObj = $photoAlbumsObj->whereRaw($rawstring);
        $photoAlbumsObj = $photoAlbumsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $photoAlbumsObj;
        #.\photoAlbums==============================
    }

    public static function photoAlbums_liketitle($term) {
        #photoAlbums==============================
        $terms = str_word_count($term, 1);
        $photoAlbumsModuleObj = Modules::where('varModuleName', 'photo-album')->first();
        $photoAlbumsObj = DB::table('photo_album')
                ->select(
                        'photo_album.intSearchRank', 'photo_album.varTitle as term', 'photo_album.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'photo_album.id', DB::raw(' "na" as fkIntDocId'), 'photo_album.fkIntImgId', DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'photo_album.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $photoAlbumsModuleObj->id)
                ->where('photo_album.chrPublish', '=', 'Y')
                ->where('photo_album.chrDelete', '=', 'N')
                ->where('photo_album.chrMain', 'Y')
                ->where('photo_album.chrIsPreview', 'N');
        $rawstring = '(nq_photo_album.varTitle like "%' . self::cleanString($term) . '%" ';
        $rawstring .= ')';
        $photoAlbumsObj = $photoAlbumsObj->whereRaw($rawstring);
        $photoAlbumsObj = $photoAlbumsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $photoAlbumsObj;
        #.\photoAlbums==============================
    }

    public static function photoAlbums_splitWordTitle($term) {
        #photoAlbums==============================
        $terms = str_word_count($term, 1);
        $photoAlbumsModuleObj = Modules::where('varModuleName', 'photo-album')->first();
        $photoAlbumsObj = DB::table('photo_album')
                ->select(
                        'photo_album.intSearchRank', 'photo_album.varTitle as term', 'photo_album.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'photo_album.id', DB::raw(' "na" as fkIntDocId'), 'photo_album.fkIntImgId', DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'photo_album.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $photoAlbumsModuleObj->id)
                ->where('photo_album.chrPublish', '=', 'Y')
                ->where('photo_album.chrDelete', '=', 'N')
                ->where('photo_album.chrMain', 'Y')
                ->where('photo_album.chrIsPreview', 'N');
        $rawstring = '(nq_photo_album.varTitle like "%' . self::cleanString($term) . '%" ';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_photo_album.varTitle like "%' . self::cleanString($term) . '%" ';
                }
            }
        }
        $rawstring .= ')';
        $photoAlbumsObj = $photoAlbumsObj->whereRaw($rawstring);
        $photoAlbumsObj = $photoAlbumsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $photoAlbumsObj;
        #.\photoAlbums==============================
    }

    public static function photoAlbums_splitWordDescription($term) {
        #photoAlbums==============================
        $terms = str_word_count($term, 1);
        $photoAlbumsModuleObj = Modules::where('varModuleName', 'photo-album')->first();
        $photoAlbumsObj = DB::table('photo_album')
                ->select(
                        'photo_album.intSearchRank', 'photo_album.varTitle as term', 'photo_album.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'photo_album.id', DB::raw(' "na" as fkIntDocId'), 'photo_album.fkIntImgId', DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'photo_album.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $photoAlbumsModuleObj->id)
                ->where('photo_album.chrPublish', '=', 'Y')
                ->where('photo_album.chrDelete', '=', 'N')
                ->where('photo_album.chrMain', 'Y')
                ->where('photo_album.chrIsPreview', 'N');
        $rawstring = '(nq_photo_album.txtDescription like "%' . self::cleanString($term) . '%" ';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_photo_album.txtDescription like "%' . self::cleanString($term) . '%" ';
                }
            }
        }
        $rawstring .= ')';
        $photoAlbumsObj = $photoAlbumsObj->whereRaw($rawstring);
        $photoAlbumsObj = $photoAlbumsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $photoAlbumsObj;
        #.\photoAlbums==============================
    }

    public static function contactusleads($term) {
        $terms = str_word_count($term, 1);
        $contactusModuleObj = Modules::where('varModuleName', 'contact-us')->first();
        $contact_lead = DB::table('contact_lead')
                ->select(
                        DB::raw('null as intSearchRank'), 'contact_lead.varName as term', DB::raw('null as info'), DB::raw(' null as slug'), DB::raw('null as pageAliasId'), DB::raw("'" . $contactusModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $contactusModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $contactusModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw('"Contact Us Lead" as moduleTitle'), 'contact_lead.id', DB::raw(' null as fkIntDocId'), DB::raw(' null as fkIntImgId'), DB::raw(' null as intFKCategory')
                )
                ->where('contact_lead.chrPublish', '=', 'Y')
                ->where('contact_lead.chrDelete', '=', 'N');
        $rawstring = '(nq_contact_lead.varName like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $contact_lead = $contact_lead->whereRaw($rawstring);

        return $contact_lead;
    }

    public static function feedbackleads($term) {
        $terms = str_word_count($term, 1);
        $feedbackModuleObj = Modules::where('varModuleName', 'feedback-leads')->first();
        $feedback_leads = DB::table('feedback_leads')
                ->select(
                        DB::raw('null as intSearchRank'), 'feedback_leads.varName as term', DB::raw('null as info'), DB::raw(' null as slug'), DB::raw('null as pageAliasId'), DB::raw("'" . $feedbackModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $feedbackModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $feedbackModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw('"Feedback Lead" as moduleTitle'), 'feedback_leads.id', DB::raw(' null as fkIntDocId'), DB::raw(' null as fkIntImgId'), DB::raw(' null as intFKCategory')
                )
                ->where('feedback_leads.chrPublish', '=', 'Y')
                ->where('feedback_leads.chrDelete', '=', 'N');
        $rawstring = '(nq_feedback_leads.varName like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $feedback_leads = $feedback_leads->whereRaw($rawstring);

        return $feedback_leads;
    }

    public static function formbuilderleads($term) {
        $terms = str_word_count($term, 1);
        $formbuilderleadsModuleObj = Modules::where('varModuleName', 'formbuilder-lead')->first();
        $formbuilderleadsObj = DB::table('formbuilder_lead')
                ->select(
                        DB::raw('null as intSearchRank'), 'form_builder.varName as term', DB::raw('null as info'), DB::raw(' null as slug'), DB::raw('null as pageAliasId'), DB::raw("'" . $formbuilderleadsModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $formbuilderleadsModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $formbuilderleadsModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw('"Form Builder Lead" as moduleTitle'), 'formbuilder_lead.id', DB::raw(' null as fkIntDocId'), DB::raw(' null as fkIntImgId'), DB::raw(' null as intFKCategory')
                )
                ->leftJoin('form_builder', 'form_builder.id', '=', 'formbuilder_lead.fk_formbuilder_id')
                ->where('formbuilder_lead.chrDelete', '=', 'N');
        $rawstring = '(nq_form_builder.varName like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $formbuilderleadsObj = $formbuilderleadsObj->whereRaw($rawstring);
        return $formbuilderleadsObj;
    }

//==============================================================================================
    public static function department($term) {
        #Dapartment==============================
        $terms = str_word_count($term, 1);
        $departmentModuleObj = Modules::where('varModuleName', 'department')->first();

        $department = DB::table('department')
                ->select(
                        'department.intSearchRank', 'department.varTitle as term', DB::raw('null as info'), DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $departmentModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $departmentModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $departmentModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw('"Department" as moduleTitle'), 'department.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->where('department.chrPublish', '=', 'Y')
                ->where('department.chrDelete', '=', 'N')
                ->where('department.chrMain', 'Y');
        $rawstring = '(nq_department.varTitle="' . self::cleanString($term) . '"';
        $rawstring .= ')';
        $department = $department->whereRaw($rawstring);
        $department = $department->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');

        #.\Dapartment==============================
        return $department;
    }

    public static function department_liketitle($term) {
        #Dapartment==============================
        $terms = str_word_count($term, 1);
        $departmentModuleObj = Modules::where('varModuleName', 'department')->first();

        $department = DB::table('department')
                ->select(
                        'department.intSearchRank', 'department.varTitle as term', DB::raw('null as info'), DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $departmentModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $departmentModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $departmentModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw('"Department" as moduleTitle'), 'department.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->where('department.chrPublish', '=', 'Y')
                ->where('department.chrDelete', '=', 'N')
                ->where('department.chrMain', 'Y');
        $rawstring = '(nq_department.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $department = $department->whereRaw($rawstring);
        $department = $department->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');

        #.\Dapartment==============================
        return $department;
    }

    public static function department_splitWordTitle($term) {
        #Dapartment==============================
        $terms = str_word_count($term, 1);
        $departmentModuleObj = Modules::where('varModuleName', 'department')->first();

        $department = DB::table('department')
                ->select(
                        'department.intSearchRank', 'department.varTitle as term', DB::raw('null as info'), DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $departmentModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $departmentModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $departmentModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw('"Department" as moduleTitle'), 'department.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->where('department.chrPublish', '=', 'Y')
                ->where('department.chrDelete', '=', 'N')
                ->where('department.chrMain', 'Y');
        $rawstring = '(nq_department.varTitle like "%' . self::cleanString($term) . '%"';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $onlystring = trim(preg_replace('/[^A-Za-z0-9]/', ' ', $term));
                if (strlen($term) > 2 && !in_array(strtolower($term), self::$ignoreCommonWords) && $onlystring != "") {
                    $rawstring .= ' or nq_department.varTitle like "%' . self::cleanString($term) . '%"';
                }
            }
        }
        $rawstring .= ')';
        $department = $department->whereRaw($rawstring);
        $department = $department->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');

        #.\Dapartment==============================
        return $department;
    }

//==============================================================================================	
    public static function termSeach($term, $limit = 50, $recordCount = false, $pp = false) {
        $data = Request::all();
        if ($pp == 'P') {
            $limit = '';
            $offset = '';
            /* conatct us leads Group */
            $contactusleads = Self::contactusleads($term);
            /* feedback leads Group */
            $feedbackleads = Self::feedbackleads($term);
            /* form builder leads Group */
            $formbuilderleads = Self::formbuilderleads($term);
        } else {
            $data['page'] = (null !== (Request::post('page'))) ? Request::post('page') : 1;
            $limit = $limit;
            $offset = (isset($data['page']) && $data['page'] != "") ? (($data['page'] - 1) * $limit) : 0;
            /* conatct us leads Group */
            $contactusleads = '';
            /* feedback leads Group */
            $feedbackleads = '';
            /* form builder leads Group */
            $formbuilderleads = '';
        }

        /* pages Group */
        $pages = Self::pages($term);
        $pages_liketitle = self::pages_liketitle($term);
        $pages_splitWordTitle = self::pages_splitWordTitle($term);
        $pages_splitWordDescription = self::pages_splitWordDescription($term);
        /* pages Group */

        /* publication Group */
        $publications = Self::publications($term);
        $publications_liketitle = self::publications_liketitle($term);
        $publications_splitWordTitle = self::publications_splitWordTitle($term);
        $publications_splitWordDescription = self::publications_splitWordDescription($term);
        /* publication Group */

        /* publication category Group */
        $publicationsCategories = Self::publicationsCategories($term);
        $publicationsCategories_liketitle = self::publicationsCategories_liketitle($term);
        $publicationsCategories_splitWordTitle = self::publicationsCategories_splitWordTitle($term);
        /* publication category Group */

        /* news Group */
        $news = Self::news($term);
        $news_liketitle = self::news_liketitle($term);
        $news_splitWordTitle = self::news_splitWordTitle($term);
        $news_splitWordDescription = self::news_splitWordDescription($term);
        /* news Group */

        /* news category Group */
        $newsCategory = Self::newsCategory($term);
        $newsCategory_liketitle = self::newsCategory_liketitle($term);
        $newsCategory_splitWordTitle = self::newsCategory_splitWordTitle($term);
        /* news category */

        /* career group */
        $careers = Self::careers($term);
        $careers_liketitle = Self::careers_liketitle($term);
        $careers_splitWordTitle = Self::careers_splitWordTitle($term);
        $careers_splitWordDescription = Self::careers_splitWordDescription($term);
        /* career group */

        /* faqcategory group */
        $faqCategory = Self::faqCategory($term);
        $faqCategory_liketitle = Self::faqCategory_liketitle($term);
        $faqCategory_splitWordTitle = Self::faqCategory_splitWordTitle($term);
        $faqCategory_splitWordDescription = Self::faqCategory_splitWordDescription($term);
        /* faqcategory group */

        /* faq group */
        $faqs = Self::faqs($term);
        $faqs_liketitle = Self::faqs_liketitle($term);
        $faqs_splitWordTitle = Self::faqs_splitWordTitle($term);
        $faqs_splitWordDescription = Self::faqs_splitWordDescription($term);
        /* faq group */

        /* Video Gallery group */
        $videoGallery = Self::videoGallery($term);
        $videoGallery_liketitle = Self::videoGallery_liketitle($term);
        $videoGallery_splitWordTitle = Self::videoGallery_splitWordTitle($term);
        /* Video Gallery group */

        /* eventcategory group */
        $eventCategory = Self::eventCategory($term);
        $eventCategory_liketitle = Self::eventCategory_liketitle($term);
        $eventCategory_splitWordTitle = Self::eventCategory_splitWordTitle($term);
        $eventCategory_splitWordDescription = Self::eventCategory_splitWordDescription($term);
        /* eventcategory group */

        /* events group */
        $events = Self::events($term);
        $events_liketitle = Self::events_liketitle($term);
        $events_splitWordTitle = Self::events_splitWordTitle($term);
        $events_splitWordDescription = Self::events_splitWordDescription($term);
        /* events group */

        /* photo album Group */
        $photoAlbums = Self::photoAlbums($term);
        $photoAlbums_liketitle = self::photoAlbums_liketitle($term);
        $photoAlbums_splitWordTitle = self::photoAlbums_splitWordTitle($term);
        $photoAlbums_splitWordDescription = self::photoAlbums_splitWordDescription($term);
        /* photo album Group */

        /* Department */
        $department = Self::department($term);
        $department_liketitle = Self::department_liketitle($term);
        $department_splitWordTitle = Self::department_splitWordTitle($term);
        /* Department */


        $response = $pages
                ->union($publications)
                ->union($publicationsCategories)
                ->union($news)
                ->union($newsCategory)
                ->union($careers)
                ->union($faqCategory)
                ->union($faqs)
                ->union($videoGallery)
                ->union($photoAlbums)
                ->union($eventCategory)
                ->union($events)
                ->union($department);
        if ($pp == 'P') {
            $response = $response->union($contactusleads)
                    ->union($feedbackleads)
                    ->union($formbuilderleads);
        }

        //string like functions
        $response = $response->union($publications_liketitle)
                ->union($publicationsCategories_liketitle)
                ->union($news_liketitle)
                ->union($newsCategory_liketitle)
                ->union($careers_liketitle)
                ->union($faqCategory_liketitle)
                ->union($faqs_liketitle)
                ->union($videoGallery_liketitle)
                ->union($photoAlbums_liketitle)
                ->union($eventCategory_liketitle)
                ->union($events_liketitle)
                ->union($department_liketitle)

                //split wordtitle modules
                ->union($publications_splitWordTitle)
                ->union($publicationsCategories_splitWordTitle)
                ->union($news_splitWordTitle)
                ->union($newsCategory_splitWordTitle)
                ->union($careers_splitWordTitle)
                ->union($faqCategory_splitWordTitle)
                ->union($faqs_splitWordTitle)
                ->union($videoGallery_splitWordTitle)
                ->union($photoAlbums_splitWordTitle)
                ->union($eventCategory_splitWordTitle)
                ->union($events_splitWordTitle)
                ->union($department_splitWordTitle)

                //split worddescription modules
                ->union($pages_splitWordDescription)
                ->union($publications_splitWordDescription)
                ->union($news_splitWordDescription)
                ->union($careers_splitWordDescription)
                ->union($faqCategory_splitWordDescription)
                ->union($faqs_splitWordDescription)
                ->union($eventCategory_splitWordDescription)
                ->union($events_splitWordDescription)
                ->groupBy('moduleId')
                ->groupBy('id')
                ->groupBy('term');
        if ($pp == 'P') {
            if ($recordCount == true) {
                $response = $response->get()->count();
            } else {
                $response = $response->get();
            }
        } else {
            $response = $response->orderBy('intSearchRank', 'ASC');
            if ($recordCount == true) {
                $response = $response->get()->count();
            } else {
                $response = $response->offset($offset)
                        ->take($limit)
                        ->get();
            }
        }
        return $response;
    }

    public static function getDescriptionRecords() {
        $news = DB::table('news')->select('news.txtDescription', 'intSearchRank', 'fkIntDocId')
                ->where('chrDelete', 'N')
                ->where('chrPublish', 'Y')
                ->where('chrMain', 'Y')
                ->where('txtDescription', 'like', '%documents/%')
                ->orWhere('fkIntDocId', '>', 0)
                ->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        $cms_page = DB::table('cms_page')->select('cms_page.txtDescription', 'intSearchRank', DB::raw(' "na" as fkIntDocId'))
                ->where('chrDelete', 'N')
                ->where('chrPublish', 'Y')
                ->where('chrMain', 'Y')
                ->where('txtDescription', 'like', '%documents/%')
                ->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        $publications = DB::table('publications')->select(DB::raw(' "na" as txtDescription'), 'intSearchRank', 'publications.fkIntDocId')
                ->where('chrDelete', 'N')
                ->where('chrPublish', 'Y')
                ->where('chrMain', 'Y')
                ->where('fkIntDocId', '>', 0)
                ->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        $response = $cms_page
                ->union($news)
                ->union($publications)
                ->orderBy('intSearchRank', 'DESC')
                ->get();
        return $response;
    }

    public static function getDocumentsSearchByTerm($term) {
        $response = DB::table('documents')->select('id', 'txtDocumentName', 'txtSrcDocumentName', 'varDocumentExtension', 'intMobileViewCount', 'intDesktopViewCount', 'intMobileDownloadCount', 'intDesktopDownloadCount')
                ->where('chrDelete', 'N')
                ->where('chrPublish', 'Y')
                ->where('txtDocumentName', 'like', '%' . $term . '%')
                ->orWhere('txtSrcDocumentName', 'like', '%' . $term . '%')
                ->get();
        return $response;
    }

    public static function getRecordListDashboard($year = 4, $timeparam = 'year') {
        $response = false;
        $documentFields = [
            DB::raw('count( created_at ) AS SearchCount')
        ];
        if ($timeparam == 'year') {
            $documentFields[] = DB::raw('YEAR(created_at) as Year');
            $response = Self::select($documentFields)
                            ->whereRaw("YEAR(created_at) > YEAR( (DATE_SUB( CURDATE() , INTERVAL " . (int) $year . " YEAR ) ) )")
                            ->groupBy(DB::raw('YEAR(created_at)'))
                            ->get()->toArray();
            return $response;
        } elseif ($timeparam == 'month') {
            $documentFields[] = DB::raw('MONTHNAME(created_at) as Year');
            $response = Self::select($documentFields)
                            ->whereRaw("MONTH(created_at) > MONTH( (DATE_SUB( CURDATE() , INTERVAL " . ((int) $year ) . " MONTH ) ) )")
                            ->groupBy(DB::raw('MONTH(created_at)'))->get()->toArray();
            return $response;
        }
    }

    //auto complete search code
    public static function autocomplete_termSeach($term) {
        $autocomplete_pages = Self::autocomplete_pages($term);
        $autocomplete_publications = Self::autocomplete_publications($term);
        $autocomplete_publicationsCategories = Self::autocomplete_publicationsCategories($term);
        $autocomplete_news = Self::autocomplete_news($term);
        $autocomplete_newsCategory = Self::autocomplete_newsCategory($term);
        $autocomplete_careers = Self::autocomplete_careers($term);
        $autocomplete_faqCategory = Self::autocomplete_faqCategory($term);
        $autocomplete_faqs = Self::autocomplete_faqs($term);
        $autocomplete_videoGallery = Self::autocomplete_videoGallery($term);
        $autocomplete_eventCategory = Self::autocomplete_eventCategory($term);
        $autocomplete_events = Self::autocomplete_events($term);
        $autocomplete_photoAlbums = Self::autocomplete_photoAlbums($term);
        $autocomplete_department = Self::autocomplete_department($term);


        $response = $autocomplete_pages
                ->union($autocomplete_publications)
                ->union($autocomplete_publicationsCategories)
                ->union($autocomplete_news)
                ->union($autocomplete_newsCategory)
                ->union($autocomplete_careers)
                ->union($autocomplete_faqCategory)
                ->union($autocomplete_faqs)
                ->union($autocomplete_videoGallery)
                ->union($autocomplete_eventCategory)
                ->union($autocomplete_events)
                ->union($autocomplete_photoAlbums)
                ->union($autocomplete_department)
                ->groupBy('term')
                ->orderBy('intSearchRank', 'DESC')
                ->get();
        return $response;
    }

//==============================================================================================

    public static function autocomplete_pages($term) {
        #CMS Pages==============================
        $cmsPagesModuleObj = Modules::where('varModuleName', 'pages')->first();
        $ignoreId = [0];
        $pages = CmsPage::select(
                        'cms_page.intSearchRank', 'cms_page.varTitle as term', 'cms_page.txtDescription as info', //item has desc
                        'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'cms_page.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $cmsPagesModuleObj->id)
                ->where('cms_page.chrPublish', '=', 'Y')
                ->where('cms_page.chrMain', 'Y')
                ->where('cms_page.chrDelete', '=', 'N')
                ->where('cms_page.chrIsPreview', 'N')
                ->whereNotIn('cms_page.id', $ignoreId)
                ->whereRaw('(nq_cms_page.varTitle like "%' . self::cleanString($term) . '%" or nq_cms_page.txtDescription like "%' . self::cleanString($term) . '%")')
                ->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        #.\CMS Pages==============================
        return $pages;
    }

//===========================================================================================
    public static function autocomplete_publications($term) {
        #publications==============================
        $terms = str_word_count($term, 1);
        $publicationsModuleObj = Modules::where('varModuleName', 'publications')->first();
        $publicationsObj = DB::table('publications')
                ->select(
                        'publications.intSearchRank', 'publications.varTitle as term', 'publications.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'publications.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'publications.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'publications.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $publicationsModuleObj->id)
                ->where('publications.chrPublish', '=', 'Y')
                ->where('publications.chrDelete', '=', 'N')
                ->where('publications.chrMain', 'Y')
                ->where('publications.chrIsPreview', 'N');
        $rawstring = '(nq_publications.varTitle like "%' . self::cleanString($term) . '%" or nq_publications.txtDescription like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $publicationsObj = $publicationsObj->whereRaw($rawstring);
        $publicationsObj = $publicationsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $publicationsObj;
        #.\publications==============================
    }

//===========================================================================================
    public static function autocomplete_publicationsCategories($term) {
        #publicationsCategories ==============================
        $terms = str_word_count($term, 1);
        $publicationsCategoryModuleObj = Modules::where('varModuleName', 'publications-category')->first();
        $publicationsCategoriesListing = DB::table('publications_category')
                ->select(
                        'publications_category.intSearchRank', 'publications_category.varTitle as term', DB::raw('null as info'), 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'publications_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'publications_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $publicationsCategoryModuleObj->id)
                ->where('publications_category.chrPublish', '=', 'Y')
                ->where('publications_category.chrDelete', '=', 'N')
                ->where('publications_category.chrMain', 'Y')
                ->where('publications_category.chrIsPreview', 'N');
        $rawstring = '(nq_publications_category.varTitle like "%' . self::cleanString($term) . '%" ';
        $rawstring .= ')';
        $publicationsCategoriesListing = $publicationsCategoriesListing->whereRaw($rawstring);
        //->whereRaw('(nq_publications_category.varTitle like "%' . $term . '%" )')
        $publicationsCategoriesListing = $publicationsCategoriesListing->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $publicationsCategoriesListing;
        #.\publicationsCategories==============================
    }

//===========================================================================================
    public static function autocomplete_news($term) {
        #News==============================
        $terms = str_word_count($term, 1);
        $newsModuleObj = Modules::where('varModuleName', 'news')->first();
        $news = DB::table('news')
                ->select(
                        'news.intSearchRank', 'news.varTitle as term', 'news.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'news.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'news.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'news.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $newsModuleObj->id)
                ->where('news.chrPublish', '=', 'Y')
                ->where('news.chrDelete', '=', 'N')
                ->where('news.chrMain', 'Y')
                ->where('news.chrIsPreview', 'N');
        $rawstring = '(nq_news.varTitle like "%' . self::cleanString($term) . '%" or nq_news.varShortDescription like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $news = $news->whereRaw($rawstring);
        $news = $news->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $news;
        #.\News==============================
    }

//===========================================================================================
    public static function autocomplete_newsCategory($term) {
        #news category==============================
        $terms = str_word_count($term, 1);
        $newsCategoryModuleObj = Modules::where('varModuleName', 'news-category')->first();
        $newsCategories = DB::table('news_category')
                ->select(
                        'news_category.intSearchRank', 'news_category.varTitle as term', DB::raw('null as info'), 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'news_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'news_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $newsCategoryModuleObj->id)
                ->where('news_category.chrPublish', '=', 'Y')
                ->where('news_category.chrDelete', '=', 'N')
                ->where('news_category.chrMain', 'Y')
                ->where('news_category.chrIsPreview', 'N');
        $rawstring = '(nq_news_category.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $newsCategories = $newsCategories->whereRaw($rawstring);
        $newsCategories = $newsCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $newsCategories;
        #.\news category==============================
    }

//===========================================================================================
    public static function autocomplete_careers($term) {
        #careers==============================
        $terms = str_word_count($term, 1);
        $careersModuleObj = Modules::where('varModuleName', 'careers')->first();
        $careers = DB::table('careers')
                ->select(
                        'careers.intSearchRank', 'careers.varTitle as term', 'careers.varShortDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'careers.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'careers.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $careersModuleObj->id)
                ->where('careers.chrPublish', '=', 'Y')
                ->where('careers.chrDelete', '=', 'N')
                ->where('careers.chrMain', 'Y')
                ->where('careers.chrIsPreview', 'N');
        $rawstring = '(nq_careers.varTitle like "%' . self::cleanString($term) . '%" or nq_careers.varShortDescription like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $careers = $careers->whereRaw($rawstring);
        $careers = $careers->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $careers;
        #.\careers==============================
    }

//===========================================================================================
    public static function autocomplete_faqCategory($term) {
        #faq category==============================
        $terms = str_word_count($term, 1);
        $faqCategoryModuleObj = Modules::where('varModuleName', 'faq-category')->first();
        $faqCategories = DB::table('faq_category')
                ->select(
                        'faq_category.intSearchRank', 'faq_category.varTitle as term', 'faq_category.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'faq_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'faq_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $faqCategoryModuleObj->id)
                ->where('faq_category.chrPublish', '=', 'Y')
                ->where('faq_category.chrDelete', '=', 'N')
                ->where('faq_category.chrMain', 'Y')
                ->where('faq_category.chrIsPreview', 'N');
        $rawstring = '(nq_faq_category.varTitle like "%' . self::cleanString($term) . '%"  or nq_faq_category.txtDescription like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $faqCategories = $faqCategories->whereRaw($rawstring);
        $faqCategories = $faqCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqCategories;
        #.\faq category==============================
    }

//===========================================================================================
    public static function autocomplete_faqs($term) {
        #faq category==============================
        $terms = str_word_count($term, 1);
        $faqsModuleObj = Modules::where('varModuleName', 'faq-category')->first();
        $faqs = DB::table('faq')
                ->select(
                        'faq.intSearchRank', 'faq.varTitle as term', 'faq.txtDescription as info', DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $faqsModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $faqsModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $faqsModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw(' "FAQs" as moduleTitle'), 'faq.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), 'faq.intFKCategory'
                )
                ->where('faq.chrPublish', '=', 'Y')
                ->where('faq.chrDelete', '=', 'N')
                ->where('faq.chrMain', 'Y');
        $rawstring = '(nq_faq.varTitle like "%' . self::cleanString($term) . '%"  or nq_faq.txtDescription like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $faqs = $faqs->whereRaw($rawstring);
        $faqs = $faqs->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqs;
        #.\faq category==============================
    }

//===========================================================================================

    public static function autocomplete_videoGallery($term) {
        #videoGallery==============================
        $terms = str_word_count($term, 1);
        $videoGalleryModuleObj = Modules::where('varModuleName', 'video-gallery')->first();

        $videoGallery = DB::table('video_gallery')
                ->select(
                        'video_gallery.intSearchRank', 'video_gallery.varTitle as term', DB::raw('null as info'), DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $videoGalleryModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $videoGalleryModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $videoGalleryModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw("'" . $videoGalleryModuleObj->varTitle . "'" . ' as moduleTitle'), 'video_gallery.id', DB::raw(' "na" as fkIntDocId'), 'video_gallery.fkIntImgId', DB::raw(' "na" as intFKCategory')
                )
                ->where('video_gallery.chrPublish', '=', 'Y')
                ->where('video_gallery.chrDelete', '=', 'N')
                ->where('video_gallery.chrMain', 'Y');
        $rawstring = '(nq_video_gallery.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $videoGallery = $videoGallery->whereRaw($rawstring);
        $videoGallery = $videoGallery->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');

        #.\videoGallery==============================
        return $videoGallery;
    }

//===========================================================================================
    public static function autocomplete_eventCategory($term) {
        #event category==============================
        $terms = str_word_count($term, 1);
        $eventCategoryModuleObj = Modules::where('varModuleName', 'event-category')->first();
        $eventCategories = DB::table('event_category')
                ->select(
                        'event_category.intSearchRank', 'event_category.varTitle as term', 'event_category.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'event_category.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'event_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $eventCategoryModuleObj->id)
                ->where('event_category.chrPublish', '=', 'Y')
                ->where('event_category.chrDelete', '=', 'N')
                ->where('event_category.chrMain', 'Y')
                ->where('event_category.chrIsPreview', 'N');
        $rawstring = '(nq_event_category.varTitle like "%' . self::cleanString($term) . '%"  or nq_event_category.txtDescription like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $eventCategories = $eventCategories->whereRaw($rawstring);
        $eventCategories = $eventCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $eventCategories;
        #.\event category==============================
    }

//===========================================================================================
    public static function autocomplete_events($term) {
        #bank super vision==============================
        $terms = str_word_count($term, 1);
        $eventsModuleObj = Modules::where('varModuleName', 'events')->first();
        $eventsObj = DB::table('events')
                ->select(
                        'events.intSearchRank', 'events.varTitle as term', 'events.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'events.id', 'fkIntDocId', DB::raw(' "na" as fkIntImgId'), 'events.intFKCategory as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'events.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $eventsModuleObj->id)
                ->where('events.chrPublish', '=', 'Y')
                ->where('events.chrDelete', '=', 'N')
                ->where('events.chrMain', 'Y')
                ->where('events.chrIsPreview', 'N');
        $rawstring = '(nq_events.varTitle like "%' . self::cleanString($term) . '%" or nq_events.txtDescription like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $eventsObj = $eventsObj->whereRaw($rawstring);
        $eventsObj = $eventsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $eventsObj;
        #.\bank suprevision==============================
    }

//===========================================================================================

    public static function autocomplete_photoAlbums($term) {
        #photoAlbums==============================
        $terms = str_word_count($term, 1);
        $photoAlbumsModuleObj = Modules::where('varModuleName', 'photo-album')->first();
        $photoAlbumsObj = DB::table('photo_album')
                ->select(
                        'photo_album.intSearchRank', 'photo_album.varTitle as term', 'photo_album.txtDescription as info', 'alias.varAlias as slug', DB::raw('"na" as pageAliasId'), 'module.id as moduleId', 'module.varModelName', 'module.varModuleName', 'module.varTitle as moduleTitle', 'photo_album.id', DB::raw(' "na" as fkIntDocId'), 'fkIntImgId', 'photo_album.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'photo_album.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $photoAlbumsModuleObj->id)
                ->where('photo_album.chrPublish', '=', 'Y')
                ->where('photo_album.chrDelete', '=', 'N')
                ->where('photo_album.chrMain', 'Y')
                ->where('photo_album.chrIsPreview', 'N');
        $rawstring = '(nq_photo_album.varTitle like "%' . self::cleanString($term) . '%" or nq_photo_album.txtDescription like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $photoAlbumsObj = $photoAlbumsObj->whereRaw($rawstring);
        $photoAlbumsObj = $photoAlbumsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $photoAlbumsObj;
        #.\photoAlbums==============================
    }

//===========================================================================================
    public static function autocomplete_department($term) {
        #Department==============================
        $terms = str_word_count($term, 1);
        $departmentModuleObj = Modules::where('varModuleName', 'department')->first();

        $department = DB::table('department')
                ->select(
                        'department.intSearchRank', 'department.varTitle as term', DB::raw('null as info'), DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $departmentModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $departmentModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $departmentModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw(' "Department" as moduleTitle'), 'department.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), DB::raw(' "na" as intFKCategory')
                )
                ->where('department.chrPublish', '=', 'Y')
                ->where('department.chrDelete', '=', 'N')
                ->where('department.chrMain', 'Y');
        $rawstring = '(nq_department.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $department = $department->whereRaw($rawstring);
        $department = $department->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        #.\Department==============================
        return $department;
    }

//===========================================================================================

    public static function cleanString($string) {
        return addslashes($string);
    }

}
