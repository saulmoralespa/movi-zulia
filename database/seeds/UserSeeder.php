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

        $user->assignRole('manager');

        $user = User::create([
            'name' => 'user 2',
            'email' => 'username2@example.com',
            'password' => bcrypt('secret')

        ]);

        $user->assignRole('manager');
    }
}
