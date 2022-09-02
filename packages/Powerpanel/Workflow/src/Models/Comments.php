<?php

/**
 * The ModuleGroup class handels Comments model queries
 * ORM implemetation.
 * @package   Netquick powerpanel 
 * @version   1.00
 * @since   	25-Sep-2018
 */

namespace Powerpanel\Workflow\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;

class Comments extends Model {

		protected $table = 'comments';
		protected $fillable = [
				'id',
				'Fk_ParentCommentId',
				'intRecordID',
				'fkMainRecord',
				'varModuleNameSpace',
				'varCmsPageComments',
				'UserID',
				'intCommentBy',
				'varModuleTitle',
				'chrPublish',
				'chrDelete',
		];

		public static function get_comments($request) {
				$id = $request->id;
				$namespace = $request->namespace;
				$Comments = Self::Select('*')->where('intRecordID', $id)->where('varModuleNameSpace', $namespace)->deleted()->displayOrderBy('ASC')->get();
				return $Comments;
		}

		public static function get_commentDetailForNotificationById($id) {
				$response = false;
        $moduleFields = [
            'id',
            'Fk_ParentCommentId',
            'intRecordID',
            'fkMainRecord',
            'varModuleNameSpace',
            'varCmsPageComments',
            'UserID',
            'intCommentBy',
            'varModuleTitle',
            'chrPublish',
            'chrDelete',
            'created_at',
            'updated_at'
        ];
				$response = self::Select($moduleFields)
										->where('id', $id)
										->deleted()
										->first();
				return $response;
		}

		public static function get_usercomments($id) {
				$Comments_user = Self::Select('*')->where('Fk_ParentCommentId', $id)->deleted()->get();
				return $Comments_user;
		}

		public static function deleteComments($ids, $moduleNameSpace) {
			Self::whereIn('fkMainRecord', $ids)
			->orWhereIn('intRecordID', $ids)
			->where('varModuleNameSpace', $moduleNameSpace)
			->update(['chrPublish' => 'N','chrDelete' => 'Y']);
		}
		

		public static function insertComents($data) {
				Self::insertGetId($data);
		}

		function scopeDeleted($query) {
				$response = false;
				$response = $query->where(['chrDelete' => 'N']);
				return $response;
		}

		public function scopeOrWhereIn($query, $column, $values)
		{
		    return $this->whereIn($column, $values, 'or');
		}

		public function scopeDisplayOrderBy($query, $orderBy) {
				$response = false;
				$response = $query->orderBy('created_at', $orderBy);
				return $response;
		}
		
		
		

}
