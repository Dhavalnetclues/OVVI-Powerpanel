<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BannerTableSeeder extends Seeder
{
		public function run()
		{			
			
												
						
								$fkIntImgId = DB::table('image')->select('id')->where('txtImageName','theme_01_banner_01_01')->first();

								DB::table('banner')->insert([
									'fkIntImgId' => $fkIntImgId->id,
                                                                        'fkIntPageId' => '1',
                                                                        'varBannerType' => 'home_banner',
									'varTitle' => 'NetQuick!',
									'varSubTitle' =>  "Easy to use html",					
									'intDisplayOrder' => 1,
									'txtDescription' => "NetQuick! &lt;p&gt; Using Library, Components, Utilities, Custom Plugin and latest other featured. &lt;/p&gt;",
                                                                        'chrMain' =>'Y',
									'chrPublish' => 'Y',
									'chrDelete'=> 'N',
									'created_at'=> Carbon::now(),
									'updated_at'=> Carbon::now()
								]);
							
						
								
					
				
						
								$fkIntImgId = DB::table('image')->select('id')->where('txtImageName','theme_01_banner_01_02')->first();

								DB::table('banner')->insert([
									'fkIntImgId' => $fkIntImgId->id,
                                                                        'fkIntPageId' => '1',
                                                                        'varBannerType' => 'home_banner',
									'varTitle' => 'NetQuick!',
									'varSubTitle' =>  "We have used latest technology.",					
									'intDisplayOrder' => 2,
									'txtDescription' => "NetQuick! &lt;p&gt; HTML5, Laravel, SCSS, CSS, CSS3, Java Script, Jquery, Bootstrap, Gulp, etc... &lt;/p&gt;",
                                                                        'chrMain' =>'Y',
									'chrPublish' => 'Y',
									'chrDelete'=> 'N',
									'created_at'=> Carbon::now(),
									'updated_at'=> Carbon::now()
								]);
							
						
								
					
				
								
					
							
		}
}
