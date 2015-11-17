<?php
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OAuthClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = str_random(40);
        $secret = str_random(40);
        $name = 'test-client';
        $createdAt = Carbon::now();
        $updatedAt = Carbon::now();
        DB::statement("DELETE FROM `oauth_clients` WHERE `name`='$name';");
        DB::statement("INSERT INTO `oauth_clients`(`id`, `secret`, `name`, `created_at`, `updated_at`) VALUES ('$id', '$secret', '$name', '$createdAt', '$updatedAt')");
    }
}
