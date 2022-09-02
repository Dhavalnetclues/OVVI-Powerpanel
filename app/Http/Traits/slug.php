<?php

namespace App\Http\Traits;

use \Cviebrock\EloquentSluggable\Services\SlugService;
use App\Alias;
use Throwable;

trait slug
{
	static function create_slug($title = false, $unique = true)
	{
		$response = null;
		if ($title != null) {
			$response = SlugService::createSlug(Alias::class, 'varAlias', $title, ['unique' => $unique]);
			$response = explode(':', $response);
		}
		return $response;
	}
	
	static function resolve_alias($alias, $sector = false, $moduleID = false) {
		$response = null;
		//try {
			$objResult = Alias::getAlias($alias, $sector, $moduleID);
			if (!empty($objResult)) {
				$response = $objResult->id;
			} else {
				abort(404);
			}
		// } catch (Throwable $e) {
		// 	abort(403, $e->getMessage() . PHP_EOL);
		// }

		return $response;
	}

	static function resolve_alias_for_routes($alias, $sector = false)
	{
		$response = null;
		$objResult = Alias::getAliasforCMS($alias, $sector);
		if (!empty($objResult)) {
			$response = $objResult->id;
		} else {
			$response = $alias;
		}
		return $response;
	}
}
