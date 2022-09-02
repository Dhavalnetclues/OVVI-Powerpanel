<?php

/**
 * This helper generates dynamic categories
 * @package Netquick
 * @version 1.00
 * @since 2017-02-09
 * @author Netquick Team
 */

namespace App\Helpers;

use App\Alias;
use App\Helpers\MyLibrary;
use Config;

class ParentRecordHierarchy_builder
{

    public static function Parentrecordhierarchy($selected_id = false, $post_id = false, $modelNameSpace = false)
    {
        $style = "style='display: none'";
        $dipnopar = "selected";
        // $notSelected = "";
        // $id = Request::segment(3);
        //        if ($modelNameSpace == false || $modelNameSpace == '') {
        //            $modelNameSpace = MyLibrary::getModelNameSpace();
        //        }
        if (Config::get('Constant.MODULE.NAME_SPACE') != '') {
            $modelNameSpace = Config::get('Constant.MODULE.NAME_SPACE') . 'Models\\' . Config::get('Constant.MODULE.MODEL_NAME');
        } else {
            $modelNameSpace = MyLibrary::getModelNameSpace();
        }
        $query = $modelNameSpace::getRecordsForHierarchy();
        $query = $query->get();

        $stringIds = array();
        $children = array();
        $onlyParentIds = array();
        $minIds = array();
        $sector = array();

        foreach ($query as $element) {
            $stringIds[] = $element->id;
            $stringIds[] = $element->intParentCategoryId;
            $onlyParentIds[] = $element->intParentCategoryId;
            $sector[] = $element->varSector;
        }
        $stringIds = array_unique($stringIds);

        $fetchData = $modelNameSpace::getRecordListforSelectBoxbyIds($stringIds);

        $children = array();

        if (!empty($fetchData)) {
            foreach ($fetchData as $p) {
                $pt = $p->intParentCategoryId;
                $list = isset($children[$pt]) ? $children[$pt] : array();
                array_push($list, $p);
                $children[$pt] = $list;
            }
        }

        $list = Self::treerecurse(0, '', array(), $children, 10, 0, 0);
        $output = '<select class="form-control" data-show-subtext="true" size="10" name="parent_category_id" id="parent_category_id" data-choices>';
        if ((Config::get('Constant.MODULE.NAME') != null) && Config::get('Constant.MODULE.NAME') == "organizations") {
            $noparentTitle = "No Parent Organization";
        } else if ((Config::get('Constant.MODULE.NAME') != null) && Config::get('Constant.MODULE.NAME') == "numismatic-coins") {
            $noparentTitle = "No Parent Numismatic Coin";
        } else {
            $noparentTitle = 'No Parent Category';
        }

        // if (is_numeric($id)){
        $output .= "<option value=\"\" " . (($selected_id == 0) ? $dipnopar : '') . ">" . $noparentTitle . "</option>";
        // }else{
        //     $output .= "<option value=\"0\" " . (($selected_id == 0) ? $notSelected : '') . ">" . $noparentTitle . "</option>";
        // }

        $sector="";

        $temp1 = "";
        $temp = "";
        $disabled = "";
        $tempfk = "";
        foreach ($list as $item) {
            if ($post_id == '') {
                $disabled = '';
            } else if ($item->id == $post_id || $item->intParentCategoryId == $post_id) {
                $disabled = " disabled='disabled' ";
                $temp1 = $item->id;
            } else if ($item->intParentCategoryId == $temp1) {
                $temp = $item->id;
                $tempfk = $item->intParentCategoryId;
            } else {
                $disabled = '';
            }
            if ($item->endDepthlevel == "Yes") {
                $disabled = " disabled='disabled' ";
            }
            $sectorName = '';
            if(isset($item->varSector) && $item->varSector != ''){
                $sectorName = '('.strtoupper($item->varSector).')';
            }
            $output .= "<option value=" . $item->id . " " . (($item->id == $selected_id) ? 'selected' : '') . " " . $disabled . " >" . htmlspecialchars(html_entity_decode($item->treename)) . " " . '<div style="font-weight:800;">' . $sectorName ."</div>" .  "</option>";
        }

        $output .= "</select>";
        return $output;
    }

