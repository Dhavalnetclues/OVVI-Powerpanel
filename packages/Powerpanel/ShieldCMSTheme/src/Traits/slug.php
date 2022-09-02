<?php
namespace App\Http\Traits;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use App\Alias;
trait slug {
	static function create_slug($title = false)
	{
			$response = null;
			if($title != null){
				$response = SlugService::createSlug(Alias::class, 'varAlias', $title);
				$response = explode(':', $response);
			}	
			return $response;
	}
	static function resolve_alias($alias)
	{
		$response = null;
			try {
					$objResult = Alias::getAlias($alias);
					if(!empty($objResult)){	
						$response = $objResult->id;
					}else{						
						abort(404);
					}
			} catch(Exception $e) {				
					abort(403, $e->getMessage().PHP_EOL);
			}
			
			return $response;
	}
	static function resolve_alias_for_routes($alias)
	{
			$response = null;
			$objResult = Alias::getAlias($alias);
			if(!empty($objResult))
			{	
					$response = $objResult->id;
			}else{
					$response = $alias;
			}			
			return $response;
	}
}