<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CrudController extends Controller
{
    public function create() {

    return view('offers.create');
    }
     public function store(Request $request)
    {
        //validator
        $rules = $this -> getRules();
        $messages = $this -> getMessages();
        $validator = Validator::make($request -> all(),$rules,$messages);

        if($validator -> fails()){
            return redirect()->back()->withErrors($validator)->withInput($request -> all());
        }

        //insert
        Offer::create([
            'name'=> $request -> name,
            'price'=> $request -> price,
            'details'=> $request -> details ,
        ]);
        return redirect()->back()->with(['success'=>'تم الإضافة بنجاح']);
    }
    protected function getRules() {
        return $rules = [
            'name'=>'required|max:100|unique:offers,name' ,
            'price'=>'required|numeric' ,
            'details'=>'required' ,
        ];
    }
    protected function getMessages() {
       return $messages =[
            'name.required' =>__('messages.offer name required'),
            'name.unique' => __('messages.offer name unique'),
           'price.required' => 'الرقم مطلوب',
            'price.numeric' => 'no price number',
           'details.required'=> 'التفاصيل مطلوبة',
        ];
    }


}
