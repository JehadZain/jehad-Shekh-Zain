<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

class UserController extends Controller
{
    public function showAdminName(){
        return "Jehad123";
    }
    public function getIndex(){

        $obj = new \stdClass();
        $obj -> name = 'jehad';
        $obj -> id = 5;
        $obj -> gender = 'male';

        return view('welcome' ) -> with('name','hhh');
    }
}
