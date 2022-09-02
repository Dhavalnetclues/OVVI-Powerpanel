<?php
/**
* This helper generates dynamic categories
* @package Netquick
* @version 1.00
* @since 2017-02-09
* @author Vishal Agrawal
*/
namespace App\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Input;
use App\Helpers\MyLibrary;

class Category_builder {
	static function Parentcategoryhierarchy($selected_id=false,$post_id=false,$modelNameSpace=false){
		$style = "style='display: none'";
		$dipnopar = "selected";

		if($modelNameSpace==false || $modelNameSpace==''){
				$modelNameSpace = MyLibrary::getModelNameSpace();	
		}
		$query = $modelNameSpace::getCategories();

		// if($post_id!=false){
		//  $query->notIdCheck($post_id);
		// }
		$query = $query->get();
	 
		$children = array();
		$pitems = array();
		foreach ($query as $row) {
			$pitems[] = $row;
		}
		if ($pitems) {
			foreach ($pitems as $p) {
				$pt = $p->intParentCategoryId;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push($list, $p);
				$children[$pt] = $list;
			}
		}
		$list = Self::treerecurse(0,'',array(),$children,5,0,0);
		
		$output = '<select class="form-control" data-show-subtext="true" size="10" name="parent_category_id" id="parent_category_id" >';
		$output .="<option value=\"0\" " . (($selected_id == 0) ? $dipnopar : '') . ">No Parent Category</option>";
		$temp1 = "";
		$temp = "";
		$disabled = "";
		$tempfk = "";
		foreach ($list as $item) {
			if ($post_id == '') {
				$disabled = '';
			} else if ($item->id == $post_id || $item->intParentCategoryId == $post_id ) {
				$disabled = " disabled='disabled' ";
				$temp1 = $item->id;
			} else if ($item->intParentCategoryId == $temp1) {
				$temp = $item->id;
				$tempfk = $item->intParentCategoryId;
			} else {
				$disabled = '';
			}
			if($item->endDepthlevel=="Yes"){
					$disabled = " disabled='disabled' ";
			}
			$output.="<option value=" . $item->id . " " . (($item->id == $selected_id) ? 'selected' : '') . " " . $disabled . " >" . htmlspecialchars(html_entity_decode($item->treename)) ."</option>";
		}
		
		$output .="</select>";
		return $output;	
		
	}
	static function treerecurse($id,$indent,$list=Array(),$children=Array(),$maxlevel='10',$level=0,$type=1) {
		if (isset($children[$id]) ) {
			if ($children[$id] && $level <= $maxlevel) {
				foreach ($children[$id] as $c) {
					$id = $c->id;
					if ($type) {
						$pre = '<sup>|_</sup>&nbsp;';
						$spacer = '.&nbsp;&nbsp;&nbsp;';
					} else {
						$pre = '|_ ';
						$spacer = '&nbsp;&nbsp;&nbsp;';
					}
					if ($c->intParentCategoryId == 0) {
						$txt = $c->varTitle;
					} else {
						$txt = $pre . $c->varTitle;
					}
					$pt = $c->intParentCategoryId;
					$list[$id] = $c;
					$list[$id]->treename = "$indent$txt";
					$list[$id]->endDepthlevel = "No";
					if($level == $maxlevel){
							$list[$id]->endDepthlevel = "Yes";
					}
					if(isset($list[$id]) && isset($list[$id]->children)){
						$list[$id]->children = count($children[$id]);
					}
					$list = Self::treerecurse($id,$indent . $spacer,$list,$children,$maxlevel,$level + 1,$type);
				}
			}
		}
		return $list;
	}
}