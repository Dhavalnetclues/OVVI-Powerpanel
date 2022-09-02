<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ModuleEntryTableSeeder extends Seeder
{
	public function run()
	{
		
		DB::table('module')->where([
			'varTitle' => 'Appointment Lead',
			'varModuleName' =>  'appointment-lead',
			'varTableName' => 'appointment_lead',
			'varModelName' => 'AppointmentLead',
			'varModuleClass' => 'AppointmentLeadController'
		])->delete();
		
		DB::table('module')->insert([
			'varTitle' => 'Appointment Lead',
			'varModuleName' =>  'appointment-lead',
			'varTableName' => 'appointment_lead',
			'varModelName' => 'AppointmentLead',
			'varModuleClass' => 'AppointmentLeadController',
			'intDisplayOrder' => 6,
			'chrIsFront' => 'Y',
			'chrIsPowerpanel' => 'Y',
			'decVersion' => 2.0,
			'chrPublish' => 'Y',
			'chrDelete' => 'N',
			'varPermissions'=>'list, delete',
			'created_at'=> Carbon::now(),
			'updated_at'=> Carbon::now()
		]);
	}
}