<?php
namespace Powerpanel\Referearn\Models;
use Powerpanel\Referearn\Models\Referearn;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;

class ReferearnExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        if (Request::get('export_type') == 'selected_records') {
            if (null !== Request::get('delete')) {
                $selectedIds = Request::get('delete');
            } else {
                $selectedIds = false;
            }
            //$filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
            $arrResults = Referearn::getListForExport($selectedIds);
        } else {
            $arrResults = Referearn::getListForExport();
        }

        if (count($arrResults) > 0) {
            return view('referearnlead::powerpanel.excel_format', ['referearnLeads' => $arrResults]);
        }
    }

}
