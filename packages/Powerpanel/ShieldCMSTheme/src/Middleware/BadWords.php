<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Offensiveword;
use App\GeneralSettings;

class BadWords
{
		/**
		 * Handle an incoming request.
		 *
		 * @param  \Illuminate\Http\Request  $request
		 * @param  \Closure  $next
		 * @return mixed
		 */

		public function handle($request, Closure $next)
		{ 
			

			$data = GeneralSettings::where('fieldName','=','BAD_WORDS')->first();			
			if(isset($data->fieldValue) && $data->fieldValue == 'Y')
			{
				$arrPost= $request->input();
				foreach ($arrPost as $key=>$input) {
					if(is_array($input)){           
						foreach ($input as $keys=>$inputs) {
							if(!in_array($key, array('_token','alias'))){           
								$filterData[$key][$keys] = OffensiveWord::censor($inputs);           
							}else{
								$filterData[$key][$keys] =$inputs;  
							}
						}
					}else{
						if(!in_array($key, array('_token','alias'))){
							 $filterData[$key] = OffensiveWord::censor($input);							 
						}
						else{
							$filterData[$key] =$input;  
						}
					}
				}
				
				if(isset($filterData)){
					$request->replace($filterData);
				}

				$arrPost=$request->input();
				if(array_key_exists('phone_number', $arrPost)){
					$check=preg_match("/[_A-Za-z]/", $arrPost['phone_number']);
					if($check){
						unset($arrPost['phone_number']);
						$request->replace($arrPost);
					}
				}


			}     
			return $next($request);
		}
}
