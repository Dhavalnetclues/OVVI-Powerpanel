<?php
namespace Netclues\Themes;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\FileViewFinder;

class ThemeServiceProvider extends ServiceProvider
{

		public function register()
	  {
	  	$this->registerViewFinder();
	  }

    public function registerViewFinder() {
    $this->app->bind('view.finder', function($app) {
			$themeConfig = \config('theme');
			$activeThemePath = $themeConfig['base_path']."/".$themeConfig['active']."/views";
			$paths = realpath($activeThemePath)?[realpath($activeThemePath)] : $app['config']['view.paths'];
			
			foreach ($app['config']['view.paths'] as $path) {
				if(!in_array($path, $paths)){
					$paths[]=$path;
				}
			}

			//$views = new FileViewFinder($app['files'], $paths);
			return new FileViewFinder($app['files'], $paths);
		});
	}
}