<?php
namespace App\Helpers;
use App\CommonModel;
use Validator;

class AddCategoryAjax {
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
				//'txtShortDescription'=>$varTitle,
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

}