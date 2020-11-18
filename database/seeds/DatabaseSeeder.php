<?php

use Illuminate\Database\Seeder;
use App\Association;
use App\Business;
use App\Category;
use App\Product;
use App\Message;
use App\User;
use App\Chat;
use App\News;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // el usuario administrador
        $user = new User([
          "name"      => "admin",
          "email"     => "admin@gmail.com",
          "password"  =>  bcrypt('123456789'),
          "phone"     => "1234567",
          "direction" => "Barcelona, calle piruleta",
          "type"      => "admin"
        ]);
        // test user
        $user->stripe_id = "cus_HtPt46MFbYYfgr":
        $user->card_last_four = "4242";
        $user->card_brand = "Visa";
        $user->save();
        // creamos el negocio único
        $restaurante = new Business([
          "email"       => "restaurante@gmail.com",
          "name"        => "Vuitantas 80's",
          "description" => "Descripción",
          "link"        => "merco.app",
          "user_id"     => $user->id
        ]);
        $restaurante->save();
        // finally we call the passport
        Artisan::call('passport:install');


    }
}
