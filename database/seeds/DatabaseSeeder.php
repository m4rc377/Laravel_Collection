<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        // inserting document types
        foreach (config('seed_data.document_types') as $value) {

            DB::table('document_type')->insert([
                'name' => $value
            ]);
        }

        // insert task status
        foreach (config('seed_data.task_statuses') as $value) {

            DB::table('task_status')->insert([
                'name' => $value
            ]);
        }

        // insert task types
        foreach (config('seed_data.task_types') as $value) {

            DB::table('task_type')->insert([
                'name' => $value
            ]);
        }

        // insert contact status
        foreach (config('seed_data.contact_status') as $value) {

            DB::table('contact_status')->insert([
                'name' => $value
            ]);
        }

        // insert sample user as the system admin
        DB::table('users')->insert([
           'name' => 'admin',
           'email' => 'admin@my-crm.com',
           'password' => bcrypt("admin"),
           'parent_id' => 0
        ]);
    }
}
