<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;

class ModuleGroupTableSeeder extends Seeder {

    public function run() {
        $pageModuleCode = DB::table('module_group')->select('id')->where('varTitle', 'Miscellaneous')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            DB::table('module_group')->insert([
                'id' => '0',
                'varTitle' => 'Miscellaneous',
                'intDisplayOrder' => '1',
                'chrDelete' => 'N',
                'chrPublish' => 'Y',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        $pageModuleCode = DB::table('module_group')->select('id')->where('varTitle', 'CMS')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            DB::table('module_group')->insert([
                'id' => '1',
                'varTitle' => 'CMS',
                'intDisplayOrder' => '2',
                'chrDelete' => 'N',
                'chrPublish' => 'Y',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        $pageModuleCode = DB::table('module_group')->select('id')->where('varTitle', 'Media & Documents')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            DB::table('module_group')->insert([
                'id' => '2',
                'varTitle' => 'Media & Documents',
                'intDisplayOrder' => '3',
                'chrDelete' => 'N',
                'chrPublish' => 'Y',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }


        $pageModuleCode = DB::table('module_group')->select('id')->where('varTitle', 'Profile')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            DB::table('module_group')->insert([
                'id' => '3',
                'varTitle' => 'Profile',
                'intDisplayOrder' => '4',
                'chrDelete' => 'N',
                'chrPublish' => 'Y',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
        
        $pageModuleCode = DB::table('module_group')->select('id')->where('varTitle', 'Leads')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            DB::table('module_group')->insert([
                'id' => '4',
                'varTitle' => 'Leads',
                'intDisplayOrder' => '5',
                'chrDelete' => 'N',
                'chrPublish' => 'Y',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
        
        $pageModuleCode = DB::table('module_group')->select('id')->where('varTitle', 'Logs')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            DB::table('module_group')->insert([
                'id' => '5',
                'varTitle' => 'Logs',
                'intDisplayOrder' => '6',
                'chrDelete' => 'N',
                'chrPublish' => 'Y',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
        
        $pageModuleCode = DB::table('module_group')->select('id')->where('varTitle', 'User Management')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            DB::table('module_group')->insert([
                'id' => '6',
                'varTitle' => 'User Management',
                'intDisplayOrder' => '7',
                'chrDelete' => 'N',
                'chrPublish' => 'Y',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }

}
