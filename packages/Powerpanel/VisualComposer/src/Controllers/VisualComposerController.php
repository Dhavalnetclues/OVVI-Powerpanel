<?php

namespace Powerpanel\VisualComposer\Controllers;

use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use DB;
use Request;
use Illuminate\Support\Facades\View;
use Powerpanel\VisualComposer\Models\VisualComposer;

class VisualComposerController extends PowerpanelController
{

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public static function get_dialog_maker()
    {
        // $ModuleId = '';
        // if (Request::has('searchValue')) {
        //     $moduleId = Request::get('searchValue');
            
        //     if(isset($moduleId) && !empty($moduleId)){
        //         $ModuleId = $moduleId;
        //     }
        // }

        $visualData = VisualComposer::where('fkParentID', 0)->orderBy('varTitle')->where('chrPublish','Y')->get()->toArray();
        
        foreach ($visualData as $key => $data) {

            if ($data['fkParentID'] == '0' && $data['varTitle'] == 'All') {
                $visualData[$key]['child'] = VisualComposer::where('fkParentID','<>', 0)->orderBy('varTitle')->where('chrPublish','Y')->get()->toArray();

            } else if ($data['fkParentID'] == '0' && $data['varTitle'] == 'Templates') {
                $children = [];
                $myLibrary = new MyLibrary;
                if (method_exists($myLibrary, 'GetTemplateData')) {
                    $tempaletData = MyLibrary::GetTemplateData();
                    if (!empty($tempaletData)) {
                        foreach ($tempaletData as $tdata) {
                            array_push($children, [
                                'id' => $tdata->id,
                                'varTitle' => $tdata->varTemplateName,
                                'varClass' => '',
                                'varIcon' => 'fa fa-align-justify',
                                'fkParentID' => $data['id'],
                                'varTemplateName' => '',
                                'varModuleName' => '',
                            ]);
                        }
                    }
                }
                $visualData[$key]['child'] = $children;
            } else if ($data['fkParentID'] == '0' && $data['varTitle'] == 'Forms') {
                $myLibrary = new MyLibrary;
                $formChildren = [];
                if (method_exists($myLibrary, 'GetFormBuilderData')) {
                    $FormBuilderData = MyLibrary::GetFormBuilderData();
                    if (!empty($FormBuilderData)) {
                        foreach ($FormBuilderData as $fdata) {
                            array_push($formChildren, [
                                'id' => $fdata->id,
                                'varTitle' => $fdata->varName,
                                'varClass' => '',
                                'varIcon' => 'fa fa-file-text-o',
                                'fkParentID' => $data['id'],
                                'varTemplateName' => '',
                                'varModuleName' => '',
                            ]);
                        }
                    }
                }  
                $visualData[$key]['child'] = $formChildren;
            } else {
                $visualData[$key]['child'] = VisualComposer::where('fkParentID', $data['id'])->where('chrPublish','Y')->orderBy('varTitle')->get()->toArray();
            }
        }

        if(!empty($visualData))
        {
            foreach ($visualData as $key => $data) {
                if (!empty($data['varModuleID']) && $data['varModuleID'] != 0) {
                    $moduleData = DB::table('module')->select('varModuleName')->where('id', $data['varModuleID'])->first();
                    if (isset($moduleData->varModuleName) && !empty($moduleData->varModuleName)) {
                        $visualData[$key]['varModuleName'] = $moduleData->varModuleName;
                    }
                }
                foreach ($data['child'] as $index => $child) {
                    if (!empty($child['varModuleID']) && $child['varModuleID'] != 0) {
                        $childModuleData = DB::table('module')->select('varModuleName')->where('id', $child['varModuleID'])->first();
                        if (isset($childModuleData->varModuleName) && !empty($childModuleData->varModuleName)) {
                            $visualData[$key]['child'][$index]['varModuleName'] = $childModuleData->varModuleName;
                        }
                    }
                }
                
            }
        }

        $visualComposerTemplate = array();
        if(!empty($visualData)){
            foreach ($visualData as $key => $data) {
                if(isset($data['varTitle']) && $data['varTitle'] == 'All') {
                    if(isset($data['child']) && !empty($data['child'])) {
                        foreach($data['child'] as $ckey => $cval) 
                        {
                            if (!empty($cval['varTemplateName'])) {
                                array_push($visualComposerTemplate, $cval['varTemplateName']);
                            }
                        }
                    }
                } 
            }
        }

        
       
        // $view = View::make('visualcomposer::dialog-maker')->with('visualData', $visualData)->with('visualComposerTemplate', $visualComposerTemplate)->with('ModuleId', $ModuleId);
        $view = View::make('visualcomposer::dialog-maker')->with('visualData', $visualData)->with('visualComposerTemplate', $visualComposerTemplate);    
        
        echo $view;
    }

    public static function page_section($section)
    {
        $MyLibrary = new MyLibrary();
        $view = View::make('visualcomposer::page-sections')->with($section)->with('MyLibrary', $MyLibrary);
        echo $view;
    }

    public static function get_builder_css_js()
    {
        $view = View::make('visualcomposer::builder-js-css');
        echo $view;
    }

    public static function get_visual_checkEditor()
    {
        $view = View::make('visualcomposer::visualckeditor');
        echo $view;
    }
}
