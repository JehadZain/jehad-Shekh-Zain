<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;


class Hospital extends Model
{
    protected $table = 'hospitals';
    protected $fillable = ['name', 'address', '	country_id','created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = true;

    public function doctors()
    {
        return $this->hasMany('App\Models\Doctor', 'hospitals_id', 'id');
    }
}
