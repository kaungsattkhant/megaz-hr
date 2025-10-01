<?php

namespace Database\Seeders;

use Exception;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Feature;
use App\Models\Inventory;
use App\Models\Department;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        #ksk
        // $hr_features = config('common.hr_feature_slug');
        // $featureIds = Feature::whereIn('slug', $hr_features)->pluck('id')->toArray();
        // $department=Department::create([
        //     'name'=>'HR',
        //     'slug'=>'hr',
        //     'created_at'=>now(),
        //     'updated_at'=>now(),
        // ]);
        // $department->features()->sync($featureIds);
        // $role=Role::create([
        //     'department_id'=>$department->id,
        //     'name'=>'Staff',
        //     'created_at'=>now(),
        //     'updated_at'=>now(),
        // ]);
        // $faker = Faker::create();
        // // $phoneNumber = '09' . str_repeat($department->id, 2) . sprintf('%02d',$departmentRole->id);
        // $phoneNumber = '0911';
        // $staff = Staff::create([
        //     'gender_id' => $faker->numberBetween(1, 2),
        //     'department_id' => $department->id,
        //     'name' => $faker->name,
        //     'phone_number' => $phoneNumber,
        //     'password' => 'password',
        //     'joined_date' => now(),
        //     'alt_phone_number' => $phoneNumber,
        //     'email' => $faker->email,
        //     'nrc_number' => $this->generateNRCNumber(),
        //     'birthdate' => '1990-12-12',
        //     'father_name' => 'U Aung',
        //     'mother_name' => 'Daw Moe',
        //     'state' => 'Mandalay',
        //     'city' => 'myitnge',
        //     'zip_code' => 'address',
        //     'address' => 'Mandalay/Myitnge',
        //     'bank_account_number' => '0002 0331 9933 8423',
        //     'nrc_front_url' => 'test',
        //     'nrc_front_path' => '/path',
        //     'nrc_back_url' => 'test-back',
        //     'nrc_back_path' => '/path/back',
        //     'household_registration_url' => '/household_registration_url',
        //     'household_registration_path' => '/household_registration_path',
        // ]);
        // $staff->emergencyContacts()->create([
        //     'primary_name' => $faker->name,
        //     'primary_phone' => $phoneNumber,
        //     'primary_relationship' => 'None',
        //     'secondary_name' => 'None',
        //     'secondary_phone' => $phoneNumber,
        //     'secondary_relationship' => 'None'
        // ]);
        // $staff->roles()->sync([$role->id]);
        // $staff->features()->sync($featureIds);


        // $departments = Department::with('roles')->get();
        $departments = Department::with('roles')->where('slug','hr')->get();
        $hr_features = config('common.hr_feature_slug');
        
            foreach ($departments as $i => $department) {
                foreach ($department->roles as $departmentRole) {
                    if ($departmentRole->name == 'Staff') {
                        $phoneNumber = str_repeat($department->id, 2);
                    } elseif ($departmentRole->name == 'Supervisor') {
                        $phoneNumber = str_repeat($department->id, 3);
                    } elseif ($departmentRole->name == 'Manager') {
                        $phoneNumber = str_repeat($department->id, 4);
                    } elseif ($departmentRole->name == 'Waiter') {
                        $phoneNumber = str_repeat($department->id, 5);
                    } elseif ($departmentRole->name == 'Cashier') {
                        $phoneNumber = str_repeat($department->id, 6);
                    } elseif ($departmentRole->name == 'Receptionist') {
                        $phoneNumber = str_repeat($department->id, 7);
                    } elseif ($departmentRole->name == 'MD' || $departmentRole->name == 'Supervisor') {
                        $phoneNumber = str_repeat($department->id, 1);
                    }
                    try {
                        DB::beginTransaction();
                        $faker = Faker::create();
                        // $phoneNumber = '09' . str_repeat($department->id, 2) . sprintf('%02d',$departmentRole->id);
                        $phoneNumber = '09' . $phoneNumber;
                        $staff = Staff::create([
                            'gender_id' => $faker->numberBetween(1, 2),
                            'department_id' => $department->id,
                            'name' => $faker->name,
                            'phone_number' => $phoneNumber,
                            'password' => 'password',
                            'joined_date' => now(),
                            'alt_phone_number' => $phoneNumber,
                            'email' => $faker->email,
                            'nrc_number' => $this->generateNRCNumber(),
                            'birthdate' => '1990-12-12',
                            'father_name' => 'U Aung',
                            'mother_name' => 'Daw Moe',
                            'state' => 'Mandalay',
                            'city' => 'myitnge',
                            'zip_code' => 'address',
                            'address' => 'Mandalay/Myitnge',
                            'bank_account_number' => '0002 0331 9933 8423',
                            'nrc_front_url' => 'test',
                            'nrc_front_path' => '/path',
                            'nrc_back_url' => 'test-back',
                            'nrc_back_path' => '/path/back',
                            'household_registration_url' => '/household_registration_url',
                            'household_registration_path' => '/household_registration_path',
                        ]);
                        $staff->emergencyContacts()->create([
                            'primary_name' => $faker->name,
                            'primary_phone' => $phoneNumber,
                            'primary_relationship' => 'None',
                            'secondary_name' => 'None',
                            'secondary_phone' => $phoneNumber,
                            'secondary_relationship' => 'None'
                        ]);
                        $department_id = $department->id;
                        $name = $department->name;
                       
                        $staff->roles()->sync([$departmentRole->id]);
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                    }
                }
            }
      
    }

    private function generateNRCNumber()
    {
        // Assuming the NRC number format is 13 digits long
        return str_pad(mt_rand(0, 9999999999999), 13, '0', STR_PAD_LEFT);
    }
}
