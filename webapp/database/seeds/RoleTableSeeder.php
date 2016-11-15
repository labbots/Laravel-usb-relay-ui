<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{

    public function run()
    {

        $roles = [
                    [
                        'id'            => 1,
                        'name'          => 'Administrator',
                        'description'   => 'Full access to create, edit, and update user information.'
                    ],
                    [
                        'id'            => 2,
                        'name'          => 'User',
                        'description'   => 'A privileged user who can access the web portal.'
                    ],
                ];

        foreach ($roles as $role) {
            try {
                Role::updateOrCreate($role);
            } catch (Exception $e) {
                echo "Error creating/updating Roles.";
                throw $e;   
            }
            
        }
    }
}
