<?php
namespace App\Helpers;
use App\CommonModel;
use Validator;
use App\Helpers\ParentRecordHierarchy_builder;
use App\Helpers\CategoryArrayBuilder;

class AddQuickCategoryAjax {
	static function Add($data, $module){
		$response=false;
		$varTitle = trim($data['varTitle']);
		$selectedCat=$data['selectedCat'];
		$parentCategory = $data['parent_category_id']; 
		$rules = ['varTitle' => 'required|max:160'];
		$validator = Validator::make($data, $rules);
		if ($validator->passes()) {
			$total = CommonModel::getRecordCount();
			$order = $total + 1;
			$newCategoryID = CommonModel::addRecord([
				'varTitle'=>$varTitle,
				'intAliasId' => MyLibrary::insertAlias(preg_replace('/[^A-Za-z0-9\-]/', '-', $varTitle)),
				'intParentCategoryId'=> $parentCategory,
				'txtShortDescription'=>$varTitle,
				'intDisplayOrder' => $order,
				'chrPublish'=>'Y',
				'chrDelete'=>'N'
			]);
		}
		array_push($selectedCat, (string)$newCategoryID);
		$module = '\\App\\' . $module;
		$category = $module::getCatWithParent();
		$category = CategoryArrayBuilder::getArray($category);		
		$MainMenuCategory = json_encode($category);

		$categoriesHtml=Category_builder::Parentcategoryhierarchy(false,false,$module);

		$response['cat'] = $MainMenuCategory;
		$response['selected'] = $selectedCat;
		$response['categoriesHtml'] = $categoriesHtml;
		$response=json_encode($response);	

		return $response;
	}

	static function AddSimple($data, $module){
		$response=false;
		$moduleNameSpace = '\\App\\' . $module;

		$varTitle = trim($data['varTitle']);
		$selectedCat=$data['selectedCat'];
		$parentCategory = $data['parent_category_id']; 
		$rules = ['varTitle' => 'required|max:160'];
		$validator = Validator::make($data, $rules);
		if ($validator->passes()) {
			$total = $moduleNameSpace::getRecordCounter();
			$order = $total + 1;
			$order = self::newDisplayOrderAdd($order,$data['parent_category_id'],$moduleNameSpace);
			$newCategoryID = CommonModel::addRecord([
				'varTitle'=>$varTitle,
				'intAliasId' => MyLibrary::insertAlias(preg_replace('/[^A-Za-z0-9\-]/', '-', $varTitle)),
				'intParentCategoryId'=> $parentCategory,
				'intDisplayOrder' => $order,
				'chrPublish'=>'Y',
				'chrDelete'=>'N',
				'chrMain' =>'Y'
			]);
		}
		array_push($selectedCat, (string)$newCategoryID);
		
		$category = $moduleNameSpace::getCatWithParent();
		$category = CategoryArrayBuilder::getArray($category);		
		$MainMenuCategory = json_encode($category);

		$categoriesHtml=ParentRecordHierarchy_builder::Parentrecordhierarchy(false,false,$moduleNameSpace);

		$response['cat'] = $MainMenuCategory;
		$response['selected'] = $selectedCat;
		$response['categoriesHtml'] = $categoriesHtml;
		$response=json_encode($response);	

		return $response;
	}

	/**
	 * This method handels swapping of available order record while adding new function
	 * @param      order
	 * @return  order
	 * @since   2016-10-21
	 * @author  NetQuick
	 */
	public static function newDisplayOrderAdd($order = null,$parentRecordId=false,$moduleNameSpace = false)
	{

		$response       = false;
		if($parentRecordId > 0){
				$order = $moduleNameSpace::getRecordCounter($parentRecordId);
				$order = $order + 1;
		}

		$response = (int) $order;
		return $response;
	}

}