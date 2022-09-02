<?php

namespace Powerpanel\LiveUser\Exports;

use Powerpanel\LiveUser\Models\LiveUsers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;

class LiveUserExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        if (null !== Request::get('country')) {
            $country = Request::get('country');
        } else {
            $country = false;
        }
        if (null !== Request::get('startDate')) {
            $startDate = Request::get('startDate');
        } else {
            $startDate = false;
        }
        if (null !== Request::get('endDate')) {
            $endDate = Request::get('endDate');
        } else {
            $endDate = false;
        }
        if (Request::get('export_type') == 'selected_records') {
            if (null !== Request::get('block')) {
                $selectedIds = Request::get('block');
            } else {
                $selectedIds = false;
            }
            $arrResults = LiveUsers::getListForExport($selectedIds, $country, $startDate, $endDate);
        } else {
            $arrResults = LiveUsers::getListForExport(false, $country, $startDate, $endDate);
        }

        if (count($arrResults) > 0) {
            return view('liveuser::powerpanel.excel_format', ['liveUsers' => $arrResults]);
        }
    }

}
