<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;

class CmsPageTableSeeder extends Seeder
{
		public function run()
		{
			$moduleCode = DB::table('module')->select('id')->where('varModuleName','pages')->first();		
							
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Home')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%newsletter')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Home'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Home'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Home')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[{"type":"home-img_content","val":{"title":"Welcome Section","image":"11","content":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<\/p>","alignment":"home-lft-txt","src":""}},{"type":"blogs_template","val":{"title":"Latest Blogs","limit":"3","desc":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam efficitur placerat nulla, in suscipit erat sodales id. Nullam ultricies eu turpis at accumsan. Mauris a sodales mi, eget lobortis nulla.","config":"5","layout":"grid_3_col","template":"blogs-template"}},{"type":"events_template","val":{"title":"Latest Events","limit":"3","desc":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam efficitur placerat nulla, in suscipit erat sodales id. Nullam ultricies eu turpis at accumsan. Mauris a sodales mi, eget lobortis nulla.","config":"5","layout":"grid_3_col","template":"events-template"}},{"type":"news_template","val":{"title":"Latest News","limit":"3","desc":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam efficitur placerat nulla, in suscipit erat sodales id. Nullam ultricies eu turpis at accumsan. Mauris a sodales mi, eget lobortis nulla.","config":"8","layout":"grid_3_col","template":"news-template"}},{"type":"team_template","val":{"title":"Meet With Our Teams","config":"team-1","layout":"nq-grid-3","extclass":"","template":"team-template"}},{"type":"testimonial_template","val":{"title":"Our Customer Says","config":"testimonial-1","layout":"nq-grid-3","extclass":"","template":"testimonial-template"}},{"type":"service_template","val":{"title":"Our Services","config":"service-1","layout":"nq-grid-3","extclass":"","template":"featured-services"}},{"type":"product_template","val":{"title":"Our Products","config":"product-1","layout":"nq-grid-3","extclass":"","template":"featured-products"}},{"type":"show_template","val":{"title":"Upcoming Shows","config":"show-1","layout":"nq-grid-3","extclass":"","template":"upcoming-shows"}},{"type":"newsletter_template","val":{"title":"Newsletter Signup","template":"newsletter"}}]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Home',
							'varMetaKeyword' => 'Home',
							'varMetaDescription' => 'Home',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Blogs')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%blogs')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Blogs'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Blogs'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Blogs')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[{"type":"blogs_template","val":{"title":"Blogs","limit":"","desc":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam efficitur placerat nulla, in suscipit erat sodales id. Nullam ultricies eu turpis at accumsan. Mauris a sodales mi, eget lobortis nulla.","config":"5","layout":"grid_3_col","template":"blogs-template"}}]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Blogs',
							'varMetaKeyword' => 'Blogs',
							'varMetaDescription' => 'Blogs',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Gallery')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%gallery')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Gallery'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Gallery'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Gallery')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Gallery',
							'varMetaKeyword' => 'Gallery',
							'varMetaDescription' => 'Gallery',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Team')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%team')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Team'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Team'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Team')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[{"type":"team_template","val":{"title":"All Team Members","config":"team-1","layout":"nq-grid-3","extclass":"","template":"all-team-members"}}]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Team',
							'varMetaKeyword' => 'Team',
							'varMetaDescription' => 'Team',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Testimonial')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%testimonial')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Testimonial'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Testimonial'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Testimonial')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[{"type":"testimonial_template","val":{"title":"All Testimonials","config":"testimonial-1","layout":"nq-grid-3","extclass":"asdasdasd","template":"all-testimonials"}}]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Testimonial',
							'varMetaKeyword' => 'Testimonial',
							'varMetaDescription' => 'Testimonial',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Services')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%services')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Services'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Services'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Services')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[{"type":"service_template","val":{"title":"All Services","config":"service-1","layout":"nq-grid-3 ","extclass":"","template":"all-services"}}]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Services',
							'varMetaKeyword' => 'Services',
							'varMetaDescription' => 'Services',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Contact Us')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%contact')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Contact Us'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Contact Us'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Contact Us')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Contact Us',
							'varMetaKeyword' => 'Contact Us',
							'varMetaDescription' => 'Contact Us',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Faq')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%faq')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Faq'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Faq'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Faq')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[{"type":"faq_template","val":{"title":"All FAQs","config":"faq-1","layout":"nq-grid-3","extclass":"","template":"all-faqs"}}]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Faq',
							'varMetaKeyword' => 'Faq',
							'varMetaDescription' => 'Faq',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','About Us')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('About Us'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('About Us'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('About Us')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'About Us',
							'varMetaKeyword' => 'About Us',
							'varMetaDescription' => 'About Us',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Privacy Policy')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Privacy Policy'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Privacy Policy'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Privacy Policy')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Privacy Policy',
							'varMetaKeyword' => 'Privacy Policy',
							'varMetaDescription' => 'Privacy Policy',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Terms &amp; Conditions')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Terms &amp; Conditions'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Terms &amp; Conditions'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Terms &amp; Conditions')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Terms &amp; Conditions',
							'varMetaKeyword' => 'Terms &amp; Conditions',
							'varMetaDescription' => 'Terms &amp; Conditions',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Thank You')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Thank You'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Thank You'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Thank You')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Thank You',
							'varMetaKeyword' => 'Thank You',
							'varMetaDescription' => 'Thank You',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Careers')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%careers')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Careers'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Careers'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Careers')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[{"type":"career_template","val":{"title":"All Careers","config":"career-1","layout":"nq-grid-3","extclass":"","template":"all-careers"}}]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Careers',
							'varMetaKeyword' => 'Careers',
							'varMetaDescription' => 'Careers',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Events')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%events')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Events'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Events'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Events')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[{"type":"events_template","val":{"title":"Events","limit":"","desc":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam efficitur placerat nulla, in suscipit erat sodales id. Nullam ultricies eu turpis at accumsan. Mauris a sodales mi, eget lobortis nulla.","config":"5","layout":"grid_3_col","template":"events-template"}}]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Events',
							'varMetaKeyword' => 'Events',
							'varMetaDescription' => 'Events',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Photo Album')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%photo-album')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Photo Album'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Photo Album'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Photo Album')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Photo Album',
							'varMetaKeyword' => 'Photo Album',
							'varMetaDescription' => 'Photo Album',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Photo Gallery')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%photo-gallery')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Photo Gallery'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Photo Gallery'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Photo Gallery')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Photo Gallery',
							'varMetaKeyword' => 'Photo Gallery',
							'varMetaDescription' => 'Photo Gallery',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Shows')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%shows')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Shows'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Shows'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Shows')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Shows',
							'varMetaKeyword' => 'Shows',
							'varMetaDescription' => 'Shows',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Products')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%products')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Products'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Products'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Products')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[{"type":"product_template","val":{"title":"All Products","config":"product-1","layout":"nq-grid-3","extclass":"","template":"all-products"}}]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Products',
							'varMetaKeyword' => 'Products',
							'varMetaDescription' => 'Products',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','News')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%news')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('News'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('News'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('News')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[{"type":"news_template","val":{"title":"News","limit":"","desc":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam efficitur placerat nulla, in suscipit erat sodales id. Nullam ultricies eu turpis at accumsan. Mauris a sodales mi, eget lobortis nulla.","config":"8","layout":"grid_3_col","template":"news-template"}}]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'News',
							'varMetaKeyword' => 'News',
							'varMetaDescription' => 'News',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Client')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%client')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Client'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Client'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Client')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Client',
							'varMetaKeyword' => 'Client',
							'varMetaDescription' => 'Client',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Video Album')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%video-album')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Video Album'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Video Album'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Video Album')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Video Album',
							'varMetaKeyword' => 'Video Album',
							'varMetaDescription' => 'Video Album',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Video Gallery')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%video-gallery')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Video Gallery'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Video Gallery'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Video Gallery')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Video Gallery',
							'varMetaKeyword' => 'Video Gallery',
							'varMetaDescription' => 'Video Gallery',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Site Map')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Site Map'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Site Map'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Site Map')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Site Map',
							'varMetaKeyword' => 'Site Map',
							'varMetaDescription' => 'Site Map',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','404')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('404'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('404'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('404')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => '404',
							'varMetaKeyword' => '404',
							'varMetaDescription' => '404',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Sign In')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Sign In'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Sign In'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Sign In')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Sign In',
							'varMetaKeyword' => 'Sign In',
							'varMetaDescription' => 'Sign In',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Forgot Password')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Forgot Password'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Forgot Password'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Forgot Password')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Forgot Password',
							'varMetaKeyword' => 'Forgot Password',
							'varMetaDescription' => 'Forgot Password',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Sign Up')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Sign Up'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Sign Up'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Sign Up')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Sign Up',
							'varMetaKeyword' => 'Sign Up',
							'varMetaDescription' => 'Sign Up',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Reset Password')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Reset Password'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Reset Password'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Reset Password')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Reset Password',
							'varMetaKeyword' => 'Reset Password',
							'varMetaDescription' => 'Reset Password',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Profile')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Profile'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Profile'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Profile')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Profile',
							'varMetaKeyword' => 'Profile',
							'varMetaDescription' => 'Profile',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Manage Addresses')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Manage Addresses'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Manage Addresses'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Manage Addresses')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Manage Addresses',
							'varMetaKeyword' => 'Manage Addresses',
							'varMetaDescription' => 'Manage Addresses',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Cart')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Cart'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Cart'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Cart')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Cart',
							'varMetaKeyword' => 'Cart',
							'varMetaDescription' => 'Cart',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Checkout')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%pages')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Checkout'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Checkout'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Checkout')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Checkout',
							'varMetaKeyword' => 'Checkout',
							'varMetaDescription' => 'Checkout',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Blog Category')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%blog-category')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Blog Category'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Blog Category'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Blog Category')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Blog Category',
							'varMetaKeyword' => 'Blog Category',
							'varMetaDescription' => 'Blog Category',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Service Category')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%service-category')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Service Category'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Service Category'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Service Category')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Service Category',
							'varMetaKeyword' => 'Service Category',
							'varMetaDescription' => 'Service Category',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Product Category')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%product-category')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Product Category'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Product Category'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Product Category')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Product Category',
							'varMetaKeyword' => 'Product Category',
							'varMetaDescription' => 'Product Category',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Careers Category')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%careers-category')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Careers Category'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Careers Category'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Careers Category')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Careers Category',
							'varMetaKeyword' => 'Careers Category',
							'varMetaDescription' => 'Careers Category',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Client Category')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%client-category')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Client Category'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Client Category'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Client Category')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Client Category',
							'varMetaKeyword' => 'Client Category',
							'varMetaDescription' => 'Client Category',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Event Category')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%event-category')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Event Category'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Event Category'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Event Category')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Event Category',
							'varMetaKeyword' => 'Event Category',
							'varMetaDescription' => 'Event Category',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','News Category')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%news-category')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('News Category'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('News Category'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('News Category')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'News Category',
							'varMetaKeyword' => 'News Category',
							'varMetaDescription' => 'News Category',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Show Category')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%show-category')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Show Category'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Show Category'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Show Category')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Show Category',
							'varMetaKeyword' => 'Show Category',
							'varMetaDescription' => 'Show Category',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
				
					$pageModuleCode = DB::table('module')->select('id')
					->where('varTitle','Sponsor Category')
					->first();

					if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)){
						$pageModuleCode = DB::table('module')->select('id')
						->where('varModuleName','like','%sponsor-category')						
						->first();
					}

					if(isset($pageModuleCode->id) && !empty($pageModuleCode->id))
					{
						$intFKModuleCode = $pageModuleCode->id;		
					}else{
						$intFKModuleCode = $moduleCode->id;
					}
					
					$exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Sponsor Category'))->first();		
					if(!isset($exists->id)){					
						DB::table('cms_page')->insert([
							'varTitle' =>  htmlspecialchars_decode('Sponsor Category'),
							'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Sponsor Category')),$moduleCode->id),
							'intFKModuleCode' => $intFKModuleCode,
							'txtDescription' => '[]',
							'chrPublish' => 'Y',
							'chrDelete'=> 'N',
                                                        'chrMain' =>'Y',
							'varMetaTitle' => 'Sponsor Category',
							'varMetaKeyword' => 'Sponsor Category',
							'varMetaDescription' => 'Sponsor Category',
							'created_at'=> Carbon::now(),
							'updated_at'=> Carbon::now(),
						]);					
					}					
									}
}
