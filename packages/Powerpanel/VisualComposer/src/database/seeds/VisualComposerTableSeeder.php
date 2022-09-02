<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;

class VisualComposerTableSeeder extends Seeder
{
	public function run()
	{
        $pageModuleCode = DB::table('visualcomposer')->select('id')->where('varTitle','All')->first();
        
        if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => '0',
                'varTitle' => 'All',
                'varIcon' =>  '',
                'varClass' => 'active',
                'varTemplateName' => '',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $pageModuleCode = DB::table('visualcomposer')->select('id')->where('varTitle','Blocks')->first();
        
        if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => '0',
                'varTitle' => 'Blocks',
                'varIcon' =>  '',
                'varClass' => '',
                'varTemplateName' => '',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        //Blocks Module
        $blocks = DB::table('visualcomposer')->select('id')->where('varTitle','Blocks')->first();

        $sectionBlock = DB::table('visualcomposer')->select('id')->where('varTitle','Section Title')->first();
        
        if(!isset($sectionBlock->id) || empty($sectionBlock->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => 'Section Title',
                'varIcon' =>  'fa fa-italic',
                'varClass' => 'only-title',
                'varTemplateName' => 'visualcomposer::partial.section-title',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $textBlock = DB::table('visualcomposer')->select('id')->where('varTitle','Text Block')->first();
        
        if(!isset($textBlock->id) || empty($textBlock->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => 'Text Block',
                'varIcon' =>  'fa fa-text-width',
                'varClass' => 'text-block',
                'varTemplateName' => 'visualcomposer::partial.text-block',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $imageBlock = DB::table('visualcomposer')->select('id')->where('varTitle','Image Block')->first();
        
        if(!isset($imageBlock->id) || empty($imageBlock->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => 'Image Block',
                'varIcon' =>  'fa fa-image',
                'varClass' => 'only-image',
                'varTemplateName' => 'visualcomposer::partial.image-block',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $documentBlock = DB::table('visualcomposer')->select('id')->where('varTitle','Document Block')->first();

        if(!isset($documentBlock->id) || empty($documentBlock->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => 'Document Block',
                'varIcon' =>  'fa fa-file-text-o',
                'varClass' => 'only-document',
                'varTemplateName' => 'visualcomposer::partial.document-block',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $promoVideo = DB::table('visualcomposer')->select('id')->where('varTitle','Promo Video')->first();
        
        if(!isset($promoVideo->id) || empty($promoVideo->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => 'Promo Video',
                'varIcon' =>  'fa fa-video-camera',
                'varClass' => 'only-video',
                'varTemplateName' => 'visualcomposer::partial.promo-video',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }
        
         $iframeVideo = DB::table('visualcomposer')->select('id')->where('varTitle','IFrame')->first();
        
        if(!isset($iframeVideo->id) || empty($iframeVideo->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => 'IFrame',
                'varIcon' =>  'fa fa-window-maximize',
                'varClass' => 'iframeonly',
                'varTemplateName' => 'visualcomposer::partial.iframe',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $imageBlockText = DB::table('visualcomposer')->select('id')->where('varTitle','Image Block With Text')->first();
        
        if(!isset($imageBlockText->id) || empty($imageBlockText->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => 'Image Block With Text',
                'varIcon' =>  'fa fa-image',
                'varClass' => 'image-with-information',
                'varTemplateName' => 'visualcomposer::partial.image-block-text',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $videoBlockText = DB::table('visualcomposer')->select('id')->where('varTitle','Video Block With Text')->first();
        
        if(!isset($videoBlockText->id) || empty($videoBlockText->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => 'Video Block With Text',
                'varIcon' =>  'fa fa-video-camera',
                'varClass' => 'video-with-information',
                'varTemplateName' => 'visualcomposer::partial.video-block-text',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $googleMap = DB::table('visualcomposer')->select('id')->where('varTitle','Google Map')->first();
        
        if(!isset($googleMap->id) || empty($googleMap->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => 'Google Map',
                'varIcon' =>  'fa fa-map-marker',
                'varClass' => 'google-map',
                'varTemplateName' => 'visualcomposer::partial.google-map',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $contactInfo = DB::table('visualcomposer')->select('id')->where('varTitle','Contact Info')->first();
        
        if(!isset($contactInfo->id) || empty($contactInfo->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => 'Contact Info',
                'varIcon' =>  'fa fa fa-tasks',
                'varClass' => 'contact-info',
                'varTemplateName' => 'visualcomposer::partial.contact-info',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $button = DB::table('visualcomposer')->select('id')->where('varTitle','Button')->first();
        
        if(!isset($button->id) || empty($button->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => 'Button',
                'varIcon' =>  'fa fa fa-square',
                'varClass' => 'section-button',
                'varTemplateName' => 'visualcomposer::partial.button',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $twopartContent = DB::table('visualcomposer')->select('id')->where('varTitle','2 Part Contents')->first();
        
        if(!isset($twopartContent->id) || empty($twopartContent->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => '2 Part Contents',
                'varIcon' =>  'fa fa-align-justify',
                'varClass' => 'two-part-content',
                'varTemplateName' => 'visualcomposer::partial.2-part-content',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $addSpace = DB::table('visualcomposer')->select('id')->where('varTitle','Add Space')->first();
        
        if(!isset($addSpace->id) || empty($addSpace->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $blocks->id,
                'varTitle' => 'Add Space',
                'varIcon' =>  'fa fa-arrows-v',
                'varClass' => 'only-spacer',
                'varTemplateName' => 'visualcomposer::partial.add-space',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        // $homePageWelcomeSection = DB::table('visualcomposer')->select('id')->where('varTitle','Home Page Welcome Section')->first();
        
        // if(!isset($homePageWelcomeSection->id) || empty($homePageWelcomeSection->id)) {
        //     DB::table('visualcomposer')->insert([
        //         'fkParentID' => $blocks->id,
        //         'varTitle' => 'Home Page Welcome Section',
        //         'varIcon' =>  'fa fa-image',
        //         'varClass' => 'home-information',
        //         'varTemplateName' => 'visualcomposer::partial.home-page-welcome-section',
        //         'varModuleID' => '',
        //         'created_at'=> Carbon::now(),
        //         'updated_at'=> Carbon::now()
        //     ]);
        // }

        $partition = DB::table('visualcomposer')->select('id')->where('varTitle','Partition')->first();
        
        if(!isset($partition->id) || empty($partition->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => '0',
                'varTitle' => 'Partition',
                'varIcon' =>  '',
                'varClass' => '',
                'varTemplateName' => '',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $partition = DB::table('visualcomposer')->select('id')->where('varTitle','Partition')->first();

        $twoColumns = DB::table('visualcomposer')->select('id')->where('varTitle','Two Columns')->first();
        
        if(!isset($twoColumns->id) || empty($twoColumns->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $partition->id,
                'varTitle' => 'Two Columns',
                'varIcon' =>  'fa fa-columns',
                'varClass' => 'two-columns',
                'varTemplateName' => '',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $threeColumns = DB::table('visualcomposer')->select('id')->where('varTitle','Three Columns')->first();
        
        if(!isset($threeColumns->id) || empty($threeColumns->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $partition->id,
                'varTitle' => 'Three Columns',
                'varIcon' =>  'fa fa-columns',
                'varClass' => 'three-columns',
                'varTemplateName' => '',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $oneThreeColumns = DB::table('visualcomposer')->select('id')->where('varTitle','One Three Columns')->first();
        
        if(!isset($oneThreeColumns->id) || empty($oneThreeColumns->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $partition->id,
                'varTitle' => 'One Three Columns',
                'varIcon' =>  'fa fa-columns',
                'varClass' => 'one-three-columns',
                'varTemplateName' => '',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $threeOneColumns = DB::table('visualcomposer')->select('id')->where('varTitle','Three One Columns')->first();
        
        if(!isset($threeOneColumns->id) || empty($threeOneColumns->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $partition->id,
                'varTitle' => 'Three One Columns',
                'varIcon' =>  'fa fa-columns',
                'varClass' => 'three-one-columns',
                'varTemplateName' => '',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $fourColumns = DB::table('visualcomposer')->select('id')->where('varTitle','Four Columns')->first();
        
        if(!isset($fourColumns->id) || empty($fourColumns->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => $partition->id,
                'varTitle' => 'Four Columns',
                'varIcon' =>  'fa fa-columns',
                'varClass' => 'four-columns',
                'varTemplateName' => '',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $templates = DB::table('visualcomposer')->select('id')->where('varTitle','Templates')->first();

        if(!isset($templates->id) || empty($templates->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => '0',
                'varTitle' => 'Templates',
                'varIcon' =>  '',
                'varClass' => '',
                'varTemplateName' => '',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }

        $forms = DB::table('visualcomposer')->select('id')->where('varTitle','Forms')->first();

        if(!isset($forms->id) || empty($forms->id)) {
            DB::table('visualcomposer')->insert([
                'fkParentID' => '0',
                'varTitle' => 'Forms',
                'varIcon' =>  '',
                'varClass' => '',
                'varTemplateName' => '',
                'varModuleID' => '',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
        }
	}
}