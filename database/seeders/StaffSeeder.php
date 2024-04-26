<?php

namespace Database\Seeders;

use Exception;

use App\Models\Department;
use App\Models\Gender;
use App\Models\Role;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::with('roles')->get();
        foreach($departments as $department){
            foreach($department->roles as $departmentRole){
                try{
                    DB::beginTransaction();
                    $faker = Faker::create();
                    $staff = Staff::create([
                        'gender_id' => $faker->numberBetween(1, 2),
                        'department_id' => $department->id,
                        'name' => $faker->name,
                        'phone_number' => $faker->phoneNumber,
                        'password' => 'password',
                    ]);
                    $staff->roles()->sync([$departmentRole->id]);
                    DB::commit();
                }catch(Exception $e){
                    DB::rollBack();
                }
            }
        }
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
}
