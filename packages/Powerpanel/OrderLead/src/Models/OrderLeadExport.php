<?php
namespace Powerpanel\FormBuilderLead\Models;
use Powerpanel\FormBuilderLead\Models\OrderLead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;

class OrderLeadExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        if (Request::get('export_type') == 'selected_records') {
            $selectedIds = array();
            if (null !== Request::get('delete')) {
                $selectedIds = Request::get('delete');
            }
            $arrResults = OrderLead::getListForExport($selectedIds);
            
        } else {
            $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
            $arrResults = OrderLead::getListForExport();
        }

        if (count($arrResults) > 0) {
            return view('orderlead::powerpanel.excel_format', ['OrderLead' => $arrResults]);
        }
    }

}
