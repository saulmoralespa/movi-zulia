<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'create',
            'update',
            'view',
            'delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo(Permission::all());

        $user = User::create([
            'name' => 'user',
            'email' => 'username@example.com',
            'password' => bcrypt('secret')

        ]);

        $user2 = User::create([
            'name' => 'user 2',
            'email' => 'username2@example.com',
            'password' => bcrypt('secret')

        ]);

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            App\Driver::create([
                'avatar' => $faker->image('public/storage/img/profile',250,250, 'people', false),
                'name' => "$faker->firstName $faker->lastName",
                'plate_number' => $faker->unique()->regexify('[A-Za-z0-9]{6}'),
                'phone' => $faker->unique()->phoneNumber,
                'filename' => json_encode([
                    $faker->image('public/storage/img/cars',640,480, 'transport', false),
                    $faker->image('public/storage/img/cars',640,480, 'transport', false),
                    $faker->image('public/storage/img/cars',640,480, 'transport', false)
                ]),
                'user_id' => $user->id
            ]);
        }

        $user->assignRole('manager');
        $user2->assignRole('manager');
    }
}
