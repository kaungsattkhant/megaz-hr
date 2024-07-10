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
        /* $departments = Department::with('roles')->get();
        $hr_features = config('common.hr_features');
        $inventory_features = config('common.inventory_features');
        $finance_features = config('common.finance_features');
        $management_features = config('common.management_features');
        $catering_features = config('common.catering_features');
        foreach ($departments as $i => $department) {
            foreach ($department->roles as $departmentRole) {
                if ($departmentRole->name == 'Staff') {
                    $phoneNumber = str_repeat($department->id, 2);
                } elseif ($departmentRole->name == 'Supervisor') {
                    $phoneNumber = str_repeat($department->id, 3);
                } elseif ($departmentRole->name == 'Manager') {
                    $phoneNumber = str_repeat($department->id, 4);
                } elseif ($departmentRole->name == 'MD') {
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
                        'joined_date'=>now(),
                        'alt_phone_number'=>$phoneNumber,
                        'email'=>$faker->email,
                        'nrc_number'=>$this->generateNRCNumber(),
                        'birthdate'=>'1990-12-12',
                        'father_name'=>'U Aung',
                        'mother_name'=>'Daw Moe',
                        'state'=>'Mandalay',
                        'city'=>'myitnge',
                        'zip_code'=>'address',
                        'address'=>'Mandalay/Myitnge',
                        'bank_account_number'=>'0002 0331 9933 8423',
                        'nrc_front_url'=>'test',
                        'nrc_front_path'=>'/path',
                        'nrc_back_url'=>'test-back',
                        'nrc_back_path'=>'/path/back',
                        'household_registration_url'=>'/household_registration_url',
                        'household_registration_path'=>'/household_registration_path',
                    ]);
                    $staff->emergencyContacts()->create([
                        'primary_name'=>$faker->name,
                        'primary_phone'=>$phoneNumber,
                        'primary_relationship'=>'None',
                        'secondary_name'=>'None',
                        'secondary_phone'=>$phoneNumber,
                        'secondary_relationship'=>'None'
                    ]);
                    $department_id = $department->id;
                    switch ($department_id) {
                        case 1:
                            $staff->features()->sync($hr_features);
                            break;
                        case 2:
                            $staff->features()->sync($finance_features);
                            break;
                        case 4:
                            $staff->features()->sync($management_features);
                            break;
                        case 5:
                            $staff->features()->sync($catering_features);
                            break;
                        case 6:
                            $staff->features()->sync($inventory_features);
                            break;

                        default:
                            $staff->features()->sync($hr_features);
                    }
                    $staff->roles()->sync([$departmentRole->id]);
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                }
            }
        }
            */

        // ****pks*****
        try{
            DB::beginTransaction();
            $features = Feature::all();
            $adminDept = Department::where('name', 'Admin')->first();
            $superAdminRole = Role::create(['name' => 'Super Admin', 'department_id' => $adminDept->id]);
            $mainInventory = Inventory::find(6);
            $superAdmin = Staff::create([
                'gender_id' => 1,
                'department_id' => $adminDept->id,
                'name' => 'Super Admin',
                'phone_number' => '0900',
                'password' => 'password',
            ]);
            $superAdmin->roles()->attach($superAdminRole);
            $superAdmin->inventories()->attach($mainInventory);
            foreach($features as $feature){
                $superAdmin->features()->attach($feature);
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            dd($e->getMessage());
        }
        // *****end******


        // $opDept = Department::where('name', 'Operation Department')->first();
        // $fiDept = Department::where('name', 'Finance Department')->first();

        // $opRole = Role::where('department_id', $opDept->id)->pluck('id')->toArray();

        // $fiRole = Role::where('department_id', $fiDept->id)->pluck('id')->toArray();

        // $staffRole = Role::where('name', 'Staff')->first();
        // $managerRole = Role::where('name', 'Manager')->first();
        // $financeRole = Role::where('name', 'Financial')->first();
        // $mdRole = Role::where('name', 'MD')->first();
        // // $barStaffRole = Role::where('name', 'Staff')->where('department_id', $barDept->id)->first();

        // DB::beginTransaction();
        // try {
        //     $faker = Faker::create();
        //     //for kitcheck
        //         $staff = Staff::create([
        //             'gender_id' => $faker->numberBetween(1, 2),
        //             'department_id' => $staffRole->department_id,
        //             'name' => $faker->name,
        //             'phone_number' => '091111',
        //             'password' => 'password',
        //         ]);
        //         $staff->roles()->sync([$staffRole->id]);

        //         $staff1 = Staff::create([
        //             'gender_id' => $faker->numberBetween(1, 2),
        //             'department_id' => $staffRole->department_id,
        //             'name' => $faker->name,
        //             'phone_number' => '0911111',
        //             'password' => 'password',
        //         ]);
        //         $staff1->roles()->sync([$staffRole->id]);

        //         $manager = Staff::create([
        //             'gender_id' => $faker->numberBetween(1, 2),
        //             'department_id' => $managerRole->department_id,
        //             'name' => $faker->name,
        //             'phone_number' => '092222',
        //             'password' => 'password',
        //         ]);
        //         $manager->roles()->sync([$managerRole->id]);

        //         $finance = Staff::create([
        //             'gender_id' => $faker->numberBetween(1, 2),
        //             'department_id' => $financeRole->department_id,
        //             'name' => $faker->name,
        //             'phone_number' => '093333',
        //             'password' => 'password',
        //         ]);
        //         $finance->roles()->sync([$financeRole->id]);

        //         $md = Staff::create([
        //             'gender_id' => $faker->numberBetween(1, 2),
        //             'department_id' => $mdRole->department_id,
        //             'name' => $faker->name,
        //             'phone_number' => '094444',
        //             'password' => 'password',
        //         ]);
        //         $md->roles()->sync([$mdRole->id]);

        #endregion

        //  //for kitcheck
        //  foreach (range(1, 20) as $index) {
        //     $staff = Staff::create([
        //         'gender_id' => $faker->numberBetween(1, 2),
        //         'department_id' => $barDept->id,
        //         'name' => $faker->name,
        //         'phone_number' => $faker->phoneNumber,
        //         'password' => 'password',
        //     ]);
        //     $randomBarRoles = $faker->randomElements($barRole, $faker->numberBetween(1, count($barRole)));
        //     $staff->roles()->sync($randomBarRoles);
        // }

        #endregion
        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     ResponseMessage($e->getMessage(), 402);
        //     throw $e;
        // }

        // $staff = Staff::create([
        //     'gender_id' => Gender::where('name', 'Male')->first()->id,
        //     'department_id' => $kitchenStaffRole->department_id,
        //     'name' => 'Ko Kitchen Staff',
        //     'phone_number' => '09112',
        //     'password' => 'password',
        // ]);

        // $staff->roles()->attach($kitchenStaffRole);

        // $staff = Staff::create([
        //     'gender_id' => Gender::where('name', 'Male')->first()->id,
        //     'department_id' => $barSupervisorRole->department_id,
        //     'name' => 'Ko Bar Supervisor',
        //     'phone_number' => '09221',
        //     'password' => 'password',
        // ]);

        // $staff->roles()->attach($barSupervisorRole);

        // $staff = Staff::create([
        //     'gender_id' => Gender::where('name', 'Female')->first()->id,
        //     'department_id' => $barStaffRole->department_id,
        //     'name' => 'Ma Bar Staff',
        //     'phone_number' => '09222',
        //     'password' => 'password',
        // ]);

        // $staff->roles()->attach($barStaffRole);
    }

    private function generateNRCNumber()
    {
        // Assuming the NRC number format is 13 digits long
        return str_pad(mt_rand(0, 9999999999999), 13, '0', STR_PAD_LEFT);
    }
}
