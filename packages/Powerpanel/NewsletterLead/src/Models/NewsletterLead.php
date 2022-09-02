<?php
namespace Powerpanel\NewsletterLead\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Helpers\MyLibrary;

class NewsletterLead extends Model 
{	
	protected $table = 'newsletter_lead';
	protected $fillable = [
		'id',
		'varName',
		'varEmail',
		'VarToken',
		'chrPublish',
		'chrDelete',
		'chrSubscribed',
		'varIpAddress',
		'created_at',
		'updated_at'
	];
	
  public static function getCurrentMonthCount(){
    $response = false;
    $response = Self::getRecords()
    ->whereRaw('MONTH(created_at) = MONTH(CURRENT_DATE())') 
    ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE())')
    ->where('chrPublish','=','Y')
    ->where('chrDelete','=','N')  
    ->count();
    return $response;
  }

  public static function getCurrentYearCount(){
    $response = false;
    $response = Self::getRecords()    
    ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE())')
    ->where('chrPublish','=','Y')
    ->where('chrDelete','=','N')  
    ->count();
    return $response;
  }
  
	/**
   * This method handels retrival of records
   * @return  Object
   * @since   2017-08-02
   * @author  NetQuick
   */
  static function getRecords() {
      return self::with([]);
  }

  /**
   * This method handels backend records
   * @return  Object
   * @since   2016-07-14
   * @author  NetQuick
   */
  static function getPowerPanelRecords( $moduleFields=false ) 
  {     
    $data=[];
    $response = false;
    $response=self::select($moduleFields);
    if(count($data)>0){
      $response = $response->with($data);
    }     
    return $response;
  }

  /**
   * This method handels retrival of backend record list
   * @return  Object
   * @since   2017-10-24
   * @author  NetQuick
   */
  public static function getRecordList($filterArr=false){
    $response = false;
    $moduleFields=[ 'id', 'varName','varEmail','chrSubscribed','VarToken','varIpAddress','chrPublish','created_at','updated_at'];
    $response = Self::getPowerPanelRecords($moduleFields)
    ->deleted()
    ->filter($filterArr)
    ->get();
    return $response;
  }
 public static function checkUnSubscriberExist($email) {
        $response = false;
        $moduleFields = ['varEmail'];
        $query = self::select($moduleFields)
                ->checkEmail($email)
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->where('chrSubscribed', '=', 'N')
                ->get();
        if (!empty($query) && $query->count() > 0) {
            $response = true;
        }
        return $response;
    }
  /**
   * This method handels retrival of backend record list for Export
   * @return  Object
   * @since   2017-10-24
   * @author  NetQuick
   */
  public static function getListForExport($selectedIds=false){
    $response = false;
    $moduleFields=[ 'varName','varEmail','chrSubscribed','VarToken','chrPublish','varIpAddress','created_at'];
    $query = Self::getPowerPanelRecords($moduleFields)->deleted();
    if(!empty($selectedIds) && count($selectedIds) > 0){
      $query->checkMultipleRecordId($selectedIds);
    }
    $response = $query->orderByCreatedAtDesc()->get();
    return $response;
  }
    
  /**
   * This method handels record id scope
   * @return  Object
   * @since   2016-07-24
   * @author  NetQuick
   */
  function scopeCheckRecordId($query, $id) {
      return $query->where('id', $id);
  }

  /**
   * This method handels publish scope
   * @return  Object
   * @since   2017-08-02
   * @author  NetQuick
   */
  function scopePublish($query) {
      return $query->where(['chrPublish' => 'Y']);
  }
  /**
   * This method handels delete scope
   * @return  Object
   * @since   2017-08-02
   * @author  NetQuick
   */
  function scopeDeleted($query) {
      return $query->where(['chrDelete' => 'N']);
  }
 function scopecheckToken($query, $id, $Token) {
        return $query->where('VarToken', $Token)->where('id', $id);
    }

  /**
   * This method handels delete scope
   * @return  Object
   * @since   2017-08-02
   * @author  NetQuick
   */
  function scopeCheckEmail($query,$email) {
      return $query->where(['varEmail' => $email]);
  }

  /**
   * This method check multiple records id
   * @return  Object
   * @since   2017-08-02
   * @author  NetQuick
   */
  function scopeCheckMultipleRecordId($query,$Ids) 
  {
      return $query->whereIn('id',$Ids);
  }
 function scopeCheckRecordId_unsubscribe($query, $id) {
        return $query->where('id', $id)->where('chrSubscribed', 'Y');
    }
   /**
   * This method handle order by query
   * @return  Object
   * @since   2017-08-02
   * @author  NetQuick
   */
  function scopeOrderByCreatedAtDesc($query) {
      return $query->orderBy('created_at','DESC');
  }

  /**
   * This method handels filter scope
   * @return  Object
   * @since   2017-08-02
   * @author  NetQuick
   */
  function scopeFilter($query, $filterArr = false ,$retunTotalRecords = false) 
  {
        $response = null;
        if ($filterArr['orderByFieldName'] != null && $filterArr['orderTypeAscOrDesc'] != null) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }

        if(isset($filterArr['searchFilter']) && !empty($filterArr['searchFilter']))
        {
            $data = $query->where('varEmail',MyLibrary::getEncryptedString($filterArr['searchFilter']));
        }

        if (!empty($filterArr['start']) && $filterArr['start'] != ' ') {
            $data = $query->whereRaw('DATE(created_at) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
        }

        if (!empty($filterArr['start']) && $filterArr['start'] != '' &&  empty($filterArr['end']) && $filterArr['end'] == '') {
            $data = $query->whereRaw('DATE(created_at) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
        }

        if (!empty($filterArr['end']) && $filterArr['end'] != ' ') {
            $data = $query->whereRaw('DATE(created_at) <= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['end']))) . '") AND created_at IS NOT null');
        }
        
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
  }

  public static function checkSubscriberExist($email){
    $response = false;
    $moduleFields=[ 'varEmail'];
    $query=self::select($moduleFields)
                    ->checkEmail($email)
            ->where(['chrSubscribed' => 'Y'])
                    ->get();
    if(!empty($query) && $query->count() > 0){
      $response = true;
    }
    
    return $response;
  }
  
  public static function getRecordListDashboard($year = false, $timeparam = false, $month = false) {
        $response = false;
        $response = Self::select('id');
        $response = $response->where('chrPublish', '=', 'Y')->where('chrDelete', '=', 'N');
        if ($timeparam != 'month') {
            $response = $response->whereRaw("YEAR(created_at) = " . (int) $year . "")->count();
        } else {
            $response = $response->whereRaw("YEAR(created_at) = " . (int) $year . "")->whereRaw("MONTH(created_at) = " . (int) $month . "")->count();
        }
        return $response;
    }

  
    public static function getDashboardReport($year = false) {
        $response = false;
        $response = Self::select('*')->where('chrDelete', 'N');
        if ($year != '') {
            $response = $response->whereRaw("YEAR(created_at) >= " . (int) $year . "");
        }
        $response = $response->count();
        return $response;
    }
}