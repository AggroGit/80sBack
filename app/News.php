<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Association;

class News extends Model
{
    protected $fillable = [
      'title','subtitle','team','image_id','text'
    ];

    protected $with = ['image'];

    protected $appends = [
      'url'
    ];
    // the image of the news
    public function image()
    {
      return $this->belongsTo('App\Image');
    }

    public function getUrlAttribute()
    {
      return url("/news/$this->id");
    }

    public function association()
    {
      return $this->belongsTo('App\Association');
    }

    public  static function tabletate($data=null) {
      return [
        'headers' => [
          'Titulo' =>  'title',
          'Subtitulo' => 'subtitle',
          'Texto'      => 'text',
          'Associación' => [
            'model_name' => 'association',
            'select'     => Association::all(), // data al seleccionar en crear
            'show'       => 'name',
            'url'        => "admin/association/edit"
          ],
        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'add'     => true,
          'remove'  => true,
          'image'   => true
        ],
        'singular' => 'news',
        'name'  => 'Notícias',

      ];

    }





}
