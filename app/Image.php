<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// our image editor
use  ImageEditor;

class Image extends Model
{

  public $editor;
  protected $appends= ["sizes"];

  public function getSizesAttribute()
  {
    $base = $this->getPath()."/$this->name";
    $end  = ".$this->format";
    return [
      "VerySmall" => "$base/50$end",
      "Small" => "$base/100$end",
      "NotSmall" => "$base/200$end",
      "Med" => "$base/300$end",
      "Big" => "$base/700$end",
      // "Original" => "$base/original$end",
    ];
  }

  public function __construct(array $attributes = array())
  {
      parent::__construct($attributes);
      // $this->editor = new ImageEditor();
  }

  // return the editor tool
  public function editable()
  {
      // devolvemos un nuevo objeto de tipo ImageEditor, no devolvemos el
      // del pbjeto porque quizas no interesa que se guarda los cambios
      // siempre en el original
      return ImageEditor::configure(array('driver' => 'imagick'))->make($this->getOriginalImage());

  }



  // random token for the image
  public function generateToken()
  {
      return md5(uniqid(rand(), true));
  }

  // say if the image exists or not
  public function exists()
  {

    return file_exists($this->getFolder());
  }

  public function destroyImage()
  {
    if($this->exists()) {
      try {
        $this->rmdir_recursive($this->getFolder());
        // return $this->delete();
      } catch (\Exception $e) {
        return; false;
      }

    }
  }

  // update the media
  public function updateImage($image)
  {
    if($this->exists()){
      $this->rmdir_recursive($this->getPathToSave()."/$this->name");
      $this->name = $this->generateToken();
      return $this->create();
    }
  }
  public function delete()
  {
      // delete all related messages
      $this->destroyImage();
      return parent::delete();
  }

  // create the image
  public function create($image, $path=null, $public = false)
  {
    try {
        // if the image is null we create the token
        $this->name = is_null($this->name)?
        $this->generateToken() : $this->name;
        // base
        $this->base = $public? "public":"storage";
        // take the format
        $this->format = $image->getClientOriginalExtension();
        // a real name of the media
        $real = $this->name.'.'.$this->format;
        // the url
        $this->url = $path?? null;
        // create the data
        $this->save();
        //
        $r = $this->getPathToSave()."/$this->name";
        mkdir($r,0777,true);
        // we save the original pic
        $image->move($this->getPathToSave()."/$this->name/","original.$this->format");
        $this->sizes($image);
        // remove the original size
        try {
          if(file_exists($this->getOriginalImage())) {
            @unlink($this->getOriginalImage());
          } else {
          }

        } catch (\Exception $e) {
          var_dump($e);
        }


        return true;

    } catch (\Exception $e) {
      $this->delete();
      return false;
    }

  }


  public function createFromUrl($url,$path,$public = false)
  {
    $content = file_get_contents($url);
    $this->name = $name = $this->generateToken();
    $this->format = "png";
    $this->url = $path;
    $this->base = $public? "public":"storage";
    if (!is_dir(storage_path("app/public/$path/$name"))) {
      // dir doesn't exist, make it
      mkdir(storage_path("app/public/$path/$name"));
    }
    // file_put_contents('upload/promotions/' . $month . '/' . $image, $contents_data);
    file_put_contents(storage_path("app/public/$path/$name/original.png"), $content);
    $this->save();
    $this->sizes();
    // remove the original size
    unlink($this->getOriginalImage());
  }


  // we save the original and 4 sizes
  public function sizes($image=null)
  {
      // take the editable
      $edit = $this->editable();
      // 700
      $edit->resize(700, null, function ($constraint) {
          $constraint->aspectRatio();
      })->save($this->getPathToSave()."/$this->name/700.$this->format");
      // 300
      $edit->resize(300, null, function ($constraint) {
          $constraint->aspectRatio();
      })->save($this->getPathToSave()."/$this->name/300.$this->format");
      // 200
      $edit->resize(200, null, function ($constraint) {
          $constraint->aspectRatio();
      })->save($this->getPathToSave()."/$this->name/200.$this->format");
      // 100
      $edit->resize(100, null, function ($constraint) {
          $constraint->aspectRatio();
      })->save($this->getPathToSave()."/$this->name/100.$this->format");
      // 50
      $edit->resize(50, null, function ($constraint) {
          $constraint->aspectRatio();
      })->save($this->getPathToSave()."/$this->name/50.$this->format");




  }

  // get a relative path
  public function getPath()
  {
    if($this->base == "public")
      return url($this->url);
    else
      return url("$this->base/".$this->url);
  }

  // get the path to save
  public function getPathToSave()
  {
    // if the image is in the storage we have to put it in the storage/app/public folder
    if ($this->base == "storage") {
      return storage_path("app/public/$this->url");
    } else {
      return $this->getPath();
    }
  }

  public function getOriginalImage()
  {
    return $this->getPathToSave()."/$this->name/original.$this->format";
  }

  public function getFolder()
  {
    return $this->getPathToSave()."/$this->name";
  }

  // elimina los archivos de una doreccion
  public function rmdir_recursive($dir) {
      foreach(scandir($dir) as $file) {
          if ('.' === $file || '..' === $file) continue;
          if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
          else unlink("$dir/$file");
      }
      rmdir($dir);
  }

  function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

  public function duplicateImage()
  {
    $new = $this->replicate();
    $new->name = $this->generateToken();
    $new->save();
    $this->recurse_copy($this->getFolder(),$new->getFolder());
    $new->refresh();
    return $new;
  }

  public  static function tabletate($data = null) {
    return [
      
      'singular' => 'product',
      'name'  => 'Productos',

    ];

  }




}
