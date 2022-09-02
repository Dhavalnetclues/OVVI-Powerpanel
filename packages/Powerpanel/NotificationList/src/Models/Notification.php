<?php
namespace Powerpanel\NotificationList\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
class Notification extends Model{
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $table = 'notifications';
	protected $fillable = [
		'fk_user_id','fk_module_id','fk_record_id','txt_notification','txt_recent_notification','chr_read'
	];
	static function notifications($sessionID=false){
		$response = false;
		$query = '';
		$query = DB::table('notifications')
		->select('notifications.*','alias.alias','alias.fk_module_code','alias.fk_record_code','users.name','modules.var_module_name','images.txt_image_url','images.txt_thumb_url','images.var_image_slug','images.txt_image_alt_tag','images.var_image_extension')
		->leftJoin('alias','notifications.fk_module_id','=','alias.fk_module_code')
		->leftJoin('alias as al','notifications.fk_record_id','=','alias.fk_record_code')
		->leftJoin('users','notifications.fk_user_id','=','users.id')
		->leftJoin('modules','notifications.fk_module_id','=','modules.int_code')
		->leftJoin('images','users.fk_image_id','=','images.id')
		->where('notifications.chr_publish','=','Y')
		->where('notifications.chr_delete','=','N')
		->whereNotIn('notifications.fk_user_id',[$sessionID])
		->orderBy('notifications.id','DESC')
		->groupBy('notifications.id')
		->take(10)->get();
		$response = $query;
		return $response;
	}
	static function update_read_status($id=false){
            echo $id;exit;
		$response = false;
		$query = '';
		if(!empty($id)){
			$query = DB::table('notifications')
      ->where('id',$id)
      ->update(['chr_read' => 'Y']);
		}
		$response = $query;
		return $response;	
	}
	static function notificationCount($sessionID = false){
		$response = false;
		$query = '';
		$query = DB::table('notifications')
		->where('chr_publish','=','Y')
		->where('chr_delete','=','N')
		->where('chr_read','=','N')
		->whereNotIn('fk_user_id',[$sessionID])
		->orderBy('updated_at','DESC')
		->count();
		$response = $query;
		return $response;
	}
}
