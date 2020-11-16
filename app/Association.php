<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Association;

class Association extends Model
{
    //
    public function users()
    {
       return $this->hasMany('App\User');
    }

    public function image()
    {
        return $this->belongsTo('App\Image');
    }

    public static function AsoPerpetua()
    {
      return Association::where('searchName','staPerpetua')->first();
    }

    public function tokenFirebase()
    {
      if($this->searchName == "staPerpetua") {
        return "AAAAE7citzw:APA91bEOiYkj6iaEnIJjKHB0Z7FK8J0lG-vV2_KQouKOMXBzM6-a6ye49tn3JyWtAK3gN3EpPvAE_5eUc2o_0oCBINt8VZGwuyN0lHibKDB_nSvijxcZ5sjiaH-Z3N0VUkLeVGD1yCcm";
      } else {
        return "AAAAtMuWwfU:APA91bEtyd7bEC1b96c6a9vesNhibzvMRVq8VfWom0IAcVpKJ_aQcp_hBMPqD8flzGvjObmK6qfAhs7LTLX4JlkorXArBD8A-QakcVcCVIKNOkFaLM8dLDCP9bJhWrwQf81L2NuoMTOo";
      }
    }



    public function getUsersCountAttribute()
    {
      $invited = $this->users()->where('invited',true)->count();
      $normal = $this->users()->count();
      $total = $invited+$normal;

      return "$invited Invitados \n $normal Registrados \n $total en total";
    }

    public  static function tabletate($data=null) {
      return [
        'headers' => [
          'Nombre Asociación'   =>  'name',
          'Correo de Contaxcto' => 'contact_email',
          'Nombre App-Front'    => 'searchName',
            'Usuarios'  => 'userscount',
        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'remove'  => true,
          'image'   => true,
          'add'     => true
        ],
        'singular' => 'association',
        'name'     => 'Asociaciones',

      ];

    }


}
