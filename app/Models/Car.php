<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    //

    public function getById($id){
        return $this->where('id',$id)->select('built','id','price','brand')->get();
    }
}