    public static function treerecurse($id, $indent, $list = array(), $children = array(), $maxlevel = '10', $level = 0, $type = 1)
    {
        if (isset($children[$id])) {
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
                    if ($level == $maxlevel) {
                        $list[$id]->endDepthlevel = "Yes";
                    }
                    if (isset($list[$id]) && isset($list[$id]->children)) {
                        $list[$id]->children = count($children[$id]);
                    }
                    $list = Self::treerecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
                }
            }
        }
        return $list;
    }

    public static function Parentrecordhierarchy_singleselectForListFilter($selected_id = false, $post_id = false, $modelNameSpace = false)
    {
        $style = "style='display: none'";
        $dipnopar = "selected";

        if ($modelNameSpace == false || $modelNameSpace == '') {
            $modelNameSpace = MyLibrary::getModelNameSpace();
        }
//        if (Config::get('Constant.MODULE.NAME_SPACE') != '') {
        //            $modelNameSpace = Config::get('Constant.MODULE.NAME_SPACE') . 'Models\\' . Config::get('Constant.MODULE.MODEL_NAME');
        //        } else {
        //            $modelNameSpace = MyLibrary::getModelNameSpace();
        //        }
        $query = $modelNameSpace::getRecordsForHierarchy();
        $query = $query->get();

        $stringIds = array();
        $children = array();
        $onlyParentIds = array();
        $minIds = array();

        foreach ($query as $element) {
            $stringIds[] = $element->id;
            $stringIds[] = $element->intParentCategoryId;
            $onlyParentIds[] = $element->intParentCategoryId;
        }
        $stringIds = array_unique($stringIds);

        $fetchData = $modelNameSpace::getRecordListforSelectBoxbyIds($stringIds);

        $children = array();

        if (!empty($fetchData)) {
            foreach ($fetchData as $p) {
                $pt = $p->intParentCategoryId;
                $list = isset($children[$pt]) ? $children[$pt] : array();
                array_push($list, $p);
                $children[$pt] = $list;
            }
        }

        $list = Self::treerecurse_singleselectforlistfilter(0, '', array(), $children, 10, 0, 0);

        $output = '<select class="form-control" data-show-subtext="true" size="10" name="category_id" id="category_id" data-choices>';
        $output .= '<option value="">' . trans('template.common.selectcategory') . '</option>';

        $temp1 = "";
        $temp = "";
        $disabled = "";
        $tempfk = "";
        foreach ($list as $item) {
            if ($post_id == '') {
                $disabled = '';
            } else if ($item->id == $post_id || $item->intParentCategoryId == $post_id) {
                $temp1 = $item->id;
            } else if ($item->intParentCategoryId == $temp1) {
                $temp = $item->id;
                $tempfk = $item->intParentCategoryId;
            } else {
                $disabled = '';
            }
            if ($item->endDepthlevel == "Yes") {

            }
            $output .= "<option value=" . $item->id . " " . (($item->id == $selected_id) ? 'selected' : '') . " " . $disabled . " >" . htmlspecialchars(html_entity_decode($item->treename)) . "</option>";
        }

        $output .= "</select>";
        return $output;
    }

    public static function treerecurse_singleselectforlistfilter($id, $indent, $list = array(), $children = array(), $maxlevel = '10', $level = 0, $type = 1)
    {
        if (isset($children[$id])) {
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
                    if ($level == $maxlevel) {
                        $list[$id]->endDepthlevel = "Yes";
                    }
                    if (isset($list[$id]) && isset($list[$id]->children)) {
                        $list[$id]->children = count($children[$id]);
                    }
                    $list = Self::treerecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
                }
            }
        }
        return $list;
    }

    public static function Parentrecordhierarchy_singleselectTypeArr($selected_id = false, $post_id = false, $modelNameSpace = false, $admin = false)
    {
        $style = "style='display: none'";
        $dipnopar = "selected";

        if ($modelNameSpace == false || $modelNameSpace == '') {
            $modelNameSpace = MyLibrary::getModelNameSpace();
        }

        $query = $modelNameSpace::getRecordsForHierarchy();
        $query = $query->get();

        $stringIds = array();
        $children = array();
        $onlyParentIds = array();
        $minIds = array();

        foreach ($query as $element) {
            $stringIds[] = $element->id;
            $stringIds[] = $element->intParentCategoryId;
            $onlyParentIds[] = $element->intParentCategoryId;
        }
        $stringIds = array_unique($stringIds);

        $fetchData = $modelNameSpace::getRecordListforSelectBoxbyIds($stringIds, $admin);

        $children = array();

        if (!empty($fetchData)) {
            foreach ($fetchData as $p) {
                $pt = $p->intParentCategoryId;
                $list = isset($children[$pt]) ? $children[$pt] : array();
                array_push($list, $p);
                $children[$pt] = $list;
            }
        }

        $list = Self::treerecurse_singleselectTypeArr(0, '', array(), $children, 10, 0, 0);

        $output = '<select class="form-select" name="category_id" id="category_id">';
        $output .= '<option value="" >' . trans('template.common.selectcategory') . '</option>';

        $temp1 = "";
        $temp = "";
        $disabled = "";
        $tempfk = "";
        foreach ($list as $item) {
            if ($post_id == '') {
                $disabled = '';
            } else if ($item->id == $post_id || $item->intParentCategoryId == $post_id) {
                $temp1 = $item->id;
            } else if ($item->intParentCategoryId == $temp1) {
                $temp = $item->id;
                $tempfk = $item->intParentCategoryId;
            } else {
                $disabled = '';
            }
            if ($item->endDepthlevel == "Yes") {

            }
            $CategoryAlias = "";
            if (isset($item->intAliasId)) {
                if (method_exists(new Alias(), 'getAliasbyID')) {
                    $Category_Alias = Alias::getAliasbyID($item->intAliasId);
                }
                if (isset($Category_Alias->varAlias)) {
                    $CategoryAlias = $Category_Alias->varAlias;
                }
            }

            if (!empty($selected_id)) {
                if (is_array($selected_id)) {
                    $output .= "<option data-categryalias='" . $CategoryAlias . "' value=" . $item->id . " " . ((in_array($item->id, $selected_id)) ? 'selected' : '') . " " . $disabled . " >" . htmlspecialchars(html_entity_decode($item->treename)) . "</option>";
                } else {
                    $output .= "<option data-categryalias='" . $CategoryAlias . "' value=" . $item->id . " " . (($item->id == $selected_id) ? 'selected' : '') . " " . $disabled . " >" . htmlspecialchars(html_entity_decode($item->treename)) . "</option>";
                }
            } else {
                $output .= "<option data-categryalias='" . $CategoryAlias . "' value=" . $item->id . " " . $disabled . " >" . htmlspecialchars(html_entity_decode($item->treename)) . "</option>";
            }
        }

        $output .= "</select>";
        return $output;
    }

    public static function treerecurse_singleselectTypeArr($id, $indent, $list = array(), $children = array(), $maxlevel = '10', $level = 0, $type = 1)
    {
        if (isset($children[$id])) {
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
                    if ($level == $maxlevel) {
                        $list[$id]->endDepthlevel = "Yes";
                    }
                    if (isset($list[$id]) && isset($list[$id]->children)) {
                        $list[$id]->children = count($children[$id]);
                    }
                    $list = Self::treerecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
                }
            }
        }
        return $list;
    }
    public static function Parentrecordhierarchy_frontArr($modelNameSpace = false, $sector_slug, $content)
    {

        $style = "style='display: none'";
        $dipnopar = "selected";

        if ($modelNameSpace == false || $modelNameSpace == '') {
            $modelNameSpace = MyLibrary::getModelNameSpace();
        }

        $query = $modelNameSpace::getRecordsForHierarchy();
        $query = $query->get();

        $stringIds = array();
        $children = array();
        $onlyParentIds = array();
        $minIds = array();

        foreach ($query as $element) {
            $stringIds[] = $element->id;
            $stringIds[] = $element->intParentCategoryId;
            $onlyParentIds[] = $element->intParentCategoryId;
        }
        $stringIds = array_unique($stringIds);

        $fetchData = $modelNameSpace::getFrontRecordListforSelectBoxbyIds($stringIds, $sector_slug);

        $children = array();

        if (!empty($fetchData)) {
            foreach ($fetchData as $p) {
                $pt = $p->intParentCategoryId;
                $list = isset($children[$pt]) ? $children[$pt] : array();
                array_push($list, $p);
                $children[$pt] = $list;
            }
        }

        $list = Self::treerecurse_frontArr(0, '', array(), $children, 10, 0, 0);

        $output = ' <select class="form-control selectpicker ac-input" data-width="100%" title="Sort by Category" id="categoryFilter" data-size="5" data-live-search="true" data-choices>';
        $output .= '<option value="" >' . trans('All') . '</option>';

        $temp1 = "";
        $temp = "";
        $disabled = "";
        $tempfk = "";

        foreach ($list as $item) {

            $disabled = '';

            if ($item->endDepthlevel == "Yes") {

            }
            $CategoryAlias = "";
            if (isset($item->intAliasId)) {
                if (method_exists(new Alias(), 'getAliasbyID')) {
                    $Category_Alias = Alias::getAliasbyID($item->intAliasId);
                }
                if (isset($Category_Alias->varAlias)) {
                    $CategoryAlias = $Category_Alias->varAlias;
                }
            }

            $cont = json_decode($content);
           $count = count(array($cont));
            $selected = '';
            for($i=0; $i<$count;$i++){
            if (isset($cont) && isset($cont[$i]->val->publicationscat) != '') {
                if ($item->id == $cont[$i]->val->publicationscat) {
                    $selected = 'selected';
                }
            }
            }
            $cClass = '';
            if ($item->intParentCategoryId > 0) {
                $cClass = '-submenu';
            }

            $output .= "<option class='" . $cClass . "'  value=" . $item->id . " " . $disabled . " " . $selected . " >" . htmlspecialchars(html_entity_decode($item->treename)) . "</option>";

        }

        $output .= "</select>";
        return $output;
    }

    public static function treerecurse_frontArr($id, $indent, $list = array(), $children = array(), $maxlevel = '10', $level = 0, $type = 1)
    {
        if (isset($children[$id])) {
            if ($children[$id] && $level <= $maxlevel) {
                foreach ($children[$id] as $c) {
                    $id = $c->id;
                    if ($type) {
                        $pre = '<sup>|_</sup>&nbsp;';
                        $spacer = '.&nbsp;&nbsp;&nbsp;';
                    } else {
                        $pre = '';
                        $spacer = '';
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
                    if ($level == $maxlevel) {
                        $list[$id]->endDepthlevel = "Yes";
                    }
                    if (isset($list[$id]) && isset($list[$id]->children)) {
                        $list[$id]->children = count($children[$id]);
                    }
                    $list[$id]->intParentCategoryId = $c->intParentCategoryId;

                    $list = Self::treerecurse_frontArr($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
                }
            }
        }
        return $list;
    }

    public static function Hierarchy_OnlyOptionsForQlinks($moduleRec = false, $module = false, $selected_id = false)
    {
        $style = "style='display: none'";
        $dipnopar = "selected";

        $stringIds = array();
        $children = array();
        $onlyParentIds = array();
        $minIds = array();

        $onlyRecords = array();
        foreach ($moduleRec as $element) {
            $stringIds[] = $element->id;
            $stringIds[] = $element->intParentCategoryId;
            $onlyParentIds[] = $element->intParentCategoryId;
            $onlyRecords[$element->id] = $element;
        }
        $stringIds = array_unique($stringIds);
        $fetchData = array();
        foreach ($stringIds as $strid) {
            if ($strid != "") {
                $fetchData[] = $onlyRecords[$strid];
            }
        }

        $children = array();

        if (!empty($fetchData)) {
            foreach ($fetchData as $p) {
                $pt = $p->intParentCategoryId;
                $list = isset($children[$pt]) ? $children[$pt] : array();
                array_push($list, $p);
                $children[$pt] = $list;
            }
        }

        $list = Self::treerecurse_OnlyOptionsForQlinks(0, '', array(), $children, 10, 0, 0);

        $temp1 = "";
        $temp = "";
        $disabled = "";
        $tempfk = "";
        $output = "";
        $optselected = "";
        foreach ($list as $item) {
            if ($item->id == $selected_id) {
                $optselected = "selected";
            } else {
                $optselected = "";
            }
            $output .= "<option value=" . $item->id . " data-moduleid=" . $module->id . " " . $optselected . ">" . htmlspecialchars(html_entity_decode($item->treename)) . "</option>";
        }
        return $output;
    }

    public static function treerecurse_OnlyOptionsForQlinks($id, $indent, $list = array(), $children = array(), $maxlevel = '10', $level = 0, $type = 1)
    {
        if (isset($children[$id])) {
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
                    if ($level == $maxlevel) {
                        $list[$id]->endDepthlevel = "Yes";
                    }
                    if (isset($list[$id]) && isset($list[$id]->children)) {
                        $list[$id]->children = count($children[$id]);
                    }
                    $list = Self::treerecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
                }
            }
        }
        return $list;
    }

}
