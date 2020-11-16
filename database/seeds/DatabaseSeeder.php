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
        $aso = new Association();
        $aso->name = "sta perpetua";
        $aso->searchName = "staPerpetua";
        $aso->save();

        // $this->call(UserSeeder::class);
        // user admin
        $new = new User;
        $new->name = "Alex";
        $new->card_last_four = "1234";
        $new->card_brand = "Visa";
        $new->stripe_id = "cus_HtPt46MFbYYfgr";
        $new->email = "poropo97@gmail.com";
        $new->type = "admin";
        $new->password = bcrypt("123456789");
        $new->save();
        // user admin
        $new2 = new User;
        $new2->name = "Juan";
        $new2->email = "admin2@gmail.com";
        $new2->password = bcrypt("123456789");
        $new2->save();
        // chat
        $chat = new Chat();
        $chat->save();
        // add users
        $chat->addUser($new2);
        $chat->addUser($new);
        // a message
        $message = new Message([
          "user_id" => $new->id,
          "message" => "Hola caracola!",
          "chat_id" => $chat->id,

        ]);
        $message->save();


        $new = new News([
          "title"     => "Esto es un titulo de prueba",
          "subtitle"  => "Subtitulo",
          "team"      => "El baix Llobregat",
          "image_id"  => 1,
          "text"      => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
        ]);
        $new->save();

        // finally we call the passport
        Artisan::call('passport:install');
        Artisan::call('db:seed --class=ProductSeeder');
        // call the category
        Artisan::call('db:seed --class=CategorySeeder');

    }
}
