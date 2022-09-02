<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\GeneralSettings;
use App\Helpers\DateFormater;
use Illuminate\Support\Facades\Storage;
use Config;

class GeneralSettingsConstants
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  $guard
   * @return mixed
   */
  public function handle($request, Closure $next)
  {   
     $arrSettings = Storage::disk('public')->get('general_settings.json');
     if(!empty($arrSettings))
      {
        foreach (json_decode($arrSettings,true) as $key => $row)
        {
          if ($row['fieldName']=="DEFAULT_DATE_FORMAT"){
            Config::set('Constant'.$row['fieldName'].'',DateFormater::fixDateFormat($row['fieldValue']));
            Config::set('Constant.DEFAULT_DATE',DateFormater::fixDateFormat($row['fieldValue']));
          }
            Config::set('Constant.'.$row['fieldName'].'',$row['fieldValue']);        
          }  
      }      
      return $next($request);
  }
  
}
