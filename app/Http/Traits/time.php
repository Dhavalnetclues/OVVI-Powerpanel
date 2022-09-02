<?php
namespace App\Http\Traits;
use App\RecentUpdates;
trait time {
	static	function time_elapsed_string($ptime){
  	$etime = time() - $ptime;
    if($etime < 1){
      return 'Just Now';
    }
    $a = array( 
    						365 * 24 * 60 * 60  =>  'years',
                30 * 24 * 60 * 60  =>  'months',
                24 * 60 * 60  =>  'days',
                60 * 60  =>  'hours',
                60  =>  'mins'
              );
    $a_plural = array( 
    									'years'   => 'years',
                      'months'  => 'months',
                      'days'    => 'days',
                      'hours'   => 'hours',
                      'mins' => 'mins'
                		);
  	foreach ($a as $secs => $str){
    	$d = $etime / $secs;
    	if($d >= 1){
      	$r = round($d);
				return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);	
    	}
  	}
	}
}