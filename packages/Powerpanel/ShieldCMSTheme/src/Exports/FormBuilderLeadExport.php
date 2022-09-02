<?php
namespace App\Exports;
use App\FormBuilderLead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;

class FormBuilderLeadExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        if (Request::get('export_type') == 'selected_records') {
            $selectedIds = array();
            if (null !== Request::get('delete')) {
                $selectedIds = Request::get('delete');
            }
            $arrResults = FormBuilderLead::getListForExport($selectedIds);
            
        } else {
            $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
            $arrResults = FormBuilderLead::getListForExport();
        }

        if (count($arrResults) > 0) {
            return view('powerpanel.formbuilder_lead.excel_format', ['FormBuilderLead' => $arrResults]);
        }
    }

}
