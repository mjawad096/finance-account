<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
   /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
   protected $dates = ['date'];

   /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
   protected $guarded = [];
}
