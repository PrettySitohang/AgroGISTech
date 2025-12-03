<?php
// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name'=>'Super Admin','email'=>'admin@agrogistech.test','password'=>bcrypt('password123'),'role'=>'super_admin']);
        User::create(['name'=>'Editor','email'=>'editor@agrogistech.test','password'=>bcrypt('password123'),'role'=>'editor']);
        User::create(['name'=>'Penulis','email'=>'penulis@agrogistech.test','password'=>bcrypt('password123'),'role'=>'penulis']);
    }
}
