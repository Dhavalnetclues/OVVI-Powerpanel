<?php

namespace Powerpanel\VideoGallery\Controllers;

use App\Http\Controllers\FrontController;
use App\Document;
use Request;
use Response;
use Input;
use App\Http\Traits\slug;
use App\Helpers\MyLibrary;
use App\Helpers\CustomPagination;
use Powerpanel\VideoGallery\Models\VideoGallery;

class VideoGalleryController extends FrontController {

    use slug;

    public function __construct() {
        parent::__construct();
    }

    public function index($alias = false) {

        $data = array();
        $limit = 12;
        $modulePageUrl = Request::segment(1);
        $print = Request::segment(2);

        $modulePageUrl .= "/" . $alias;
        
        $videoGalleryArr = VideoGallery::getFrontList($limit);

        $data['videoGalleryArr'] = $videoGalleryArr;
        $moduelFrontPageUrl = MyLibrary::getFront_Uri('video-gallery')['uri'];
        $moduleFrontWithCatUrl = ($alias != false ) ? $moduelFrontPageUrl . '/' . $alias : $moduelFrontPageUrl;
        $pagginateUrl = $moduleFrontWithCatUrl;

        return view('video-gallery::frontview.video-gallery', $data);
    }

}
