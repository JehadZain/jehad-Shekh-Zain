<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Hospital;


class Doctor extends Model
{
    protected $table = 'doctors';
    protected $fillable = ['name', 'title','hospitals_id', 'created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = true;

    public function hospital(){
        return $this -> belongsTo('App\Models\Hospital','hospitals_id','id');
    }


}
