<?php

namespace Powerpanel\ShieldCMSTheme\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules;
use Powerpanel\CmsPage\Models\CmsPage;
use DB;
use Carbon\Carbon;
use Crypt;
use Request;
use Auth;

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
                        'news.intSearchRank', 
                        'news.varTitle as term', 
                        'news.varShortDescription as info', 
                        'alias.varAlias as slug', 
                        DB::raw('"na" as pageAliasId'), 
                        'module.id as moduleId', 
                        'module.varModelName', 
                        'module.varModuleName', 
                        'module.varTitle as moduleTitle', 
                        'news.id', 
                        'fkIntDocId', 
                        'fkIntImgId', 
                        'news.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'news.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->leftJoin('documents', 'documents.id', '=', 'news.fkIntDocId')
                ->where('alias.intFkModuleCode', '=', $newsModuleObj->id)
                ->where('news.chrPublish', '=', 'Y')
                ->where('news.chrDelete', '=', 'N')
                ->where('news.chrMain', 'Y')
                ->where('news.chrIsPreview', 'N');
        $rawstring = '(nq_news.varTitle like "%' . self::cleanString($term) . '%" or nq_news.varShortDescription like "%' . self::cleanString($term) .'%" or nq_documents.txtDocumentName like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $news = $news->whereRaw($rawstring);
        return $news;
    }

    public static function pages($term) {
        #CMS Pages==============================
        $terms = str_word_count($term, 1);
        $cmsPagesModuleObj = Modules::where('varModuleName', 'pages')->first();
        $ignoreId = [0];
        $pages = DB::table('cms_page')->select(
            'cms_page.intSearchRank', 
            'cms_page.varTitle as term', 
            DB::raw('"" as info'), 
            DB::raw('"" as slug'), 
            DB::raw('"na" as pageAliasId'), 
            'module.id as moduleId', 
            'module.varModelName', 
            'module.varModuleName', 
            'module.varTitle as moduleTitle', 
            'cms_page.id', 
            DB::raw(' "na" as fkIntDocId'), 
            DB::raw('"" as fkIntImgId'), 
            DB::raw(' "na" as intFKCategory')
            )
                ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $cmsPagesModuleObj->id)
                ->where('cms_page.chrPublish', '=', 'Y')
                ->where('cms_page.chrMain', 'Y')
                ->where('cms_page.chrDelete', '=', 'N')
                ->where('cms_page.chrIsPreview', 'N')
                ->where('cms_page.chrDraft', '=', 'N')
                ->whereNotIn('cms_page.id', $ignoreId);
        $rawstring = '( nq_cms_page.varTitle like "%' . self::cleanString($term) . '%" ';
        $rawstring .= ')';
        $pages = $pages->whereRaw($rawstring);
        return $pages;
    }

    public static function careers($term) {
        #careers==============================
        $terms = str_word_count($term, 1);
        $careersModuleObj = Modules::where('varModuleName', 'careers')->first();
        $careers = DB::table('careers')
                ->select(
                        'careers.intSearchRank', 
                        'careers.varTitle as term', 
                        DB::raw('"" as info'),
                        'alias.varAlias as slug', 
                        DB::raw('"" as pageAliasId'), 
                        'module.id as moduleId', 
                        'module.varModelName', 
                        'module.varModuleName', 
                        'module.varTitle as moduleTitle',
                        'careers.id', 
                        'fkIntDocId',
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
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
        // $careers = $careers->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $careers;
        #.\careers==============================
    }

    public static function careerCategory($term) {
        #career category==============================
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'career-category')->first();
        $tableObject = DB::table('career_category')
                ->select(
                        'career_category.intSearchRank',
                        'career_category.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'career_category.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('career_category.chrPublish', '=', 'Y')
                ->where('career_category.chrDelete', '=', 'N')
                ->where('career_category.chrMain', 'Y')
                ->where('career_category.chrIsPreview', 'N');
        $rawstring = '(nq_career_category.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
        #.\event category==============================
    }

    public static function faqCategory($term) {
        $terms = str_word_count($term, 1);
        $faqCategoryModuleObj = Modules::where('varModuleName', 'faq-category')->first();
        $faqCategories = DB::table('faq_category')
                ->select(
                        'faq_category.intSearchRank', 
                        'faq_category.varTitle as term', 
                        DB::raw('"" as info'),
                        'alias.varAlias as slug',
                        DB::raw('"" as pageAliasId'), 
                        'module.id as moduleId', 
                        'module.varModelName', 'module.varModuleName', 
                        'module.varTitle as moduleTitle',
                        'faq_category.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
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
        // $faqCategories = $faqCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqCategories;
    }

    public static function faqs($term) {
        $terms = str_word_count($term, 1);
        $faqsModuleObj = Modules::where('varModuleName', 'faq')->first();
        $faqs = DB::table('faq')
                ->select(
                        'faq.intSearchRank',
                        'faq.varTitle as term',
                        DB::raw('"" as info'),
                        DB::raw('"" as slug'),
                        DB::raw('"na" as pageAliasId'), 
                        DB::raw("'" . $faqsModuleObj->id . "'" . ' as moduleId'),
                        DB::raw("'" . $faqsModuleObj->varModelName . "'" . ' as varModelName'),
                        DB::raw("'" . $faqsModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw(' "FAQs" as moduleTitle'), 
                        'faq.id', 
                        DB::raw('"" as fkIntDocId'), 
                        DB::raw('"" as fkIntImgId'), 
                        DB::raw(' "na" as intFKCategory')
                )
                ->where('faq.chrPublish', '=', 'Y')
                ->where('faq.chrDelete', '=', 'N')
                ->where('faq.chrMain', 'Y');
                
        $rawstring = '(nq_faq.varTitle like "%' . self::cleanString($term) . '%" or nq_faq.txtDescription like "%' . self::cleanString($term) .'%"';
        $rawstring .= ')';
        $faqs = $faqs->whereRaw($rawstring);
        // $faqs = $faqs->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqs;
    }

//==============================================================================================

    public static function videoGallery($term) {
        #videoGallery==============================
        $terms = str_word_count($term, 1);
        $videoGalleryModuleObj = Modules::where('varModuleName', 'video-gallery')->first();

        $videoGallery = DB::table('video_gallery')
                ->select(
                        'video_gallery.intSearchRank', 
                        'video_gallery.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw(' null as slug'), 
                        DB::raw('"na" as pageAliasId'), 
                        DB::raw("'" . $videoGalleryModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $videoGalleryModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $videoGalleryModuleObj->varModuleName . "'" . ' as varModuleName'), 
                        DB::raw("'" . $videoGalleryModuleObj->varTitle . "'" . ' as moduleTitle'), 
                        'video_gallery.id', 
                        DB::raw(' "na" as fkIntDocId'), 
                        'video_gallery.fkIntImgId', 
                        DB::raw(' "na" as intFKCategory')
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

//==============================================================================================

    public static function newsCategory($term) {
        $terms = str_word_count($term, 1);
        $newsCategoryModuleObj = Modules::where('varModuleName', 'news-category')->first();
        $newsCategories = DB::table('news_category')
                ->select(
                        'news_category.intSearchRank', 
                        'news_category.varTitle as term', 
                        DB::raw('"" as info'), 
                        'alias.varAlias as slug',
                        DB::raw('"na" as pageAliasId'), 
                        'module.id as moduleId', 
                        'module.varModelName', 
                        'module.varModuleName', 
                        'module.varTitle as moduleTitle',  
                        'news_category.id', 
                        DB::raw('"" as fkIntDocId'),
                        DB::raw('"" as fkIntImgId'), 
                        DB::raw('"na" as intFKCategory')
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
        // $newsCategories = $newsCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $newsCategories;
    }

    public static function team($term) {
        $terms = str_word_count($term, 1);
        $teamModuleObj = Modules::where('varModuleName', 'team')->first();
        $team = DB::table('team')
                ->select(
                        'team.intSearchRank', 
                        'team.varTitle as term', 
                        'team.varShortDescription as info', 
                        'alias.varAlias as slug', 
                        DB::raw('"na" as pageAliasId'), 
                        'module.id as moduleId', 
                        'module.varModelName', 'module.varModuleName', 
                        'module.varTitle as moduleTitle', 
                        'team.id', 
                        DB::raw('"" as fkIntDocId'),
                        'fkIntImgId', 
                        DB::raw('"" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'team.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $teamModuleObj->id)
                ->where('team.chrPublish', '=', 'Y')
                ->where('team.chrDelete', '=', 'N')
                ->where('team.chrMain', 'Y')
                ->where('team.chrIsPreview', 'N');
        $rawstring = '(nq_team.varTitle like "%' . self::cleanString($term) . '%" or nq_team.varShortDescription like "%' . self::cleanString($term) .'%"';
        $rawstring .= ')';
        $team = $team->whereRaw($rawstring);
        return $team;
    }

    public static function blogs($term) {
        $terms = str_word_count($term, 1);
        $blogsModuleObj = Modules::where('varModuleName', 'blogs')->first();
        $blogs = DB::table('blogs')
                ->select(
                        'blogs.intSearchRank', 
                        'blogs.varTitle as term', 
                        'blogs.varShortDescription as info', 
                        'alias.varAlias as slug', 
                        DB::raw('"na" as pageAliasId'), 
                        'module.id as moduleId', 
                        'module.varModelName', 
                        'module.varModuleName', 
                        'module.varTitle as moduleTitle', 
                        'blogs.id', 
                        DB::raw('"" as fkIntDocId'), 
                        'fkIntImgId', 
                        'blogs.intFKCategory as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'blogs.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $blogsModuleObj->id)
                ->where('blogs.chrPublish', '=', 'Y')
                ->where('blogs.chrDelete', '=', 'N')
                ->where('blogs.chrMain', 'Y')
                ->where('blogs.chrIsPreview', 'N');
        $rawstring = '(nq_blogs.varTitle like "%' . self::cleanString($term) . '%" or nq_blogs.varShortDescription like "%' . self::cleanString($term) .'%"';
        $rawstring .= ')';
        $blogs = $blogs->whereRaw($rawstring);
        // $blogs = $blogs->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $blogs;
    }

    public static function blogCategory($term) {
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'blog-category')->first();
        $tableObject = DB::table('blog_category')
                ->select(
                        'blog_category.intSearchRank',
                        'blog_category.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'blog_category.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('blog_category.chrPublish', '=', 'Y')
                ->where('blog_category.chrDelete', '=', 'N')
                ->where('blog_category.chrMain', 'Y')
                ->where('blog_category.chrIsPreview', 'N');
        $rawstring = '(nq_blog_category.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
    }

    public static function eventCategory($term) {
        $terms = str_word_count($term, 1);
        $eventCategoryModuleObj = Modules::where('varModuleName', 'event-category')->first();
        $eventCategories = DB::table('event_category')
                ->select(
                    'event_category.intSearchRank',
                    'event_category.varTitle as term', 
                    DB::raw('"" as info'), 
                    'alias.varAlias as slug',
                    DB::raw('"" as pageAliasId'), 
                    'module.id as moduleId', 
                    'module.varModelName', 'module.varModuleName',
                    'module.varTitle as moduleTitle',
                    'event_category.id', 
                    DB::raw(' "" as fkIntDocId'),
                    DB::raw(' "" as fkIntImgId'), 
                    DB::raw(' "" as intFKCategory')
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
        // $eventCategories = $eventCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $eventCategories;
    }

    public static function services($term) {
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'service')->first();
        $tableObject = DB::table('service')
                ->select(
                        'service.intSearchRank',
                        'service.varTitle as term', 
                        'service.varShortDescription as info', 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'service.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('service.chrPublish', '=', 'Y')
                ->where('service.chrDelete', '=', 'N')
                ->where('service.chrMain', 'Y')
                ->where('service.chrIsPreview', 'N')
                ->where('service.chrTrash', 'N');
        $rawstring = '(nq_service.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
    }

    public static function serviceCategory($term) {
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'service-category')->first();
        $tableObject = DB::table('service_category')
                ->select(
                        'service_category.intSearchRank',
                        'service_category.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'service_category.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('service_category.chrPublish', '=', 'Y')
                ->where('service_category.chrDelete', '=', 'N')
                ->where('service_category.chrMain', 'Y')
                ->where('service_category.chrIsPreview', 'N')
                ->where('service_category.chrTrash', 'N');
        $rawstring = '(nq_service_category.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
    }

    public static function alerts($term) {
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'alerts')->first();
        $tableObject = DB::table('alerts')
                ->select(
                        'alerts.intSearchRank',
                        'alerts.varTitle as term', 
                        'alerts.varShortDescription as info', 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'alerts.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('alerts.chrPublish', '=', 'Y')
                ->where('alerts.chrDelete', '=', 'N')
                ->where('alerts.chrMain', 'Y')
                ->where('alerts.chrTrash', 'N');
        $rawstring = '(nq_alerts.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
    }

    public static function photo_gallery($term) {
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'photo-gallery')->first();
        $tableObject = DB::table('photo_gallery')
                ->select(
                        'photo_gallery.intSearchRank',
                        'photo_gallery.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'photo_gallery.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('photo_gallery.chrPublish', '=', 'Y')
                ->where('photo_gallery.chrDelete', '=', 'N')
                ->where('photo_gallery.chrMain', 'Y')
                ->where('photo_gallery.chrTrash', 'N');
        $rawstring = '(nq_photo_gallery.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
    }

//==============================================================================================

    public static function events($term) {
        $terms = str_word_count($term, 1);
        $eventsModuleObj = Modules::where('varModuleName', 'events')->first();
        $eventsObj = DB::table('events')
                ->select(
                        'events.intSearchRank', 
                        'events.varTitle as term', 
                        'events.varShortDescription as info', 
                        'alias.varAlias as slug', 
                        DB::raw('"na" as pageAliasId'), 
                        'module.id as moduleId', 
                        'module.varModelName', 
                        'module.varModuleName', 
                        'module.varTitle as moduleTitle', 
                        'events.id', 
                        'fkIntDocId',  
                        'fkIntImgId', 
                        'events.intFKCategory as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'events.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->leftJoin('documents', 'documents.id', '=', 'events.fkIntDocId')
                ->where('alias.intFkModuleCode', '=', $eventsModuleObj->id)
                ->where('events.chrPublish', '=', 'Y')
                ->where('events.chrDelete', '=', 'N')
                ->where('events.chrMain', 'Y')
                ->where('events.chrIsPreview', 'N');
        $rawstring = '(nq_events.varTitle like "%' . self::cleanString($term) . '%" or nq_events.varShortDescription like "%' . self::cleanString($term) .'%" or nq_documents.txtDocumentName like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $eventsObj = $eventsObj->whereRaw($rawstring);
        return $eventsObj;
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
                        'publications_category.intSearchRank',
                        'publications_category.varTitle as term', 
                        DB::raw('"" as info'), 
                        'alias.varAlias as slug',
                        DB::raw('"na" as pageAliasId'), 
                        'module.id as moduleId', 
                        'module.varModelName', 
                        'module.varModuleName', 
                        'module.varTitle as moduleTitle', 
                        'publications_category.id', 
                        DB::raw(' "na" as fkIntDocId'),
                        DB::raw(' "na" as fkIntImgId'), 
                        DB::raw(' "na" as intFKCategory')
                )
                ->leftJoin('alias', 'alias.id', '=', 'publications_category.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->where('alias.intFkModuleCode', '=', $publicationsCategoryModuleObj->id)
                ->where('publications_category.chrPublish', '=', 'Y')
                ->where('publications_category.chrDelete', '=', 'N')
                ->where('publications_category.chrMain', 'Y')
                ->where('publications_category.chrIsPreview', 'N');
        $rawstring = '(nq_publications_category.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $publicationsCategoriesListing = $publicationsCategoriesListing->whereRaw($rawstring);

        // $publicationsCategoriesListing = $publicationsCategoriesListing->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $publicationsCategoriesListing;
    }
    
    public static function publications($term) {
        $terms = str_word_count($term, 1);
        $publicationsModuleObj = Modules::where('varModuleName', 'publications')->first();
        $publicationsObj = DB::table('publications')
                ->select(
                        'publications.intSearchRank', 
                        'publications.varTitle as term', 
                        'publications.txtDescription as info', 
                        DB::raw('"" as slug'), 
                        DB::raw('"na" as pageAliasId'), 
                        'module.id as moduleId', 
                        'module.varModelName', 
                        'module.varModuleName', 
                        'module.varTitle as moduleTitle', 
                        'publications.id', 
                        'fkIntDocId', 
                        DB::raw('"" as fkIntImgId'), 
                        'publications.txtCategories as intFKCategory'
                )
                ->leftJoin('alias', 'alias.id', '=', 'publications.intAliasId')
                ->leftJoin('module', 'module.id', '=', 'alias.intFkModuleCode')
                ->leftJoin('documents', 'documents.id', '=', 'publications.fkIntDocId')
                ->where('alias.intFkModuleCode', '=', $publicationsModuleObj->id)
                ->where('publications.chrPublish', '=', 'Y')
                ->where('publications.chrDelete', '=', 'N')
                ->where('publications.chrMain', 'Y')
                ->where('publications.chrIsPreview', 'N');

        $rawstring = '(nq_publications.varTitle like "%' . self::cleanString($term) . '%" or nq_publications.varShortDescription like "%' . self::cleanString($term) .'%" or nq_documents.txtDocumentName like "%' . self::cleanString($term) . '%"';
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
                        'photo_album.intSearchRank', 
                        'photo_album.varTitle as term', 
                        'photo_album.varShortDescription as info', 
                        'alias.varAlias as slug', 
                        DB::raw('"na" as pageAliasId'), 
                        'module.id as moduleId', 
                        'module.varModelName', 
                        'module.varModuleName', 
                        'module.varTitle as moduleTitle', 
                        'photo_album.id', 
                        DB::raw(' "na" as fkIntDocId'), 
                        'photo_album.fkIntImgId', 
                        DB::raw(' "na" as intFKCategory')
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
    public static function termSeach($term, $limit = 50, $recordCount = false) {
    	$isAvailableFirstModule = false;
    		
        $data = Request::all();
        
        $data['page'] = (null !== (Request::get('page'))) ? Request::get('page') : 1;
        $limit = $limit;
        
        $offset = (isset($data['page']) && $data['page'] != "") ? (((int)$data['page'] - 1) * (int)$limit) : 0;

        /* pages Group */
        if(Auth::user()->can('pages-list')){
      		$pages = Self::pages($term);
      		$response = $pages;
        	$isAvailableFirstModule=true;
    		}

    		if(Auth::user()->can('publications-list')){
    			$publications = Self::publications($term);
    			if($isAvailableFirstModule){
        		$response = $response->union($publications);	
        	}else{
        		$response = $publications;
        		$isAvailableFirstModule=true;	
        	}
    		}

        if(Auth::user()->can('publications-category-list')){
        	$publicationsCategories = Self::publicationsCategories($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($publicationsCategories);	
        	}else{
        		$response = $publicationsCategories;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('news-list')){
        	$news = Self::news($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($news);	
        	}else{
        		$response = $news;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('news-category-list')){
        	$newsCategory = Self::newsCategory($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($newsCategory);	
        	}else{
        		$response = $newsCategory;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('careers-list')){
        	$careers = Self::careers($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($careers);	
        	}else{
        		$response = $careers;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('faq-category-list')){
        	$faqCategory = Self::faqCategory($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($faqCategory);	
        	}else{
        		$response = $faqCategory;
        		$isAvailableFirstModule=true;	
        	}	
        }

        if(Auth::user()->can('faq-list')){
        	$faqs = Self::faqs($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($faqs);	
        	}else{
        		$response = $faqs;
        		$isAvailableFirstModule=true;	
        	}	
        }

        if(Auth::user()->can('event-category-list')){
        	$eventCategory = Self::eventCategory($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($eventCategory);	
        	}else{
        		$response = $eventCategory;
        		$isAvailableFirstModule=true;	
        	}	
        }

        if(Auth::user()->can('events-list')){
        	$events = Self::events($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($events);	
        	}else{
        		$response = $events;
        		$isAvailableFirstModule=true;	
        	}		
        }

        if(Auth::user()->can('video-gallery-list')){
        	$videoGallery = Self::videoGallery($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($videoGallery);	
        	}else{
        		$response = $videoGallery;
        		$isAvailableFirstModule=true;	
        	}		
        }

        if(Auth::user()->can('photo-album-list')){
        	$photoAlbums = Self::photoAlbums($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($photoAlbums);	
        	}else{
        		$response = $photoAlbums;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('career-category-list')){
        	$careerCategory = Self::careerCategory($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($careerCategory);	
        	}else{
        		$response = $careerCategory;
        		$isAvailableFirstModule=true;	
        	}	
        }

        if(Auth::user()->can('blog-category-list')){
        	$blogCategory = Self::blogCategory($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($blogCategory);	
        	}else{
        		$response = $blogCategory;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('blogs-list')){
        	$blogs = Self::blogs($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($blogs);	
        	}else{
        		$response = $blogs;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('service-list')){
        	$service = Self::services($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($service);	
        	}else{
        		$response = $service;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('service-category')){
        	$serviceCategory = Self::serviceCategory($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($serviceCategory);	
        	}else{
        		$response = $serviceCategory;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('team-list')){
        	$team = Self::team($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($team);	
        	}else{
        		$response = $team;
        		$isAvailableFirstModule=true;	
        	}	
        }

        if(Auth::user()->can('alerts-list')){
        	$alerts = Self::alerts($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($alerts);	
        	}else{
        		$response = $alerts;
        		$isAvailableFirstModule=true;	
        	}	
        }

        if(Auth::user()->can('photo-gallery-list')){
        	$photo_gallery = Self::photo_gallery($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($photo_gallery);	
        	}else{
        		$response = $photo_gallery;
        		$isAvailableFirstModule=true;	
        	}	
        }

        $response = $response
                ->groupBy('moduleId')
                ->groupBy('id')
                ->groupBy('term');
        
        $response = $response->orderBy('intSearchRank', 'ASC');
        if ($recordCount == true) {
            $response = $response->get()->count();
        } else {
            $response = $response->offset($offset)
            										 ->take($limit)
            										 ->get();

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
    	$response =false;
    		$isAvailableFirstModule = false;
    		if(Auth::user()->can('pages-list')){
    			$autocomplete_pages = Self::autocomplete_pages($term);
        	$response = $autocomplete_pages;
        	$isAvailableFirstModule=true;
    		}
        
        if(Auth::user()->can('publications-list')){
        	$autocomplete_publications = Self::autocomplete_publications($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_publications);	
        	}else{
        		$response = $autocomplete_publications;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('publications-category-list')){
        	$autocomplete_publicationsCategories = Self::autocomplete_publicationsCategories($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_publicationsCategories);	
        	}else{
        		$response = $autocomplete_publicationsCategories;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('news-list')){
        	$autocomplete_news = Self::autocomplete_news($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_news);	
        	}else{
        		$response = $autocomplete_news;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('news-category-list')){
        	$autocomplete_newsCategory = Self::autocomplete_newsCategory($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_newsCategory);	
        	}else{
        		$response = $autocomplete_newsCategory;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('careers-list')){
        	$autocomplete_careers = Self::autocomplete_careers($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_careers);	
        	}else{
        		$response = $autocomplete_careers;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('faq-category-list')){
        	$autocomplete_faqCategory = Self::autocomplete_faqCategory($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_faqCategory);	
        	}else{
        		$response = $autocomplete_faqCategory;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('faq-list')){
        	$autocomplete_faqs = Self::autocomplete_faqs($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_faqs);	
        	}else{
        		$response = $autocomplete_faqs;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('event-category-list')){
        	$autocomplete_eventCategory = Self::autocomplete_eventCategory($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_eventCategory);	
        	}else{
        		$response = $autocomplete_eventCategory;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('events-list')){
        	$autocomplete_events = Self::autocomplete_events($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_events);	
        	}else{
        		$response = $autocomplete_events;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('video-gallery-list')){
        	$autocomplete_videoGallery = Self::autocomplete_videoGallery($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_videoGallery);	
        	}else{
        		$response = $autocomplete_videoGallery;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('photo-album-list')){
        	$autocomplete_photoAlbums = Self::autocomplete_photoAlbums($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_photoAlbums);	
        	}else{
        		$response = $autocomplete_photoAlbums;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('career-category-list')){
        	$autocomplete_careerCategory = Self::autocomplete_careerCategory($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_careerCategory);	
        	}else{
        		$response = $autocomplete_careerCategory;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('blog-category-list')){
        	$autocomplete_blogCategory = Self::autocomplete_blogCategory($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_blogCategory);	
        	}else{
        		$response = $autocomplete_blogCategory;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('blogs-list')){
        	$autocomplete_blogs = Self::autocomplete_blogs($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_blogs);	
        	}else{
        		$response = $autocomplete_blogs;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('service-list')){
        	$autocomplete_services = Self::autocomplete_services($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_services);	
        	}else{
        		$response = $autocomplete_services;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('service-category-list')){
        	$autocomplete_serviceCategory = Self::autocomplete_serviceCategory($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_serviceCategory);	
        	}else{
        		$response = $autocomplete_serviceCategory;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('team-list')){
        	$autocomplete_team = Self::autocomplete_team($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_team);	
        	}else{
        		$response = $autocomplete_team;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('alerts-list')){
        	$autocomplete_alerts = Self::autocomplete_alerts($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_alerts);	
        	}else{
        		$response = $autocomplete_alerts;
        		$isAvailableFirstModule=true;	
        	}
        }

        if(Auth::user()->can('photo-gallery-list')){
        	$autocomplete_photo_gellery = Self::autocomplete_photo_gallery($term);
        	if($isAvailableFirstModule){
        		$response = $response->union($autocomplete_photo_gellery);	
        	}else{
        		$response = $autocomplete_photo_gellery;
        		$isAvailableFirstModule=true;	
        	}
        }

        if($isAvailableFirstModule){
        	$response = $response->groupBy('term')
                ->orderBy('intSearchRank', 'DESC')
                ->get();	
        }
        
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
        //$careers = $careers->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
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
        //$faqCategories = $faqCategories->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $faqCategories;
        #.\faq category==============================
    }

//===========================================================================================
    public static function autocomplete_faqs($term) {
        #faq category==============================
        $terms = str_word_count($term, 1);
        $faqsModuleObj = Modules::where('varModuleName', 'faq')->first();
        $faqs = DB::table('faq')
                ->select(
                        'faq.intSearchRank', 'faq.varTitle as term', 'faq.txtDescription as info', DB::raw(' null as slug'), DB::raw('"na" as pageAliasId'), DB::raw("'" . $faqsModuleObj->id . "'" . ' as moduleId'), DB::raw("'" . $faqsModuleObj->varModelName . "'" . ' as varModelName'), DB::raw("'" . $faqsModuleObj->varModuleName . "'" . ' as varModuleName'), DB::raw(' "FAQs" as moduleTitle'), 'faq.id', DB::raw(' "na" as fkIntDocId'), DB::raw(' "na" as fkIntImgId'), 'faq.intFKCategory'
                )
                ->where('faq.chrPublish', '=', 'Y')
                ->where('faq.chrDelete', '=', 'N')
                ->where('faq.chrMain', 'Y')
                ->where('faq.chrTrash', 'N');
        $rawstring = '(nq_faq.varTitle like "%' . self::cleanString($term) . '%"  or nq_faq.txtDescription like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $faqs = $faqs->whereRaw($rawstring);
        //$faqs = $faqs->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
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
        //$videoGallery = $videoGallery->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');

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
                        'event_category.intSearchRank',
                        'event_category.varTitle as term', 
                        DB::raw('"" as info'), 
                        'alias.varAlias as slug',
                        DB::raw('"" as pageAliasId'), 
                        'module.id as moduleId', 
                        'module.varModelName', 'module.varModuleName',
                        'module.varTitle as moduleTitle',
                        'event_category.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
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
                    'photo_album.intSearchRank', 
                    'photo_album.varTitle as term', 
                    'photo_album.txtDescription as info', 
                    'alias.varAlias as slug', 
                    DB::raw('"na" as pageAliasId'), 
                    'module.id as moduleId', 
                    'module.varModelName', 
                    'module.varModuleName', 
                    'module.varTitle as moduleTitle', 
                    'photo_album.id', 
                    DB::raw(' "na" as fkIntDocId'), 
                    'fkIntImgId', 
                    DB::raw(' "" as intFKCategory')
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
        //$photoAlbumsObj = $photoAlbumsObj->whereRaw('(DATE(NOW()) BETWEEN DATE(dtDateTime) AND DATE(dtEndDateTime) OR (DATE(NOW()) >= DATE(dtDateTime) and dtEndDateTime is null))');
        return $photoAlbumsObj;
        #.\photoAlbums==============================
    }

//===========================================================================================

    public static function autocomplete_careerCategory($term) {
        #career category==============================
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'career-category')->first();
        $tableObject = DB::table('career_category')
                ->select(
                        'career_category.intSearchRank',
                        'career_category.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'career_category.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('career_category.chrPublish', '=', 'Y')
                ->where('career_category.chrDelete', '=', 'N')
                ->where('career_category.chrMain', 'Y')
                ->where('career_category.chrIsPreview', 'N');
        $rawstring = '(nq_career_category.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
        #.\event category==============================
    }

//===========================================================================================

    public static function autocomplete_blogCategory($term) {
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'blog-category')->first();
        $tableObject = DB::table('blog_category')
                ->select(
                        'blog_category.intSearchRank',
                        'blog_category.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'blog_category.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('blog_category.chrPublish', '=', 'Y')
                ->where('blog_category.chrDelete', '=', 'N')
                ->where('blog_category.chrMain', 'Y')
                ->where('blog_category.chrIsPreview', 'N');
        $rawstring = '(nq_blog_category.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
    }
//===========================================================================================

    public static function autocomplete_blogs($term) {        
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'blogs')->first();
        $tableObject = DB::table('blogs')
                ->select(
                        'blogs.intSearchRank',
                        'blogs.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'blogs.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('blogs.chrPublish', '=', 'Y')
                ->where('blogs.chrDelete', '=', 'N')
                ->where('blogs.chrMain', 'Y')
                ->where('blogs.chrIsPreview', 'N');
        $rawstring = '(nq_blogs.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
        #.\blogs==============================
    }
//===========================================================================================

    public static function autocomplete_services($term) {
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'service')->first();
        $tableObject = DB::table('service')
                ->select(
                        'service.intSearchRank',
                        'service.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'service.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('service.chrPublish', '=', 'Y')
                ->where('service.chrDelete', '=', 'N')
                ->where('service.chrMain', 'Y')
                ->where('service.chrIsPreview', 'N')
                ->where('service.chrTrash', 'N');
        $rawstring = '(nq_service.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
    }
//===========================================================================================

    public static function autocomplete_serviceCategory($term) {
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'service-category')->first();
        $tableObject = DB::table('service_category')
                ->select(
                        'service_category.intSearchRank',
                        'service_category.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'service_category.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('service_category.chrPublish', '=', 'Y')
                ->where('service_category.chrDelete', '=', 'N')
                ->where('service_category.chrMain', 'Y')
                ->where('service_category.chrIsPreview', 'N')
                ->where('service_category.chrTrash', 'N');
        $rawstring = '(nq_service_category.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
    }
//===========================================================================================

    public static function autocomplete_team($term) {
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'team')->first();
        $tableObject = DB::table('team')
                ->select(
                        'team.intSearchRank',
                        'team.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'team.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('team.chrPublish', '=', 'Y')
                ->where('team.chrDelete', '=', 'N')
                ->where('team.chrMain', 'Y')
                ->where('team.chrIsPreview', 'N')
                ->where('team.chrTrash', 'N');
        $rawstring = '(nq_team.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
    }

//===========================================================================================
		public static function autocomplete_alerts($term) {
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'alerts')->first();
        $tableObject = DB::table('alerts')
                ->select(
                        'alerts.intSearchRank',
                        'alerts.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'alerts.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('alerts.chrPublish', '=', 'Y')
                ->where('alerts.chrDelete', '=', 'N')
                ->where('alerts.chrMain', 'Y')
                ->where('alerts.chrTrash', 'N');
        $rawstring = '(nq_alerts.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
    }

//===========================================================================================
		public static function autocomplete_photo_gallery($term) {
        $terms = str_word_count($term, 1);
        $ModuleObj = Modules::where('varModuleName', 'photo-gallery')->first();
        $tableObject = DB::table('photo_gallery')
                ->select(
                        'photo_gallery.intSearchRank',
                        'photo_gallery.varTitle as term', 
                        DB::raw('"" as info'), 
                        DB::raw('"" as slug'),
                        DB::raw('"" as pageAliasId'), 
                        DB::raw("'" . $ModuleObj->id . "'" . ' as moduleId'), 
                        DB::raw("'" . $ModuleObj->varModelName . "'" . ' as varModelName'), 
                        DB::raw("'" . $ModuleObj->varModuleName . "'" . ' as varModuleName'),
                        DB::raw("'" . $ModuleObj->varTitle . "'" . ' as moduleTitle'),
                        'photo_gallery.id', 
                        DB::raw(' "" as fkIntDocId'),
                        DB::raw(' "" as fkIntImgId'), 
                        DB::raw(' "" as intFKCategory')
                )
                
                ->where('photo_gallery.chrPublish', '=', 'Y')
                ->where('photo_gallery.chrDelete', '=', 'N')
                ->where('photo_gallery.chrMain', 'Y')
                ->where('photo_gallery.chrTrash', 'N');
        $rawstring = '(nq_photo_gallery.varTitle like "%' . self::cleanString($term) . '%"';
        $rawstring .= ')';
        $tableObject = $tableObject->whereRaw($rawstring);
        return $tableObject;
    }
//===========================================================================================

    public static function cleanString($string) {
        return addslashes($string);
    }

}
