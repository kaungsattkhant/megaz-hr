<?php

namespace Database\Seeders;

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
        //
        $kitchenDept = Department::where('name', 'Kitchen Department')->first();
        $barDept = Department::where('name', 'Bar Department')->first();

        $kitchenRole = Role::where('department_id', $kitchenDept->id)->pluck('id')->toArray();
        // $kitchenStaffRole = Role::where('name', 'Staff')->where('department_id', $kitchenDept->id)->first();

        $barRole = Role::where('department_id', $barDept->id)->pluck('id')->toArray();
        // $barStaffRole = Role::where('name', 'Staff')->where('department_id', $barDept->id)->first();

        DB::beginTransaction();
        try {
            $faker = Faker::create();
            //for kitcheck
            foreach (range(1, 20) as $index) {
                $staff = Staff::create([
                    'gender_id' => $faker->numberBetween(1, 2),
                    'department_id' => $kitchenDept->id,
                    'name' => $faker->name,
                    'phone_number' => $faker->phoneNumber,
                    'password' => 'password',
                ]);
                $randomKitchenRoles = $faker->randomElements($kitchenRole, $faker->numberBetween(1, count($kitchenRole)));
                $staff->roles()->sync($randomKitchenRoles);
            }
          
            #endregion

             //for kitcheck
             foreach (range(1, 20) as $index) {
                $staff = Staff::create([
                    'gender_id' => $faker->numberBetween(1, 2),
                    'department_id' => $barDept->id,
                    'name' => $faker->name,
                    'phone_number' => $faker->phoneNumber,
                    'password' => 'password',
                ]);
                $randomBarRoles = $faker->randomElements($barRole, $faker->numberBetween(1, count($barRole)));
                $staff->roles()->sync($randomBarRoles);
            }
           
            #endregion
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }

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
