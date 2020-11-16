<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Mail\BasicMail;
use App\Jobs\sendMail;
use App\Association;
use App\User;

class StaNoti extends Model
{
    //
    protected $table = 'sta_notifications';

    protected $fillable = [
      'send_at','for','type','title','message'
    ];

    public function getEnviadoAttribute()
    {
      return $this->sended? "Enviado":"Pendiente";
    }

    public function getGenteAttribute()
    {
      switch ($this->for) {
        case 'all':
        return "Todos";
          break;
        case "business":
          return "negocios";
        case "client";
          return "clientes";
        break;

      }
    }

    public function getMedioAttribute()
    {
        return ($this->type == "all")?
        "email y push" : $this->type;
    }

    public function send()
    {
      // if is for all take all
      $users = User::where('association_id',$this->AsoPerpetua()->id);
      // else only that we take
      if($this->for !== "all") {
        $users = $users->where('type',$this->for);
      }
      // vía
      if($this->type == "all") {
        foreach ($users->get() as $user) {
            // mails
            sendMail::dispatch(new BasicMail([
              'title'       => $this->title,
              'logoInTitle' => false,
              'text'        => $this->message,
            ]),$user->email);
            // Notifications
            $user->send([
              'title' =>  $this->title,
              'body'  =>  $this->message,
              'type'  =>  'staNoti'
            ]);
        }
      }
      // vía
      if($this->type == "email") {
        foreach ($users->get() as $user) {
            // mails
            sendMail::dispatch(new BasicMail([
              'title'       => $this->title,
              'logoInTitle' => false,
              'text'        => $this->message,
            ]),$user->email);

        }
      }
      //
      if($this->type == "push") {
        foreach ($users->get() as $user) {
            // mails
            $user->send([
              'title' =>  $this->title,
              'body'  =>  $this->message,
              'type'  =>  'staNoti'
            ]);

        }
      }
      $this->sended = true;
      $this->save();

    }

    public function AsoPerpetua()
    {
      return Association::where('searchName','staPerpetua')->first();
    }

    public  static function tabletate($data=null) {
      return [
        'headers' => [
          'Enviado'         =>  'enviado',
          'Para'            =>  'gente',
          'Por medio'       =>  'medio',
          'Fecha de envío'  =>  'send_at',
          'Titulo'          =>  'title'
        ],

        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'remove'  => true,
          'add'     => true,
        ],
        'singular' => 'notifications',
        'name'  => 'Notificaciones'
      ];
      
    }
}
