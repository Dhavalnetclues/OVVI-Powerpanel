<?php 
namespace Powerpanel\ShieldCMSTheme\Controllers\Powerpanel\log;

use App\Http\Controllers\PowerpanelController;
use Illuminate\Support\Facades\Input;
use App\Log;
use Request;
use DB;
use App\Modules;

class LogmanagerController extends PowerpanelController {
	

	public function __construct()
	{
			parent::__construct();
			if(isset($_COOKIE['locale'])){
				app()->setLocale($_COOKIE['locale']);
			}
	}	
	public function index()
	{
		$logdata=DB::table('logs')->get();

	//	echo "<pre>";
//		print_r($logdata); 

		$this->breadcrumb['title']= trans('shiledcmstheme::template.managelogmanagers');
		$breadcrumb=$this->breadcrumb;

		return view('shiledcmstheme::powerpanel.logmanager.log_manager',['breadcrumb'=>$breadcrumb]);
	}

	/**
 * This method loads team table data on view 
 * @return  View
 * @since   2016-11-14
 * @author  NetQuick
 */
	public function get_list()
	{		
		/*Start code for sorting*/
		if(!empty(Input::get('order')) && !empty(Input::get('columns'))){
				$orderColumnNo = Input::get('order')[0]['column'];
				$orderByFieldName = Input::get('columns')[$orderColumnNo]['name'];
				$orderTypeAscOrDesc = Input::get('order')[0]['dir'];
		}else{
				$orderByFieldName="";
				$orderTypeAscOrDesc="";
		}
		/*End code for sorting*/
		if(!empty(Input::get('searchValue'))){
				$searchFilter = Input::get('searchValue');
		}else{
				$searchFilter = "";
		}
		if (!empty(Input::get('customActionName'))) {
			$emailtypeFilter = Input::get('customActionName');
		} else {
			$emailtypeFilter = "";
		}

		$records = array();
		$records["data"] = array();
		$sEcho = intval(Input::get('draw'));
		$iDisplayStart = intval(Input::get('start'));
		$iDisplayLength = intval(Input::get('length'));		
		$arrResults = Log::list_log($iDisplayStart, $iDisplayLength, $orderByFieldName, $orderTypeAscOrDesc, $searchFilter, $emailtypeFilter);
		$iTotalRecords = count(Log::list_log($iDisplayStart, $iDisplayLength, $orderByFieldName, $orderTypeAscOrDesc, $searchFilter, $emailtypeFilter,true));		

		$end = $iDisplayStart + $iDisplayLength;
		$end = $end > $iTotalRecords ? $iTotalRecords : $end;		
		
		
		if (!empty($arrResults)) {

			foreach ($arrResults as $key => $value) {				
				$old_val ='';
				$new_val ='';
				$link = '';
				if(strlen($value->old_val)>0 && strtolower($value->action)=='update'){
					$old_val .=	'<a href="javascript:void(0)" class="without_bg_icon " onclick="return hs.htmlExpand(this,{width:300,headingText:\'Old Value\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-envelope"></span></a>';
					$old_val .=	'<div class="highslide-maincontent">'.$value->old_val.'</div>';
				}else{
					$old_val .=	'-';
				}
				if(strlen($value->new_val)>0 && strtolower($value->action)=='update'){				
				$new_val .=	'<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'New Value\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-envelope"></span></a>';
				$new_val .=	'<div class="highslide-maincontent">'.$value->new_val.'</div>';
				}else{$new_val .=	'-';}
				if($value->var_module_name=='contact') {
					$link	.= '<a href="'.url('powerpanel/'.'contacts').'">'.$value->var_module_name.'</a>';				
				}else{
					$link .= '<a href="'.url('powerpanel/'.$value->var_module_name).'">'.$value->var_module_name.'</a>';
				}			
				$records["data"][] = array(
					'<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id. '">',
					$value->name,
					$value->var_module_name,
					$link,
					$old_val,
					$new_val,
					$value->action,								
					date(''.Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT').'',strtotime($value->created_at))
				);
				
			}
		}
		
		if (!empty(Input::get("customActionType")) && Input::get("customActionType") == "group_action") {
			$records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
			$records["customActionMessage"] = "Records are listed successfully!"; // pass custom message(useful for getting status of group actions)
		}
		$records["draw"] = $sEcho;
		$records["recordsTotal"] = $iTotalRecords;
		$records["recordsFiltered"] = $iTotalRecords;
		
		echo json_encode($records);
	}	

	/**
 * This method destroys logs in multiples 
 * @return  Team index view
 * @since   2016-11-15
 * @author  NetQuick
 */
	public function destroyAll()
	{	
		$dataid = Input::get('ids');
		foreach ($dataid as $key=>$id) {
			Log::where('id', $id)->delete();
		}
	}

}