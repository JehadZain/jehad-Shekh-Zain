<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Hospital;


class Doctor extends Model
{
    protected $table = 'doctors';
    protected $fillable = ['name', 'title', 'hospitals_id', 'medical_id','gender', 'created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at', 'pivot'];
    public $timestamps = true;

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital', 'hospitals_id', 'id');
    }

    public function services()
    {
        return $this->belongsToMany('App\Models\Service', 'doctor_service', 'doctor_id', 'service_id', 'id', 'id');
    }
    public function getGenderAttribute($val){
        return $val == 1 ? 'male' : 'famle';
    }

}
