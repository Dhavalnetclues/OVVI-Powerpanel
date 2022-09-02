<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use DB;

class NumberAllocationTableSeeder extends Seeder
{
	public function run()
	{
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle','Number Allocation')->first();
        
        if(!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode'=>'2',
                    'varTitle' => 'Number Allocation',
                    'varModuleName' =>  'number-allocation',
                    'varTableName' => 'number_allocation',
                    'varModelName' => 'NumberAllocation',
                    'varModuleClass' => 'NumberAllocationController',
                    'varModuleNameSpace' => 'Powerpanel\NumberAllocation\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'Y',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions'=> 'list, create, edit, delete, publish, reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now()
                ]);
            } else {
                DB::table('module')->insert([
                    'varTitle' => 'Number Allocation',
                    'varModuleName' =>  'number-allocation',
                    'varTableName' => 'number_allocation',
                    'varModelName' => 'NumberAllocation',
                    'varModuleClass' => 'NumberAllocationController',
                    'varModuleNameSpace' => 'Powerpanel\NumberAllocation\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'Y',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions'=> 'list, create, edit, delete, publish, reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now()
                ]);
            }


            $pageModuleCode = DB::table('module')->where('varTitle','Number Allocation')->first();
            $permissions = [];
            foreach (explode(',', $pageModuleCode->varPermissions) as $permissionName) {
                $permissionName=trim($permissionName);
                $Icon = $permissionName;
                
                if($permissionName=='list'){
                    $Icon = 'per_list';	
                }elseif ($permissionName == 'create') {
                    $Icon = 'per_add';							
                }elseif ($permissionName == 'edit') {
                    $Icon = 'per_edit';							
                }elseif ($permissionName == 'delete') {
                    $Icon = 'per_delete';							
                }elseif ($permissionName == 'publish') {
                    $Icon = 'per_publish';
                }elseif ($permissionName == 'reviewchanges') {
                    $Icon = 'per_reviewchanges';
                }
                array_push($permissions, [
                    'name' => $pageModuleCode->varModuleName.'-'.$permissionName,
                    'display_name' => $Icon,
                    'description' => ucwords($permissionName).' Permission',
                    'intFKModuleCode'=> $pageModuleCode->id
                ]);
            }
            
            foreach ($permissions as $key => $value) {
                $id = DB::table('permissions')->insertGetId($value);
                for ($roleId = 1; $roleId <= 3; $roleId++) {
                    $value = [
                        'permission_id' => $id,
                        'role_id' => $roleId,
                    ];
                    DB::table('role_has_permissions')->insert($value);
                }
            }

        }

        $pageModuleCode = DB::table('module')->where('varTitle','Blog Category')->first();//to be changed once company module is prepared

        $cmsModuleCode = DB::table('module')->where('varTitle','pages')->first();
        $intFKModuleCode = $pageModuleCode->id;
					
        $exists = DB::table('cms_page')->select('id')->where('varTitle',htmlspecialchars_decode('Number Allocation'))->first();

        if(!isset($exists->id)){		
            if (\Schema::hasColumn('cms_page', 'chrMain')) {			
                DB::table('cms_page')->insert([
                    'varTitle' =>  htmlspecialchars_decode('Number Allocation'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Number Allocation')),$cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrMain' => 'Y',
                    'chrDelete'=> 'N',
                    'varMetaTitle' => 'Number Allocation',
                    'varMetaKeyword' => 'Number Allocation',
                    'varMetaDescription' => 'Number Allocation',
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now(),
                ]);
            }
            else {
                DB::table('cms_page')->insert([
                    'varTitle' =>  htmlspecialchars_decode('Number Allocation'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Number Allocation')),$cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete'=> 'N',
                    'varMetaTitle' => 'Number Allocation',
                    'varMetaKeyword' => 'Number Allocation',
                    'varMetaDescription' => 'Number Allocation',
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now(),
                ]);
            }					
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle','Number Allocation')->first();		

        $moduleCode = DB::table('module')->select('id')->where('varModuleName','number-allocation')->first();
        
        
         DB::table('number_allocation')->insert([
            'varSector' => 'ofreg',
            'nxx' => '123-456',
            'intFkCategory' => 1,
            'service' => 'Fixed',
            'note' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'chrMain' => 'Y',
            'intDisplayOrder' => '1', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
         
         DB::table('number_allocation')->insert([
            'varSector' => 'water',
            'nxx' => '456-123',
            'intFkCategory' => 2,
            'service' => 'Mobile',
            'note' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'chrMain' => 'Y',
            'intDisplayOrder' => '2', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
         
          DB::table('number_allocation')->insert([
            'varSector' => 'ict',
            'nxx' => '345-678',
            'intFkCategory' => 3,
            'service' => 'Mobile - GSM',
            'note' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'chrMain' => 'Y',
            'intDisplayOrder' => '3', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $moduleCode = DB::table('module')->select('id')->where('varModuleName','number-allocation')->first();
        
        if (Schema::hasTable('visualcomposer')) {
            //Adding Interconnections Module In visual composer
            $numberAllocationModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Number Allocation')->where('fkParentID', '0')->first();

            if (!isset($numberAllocationModule->id) || empty($numberAllocationModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Number Allocation',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $moduleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $numberAllocationModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Number Allocation')->where('fkParentID', '0')->first();

            $numberAllocationChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Number Allocation')->where('fkParentID', '<>', '0')->first();

            if (!isset($numberAllocationChild->id) || empty($numberAllocationChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $numberAllocationModule->id,
                    'varTitle' => 'All Number Allocation',
                    'varIcon' => 'fa fa-university',
                    'varClass' => 'number-allocations',
                    'varTemplateName' => 'number-allocation::partial.all-number-allocations',
                    'varModuleID' => $moduleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
	}
}