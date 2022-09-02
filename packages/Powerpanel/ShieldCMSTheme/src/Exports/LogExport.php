<?php
namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use App\Log;
use Config;
class LogExport implements FromView, ShouldAutoSize
{
	public function view(): View
	{
		$postArr = Request::all();
		if (Request::get('export_type') == 'selected_records') {
				$selectedIds = '';
				if (null !== Request::get('delete')) {
						$selectedIds = Request::get('delete');
				}
				$arrResults = Log::getListForExport($selectedIds);
		} else {
				if (isset($postArr['rid']) && isset($postArr['mid'])) {
						$arrResults = Log::getListForExport(false, $postArr['mid'], $postArr['rid']);
				} else {
						$arrResults = Log::getListForExport();
				}
		}
		
		if (count($arrResults) > 0) {
				return view('shiledcmstheme::powerpanel.logmanager.excel_format', ['logsLeads' => $arrResults]);
		}
	}
}