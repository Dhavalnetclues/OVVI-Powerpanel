<?php
namespace App\Helpers;
class CategoryArrayBuilder {
	static function getArray($category,$isFilter=false,$isUserAccountRole=false){
		$response=false;
		$category_array=array();
			foreach ($category as $cat) {				
				if(isset($cat->parentCategory->varTitle)){
					$category_array[$cat->parentCategory->varTitle]['id']=$cat->parentCategory->id;
					$category_array[$cat->parentCategory->varTitle]['text']=$cat->parentCategory->varTitle;					
					$category_array[$cat->parentCategory->varTitle]['children'][] = array(
						'parentTitle'=>$cat->parentCategory->varTitle,						
						'id' => $cat->id,
						'text' => $cat->varTitle
					);
				}
				if(!isset($cat->parentCategory->id)){
					$category_array[] = array(
						'id' => $cat->id,
						'text' => $cat->varTitle						
					);
				}
			}

			$newObj=[];			
			foreach ($category_array as $key => $value) {
				if(isset($category_array[$key])){					
					array_push($newObj, $category_array[$key]);				
				}
			}

			if(!empty($newObj)){
				if(!$isFilter && !$isUserAccountRole){
//					$newObj[]=['id'=>'addCat', 'text'=>'Add Category'];
				}
				$response	= $newObj;			
			}			
		return $response;
	}
}