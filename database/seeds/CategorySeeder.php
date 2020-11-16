<?php

use Illuminate\Database\Seeder;

use App\Translation;
use App\Business;
use App\Category;
use App\Product;
use App\Image;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // alimentacion
        $alimentacion = new Category([
          "name" => "Alimentacion",
          'image_id' => Image::where('name','comestibles')->first()->id
        ]);
        $alimentacion->save();
        $alimentacion->addTranslation("Alimentació");
        // restauracion
        $restauracion = new Category([
          "name" => "Servicios",
          'image_id' => Image::where('name','c_restaurantes')->first()->id
        ]);
        $restauracion->save();
        $restauracion->addTranslation('Serveis');


        // tiendas
        $tiendas = new Category([
          "name" => "Comercios y Tiendas",
        ]);
        $tiendas->save();
        $tiendas->addTranslation("Tendes i comerç");

        // Subcategorias
        $embutidos= new Category([
          'name'        => 'Carnes y Embutidos',
          'category_id' => $alimentacion->id,
        ]);
        $embutidos->save();
        //
        $frutasVerduras= new Category([
          'name'        => 'Frutas y verduras',
          'category_id' => $alimentacion->id
        ]);
        $frutasVerduras->save();

        //
        $ferreteria= new Category([
          'name'        => 'Ferretería',
          'category_id' => $restauracion->id
        ]);
        $ferreteria->save();
        $ferreteria->addTranslation('Ferreteria');

        //
        $drogueria= new Category([
          'name'        => 'Droguería',
          'category_id' => $restauracion->id
        ]);
        $drogueria->save();
        $drogueria->addTranslation('droguería');

        // de alimentacion
        // carniceria
        $carnes = new Business([
            'name' => 'Carnes Menir',
            'email' => 'menir@merco.com',
            'category_id' => $alimentacion->id,
            'user_id'     => 1
        ]);
        $carnes->save();
        // fruteria
        $fruteria = new Business([
            'name' => 'Fruterías Jaume',
            'email' => 'frutasJaume@merco.com',
            'category_id' => $frutasVerduras->id,
            'user_id'     => 1
        ]);
        //
        $fruteria->save();





    }
}
