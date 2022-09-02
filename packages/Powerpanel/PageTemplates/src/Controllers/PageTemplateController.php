<?php

namespace Powerpanel\PageTemplates\Controllers;

use App\Http\Controllers\FrontController;
use Powerpanel\PageTemplates\Models\PageTemplate;
use App\Helpers\FrontPageContent_Shield;
use App\Document;
use Request;
use Response;
use App\Http\Traits\slug;
use App\Helpers\MyLibrary;
use App\Helpers\CustomPagination;

class PageTemplateController extends FrontController {

		use slug;

		/**
		 * Create a new controller instance.
		 *
		 * @return void
		 */
		public function __construct() {
				parent::__construct();
		}

		/**
		 * This method loads Careers list view
		 * @return  View
		 * @since   2018-08-27
		 * @author  NetQuick
		 */
		public function index($alias = false) {
				$categoryData = '';
				if (is_numeric($alias)) {
						$pageTemplates = PageTemplate::getRecordById($alias);
				} else {
						$Aliasid = slug::resolve_alias($alias);
						$pageTemplates = PageTemplate::getRecordIdByAliasID($Aliasid);
				}

				if (!empty($pageTemplates)) {

						view()->share('META_TITLE', 'Shield CMS - Page CMS Template');
						view()->share('META_DESCRIPTION', 'Shield CMS - Page CMS Template');

						$breadcrumb = [];
						$data = [];
						$breadcrumb['title'] = (!empty($pageTemplates->varTitle)) ? ucwords($pageTemplates->varTitle) : '';
						$breadcrumb['url'] = MyLibrary::getFront_Uri('page_template')['uri'];
						$detailPageTitle = $breadcrumb['title'];
						
						$data['detailPageTitle'] = 'Page Template';
						$data['pageTemplateData'] = $pageTemplates;
						$data['moduleTitle'] = 'Page Template';
						
						
						$data['breadcumbcurrentPageTitle'] = $detailPageTitle;
						$data['breadcrumb'] = $breadcrumb;
						if (isset($pageTemplates->txtDesc) && $pageTemplates->txtDesc != '') {
							$data['templateContent'] = FrontPageContent_Shield::renderBuilder($pageTemplates->txtDesc)['response'];
						}else{
							$data['templateContent'] ="";
						}
						return view('pageTemplateView', $data);
				} else {
						abort(404);
				}
		}

}
