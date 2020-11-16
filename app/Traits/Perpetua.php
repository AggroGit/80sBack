<?php


namespace App\Traits;

use  App\Scratch;

/**
 * Trait for object that can be putted in a socket
 *
 */
trait Perpetua
{
  // cogemos los rasca y guaña y le desbloqueamos el siguiente
  public function giveRascaYGuaña()
  {
    // si todos ya estan usados se quitan y se dan de nuevo
    if($this->scratches()->wherePivot('used',true)->count()==$this->scratches()->count()) {
      // los eliminamos
      $this->scratches()->detach();
      // volvemos a poner
      $this->scratches()->attach(Scratch::all());
      //
      $this->save();

    }
    $this->refresh();
    // ahora devemos hacer available el primero, si es que tiene
    if($first = $this->scratches()->wherePivot('available',false)->orderBy('points','ASC')->first()) {
      //
      $first->pivot->available = true;
      //
      $first->pivot->save();
      //
      $first->save();
      $this->save();
    }
  }

  

  // a partir de un id de rasca y guanya se lo ponemos como used
  public function useRascaYGuanya($id)
  {
    if($toUse = $this->scratches()->wherePivot('used',false)->wherePivot('available',true)->find($id)) {
      $toUse->pivot->used = true;
      $toUse->pivot->save();
      $this->staPerpetuaPoints = $this->staPerpetuaPoints + $toUse->points;
      $this->save();
      return true;
    }
    return false;

  }











}
