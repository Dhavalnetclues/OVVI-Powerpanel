<?php

use Illuminate\Database\Seeder;

class EmailTypeTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $emailTypeData = [
                    ['varEmailType' => 'Forgot Password', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Contact Us Lead', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'General', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Appointment Lead', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Reservation Lead', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Feedback Lead', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Privacy Removal', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Email To Friend', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Submit Ticket', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Contact Us Reply', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Registration Lead', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Contact Us Forward', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Page Hits Report', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Document Views & Downloads Report', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'New device signed', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Two Factor Authentication', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
                    ['varEmailType' => 'Form Builder Lead', 'chrPublish' => 'Y', 'chrDelete' => 'N'],
        ];

        DB::table('email_type')->insert($emailTypeData);
    }

}
