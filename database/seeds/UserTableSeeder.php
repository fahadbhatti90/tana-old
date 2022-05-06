<?php

use App\Model\Profile;
use Illuminate\Database\Seeder;
use App\Model\Role;
use App\Model\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mgmt_user_role')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Profile::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $superadminRole = Role::where('role_name','Super Admin')->first();
        $userRole = Role::where('role_name','User')->first();

        $superadmin = User::create(['username' => 'Shahbaz Bin Tahir', 'email' => 'shahbaz.tahir@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Ahad Siddiqui', 'email' => 'ahad.siddiqui@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Ahsan Wani', 'email' => 'ahsan.wani@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Ashar Qadir', 'email' => 'ashar.qadir@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Faisal Adnan', 'email' => 'faisal.adnan@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Muhammad Adnan', 'email' => 'muhammad.adnan@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Sikandar Ali Shah', 'email' => 'sikandar.ali@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Maaz Ali', 'email' => 'maaz.ali@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Roman Amin', 'email' => 'roman.amin@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Saliha Arif', 'email' => 'saliha.arif@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Tahir Mustafa', 'email' => 'tahir.mustafa@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Umer Farooq', 'email' => 'umer.farooq@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Abdul Waqar', 'email' => 'abdul.waqar@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Hamza Younas', 'email' => 'hamza.younas@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Fahad Bhatti', 'email' => 'fahad.bhatti@diginc.pk','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);

        $user = User::create(['username' => '47', 'email' => '47@tanasales.com','password' => Hash::make('123456789'),]);
        $user->roles()->attach($userRole);
        $user = User::create(['username' => 'Arctix', 'email' => 'arctix@tanasales.com','password' => Hash::make('123456789'),]);
        $user->roles()->attach($userRole);
        $user = User::create(['username' => 'Ardisam', 'email' => 'Ardisam@tanasales.com','password' => Hash::make('123456789'),]);
        $user->roles()->attach($userRole);
    }
}
