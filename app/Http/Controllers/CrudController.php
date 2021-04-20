<?php

namespace App\Http\Controllers;

use App\Events\VideoViewer;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Models\Video;
use App\Traits\OfferTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


class CrudController extends Controller
{

    use OfferTrait;
    public function create() {

    return view('offers.create');
    }
     public function store(OfferRequest $request)
    {
        /*//validator
       // $rules = $this -> getRules();
       // $messages = $this -> getMessages();
        $validator = Validator::make($request -> all(),$rules,$messages);

        if($validator -> fails()){
            return redirect()->back()->withErrors($validator)->withInput($request -> all());
        }*/

        $file_name = $this -> saveImage($request -> photo , 'images/offers');
        //insert
        Offer::create([
            'photo' => $file_name ,
            'name_ar'=> $request -> name_ar,
            'name_en'=> $request -> name_en,
            'price'=> $request -> price,
            'details_ar'=> $request -> details_ar,
            'details_en'=> $request -> details_en,
        ]);
        return redirect()->back()->with(['success'=>'تم الإضافة بنجاح']);
    }
    /*protected function getRules() {
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
    }*/
    public function getAllOffers(){
         /* $offers = Offer::select(
              'id' ,
              'price' ,
              'photo',
              'name_'.LaravelLocalization::getCurrentLocale() .' as name',
              'details_'.LaravelLocalization::getCurrentLocale() .' as details'
          )-> get();*/
            ######### paginate #########
           $offers = Offer::select(
              'id' ,
              'price' ,
              'photo',
              'name_'.LaravelLocalization::getCurrentLocale() .' as name',
              'details_'.LaravelLocalization::getCurrentLocale() .' as details'
          )-> paginate(PAGINATION_COUNT);

        return view('offers.paginations',compact('offers'));
    }
    public function editOffer($offer_id){

       // Offer::findOrFail($offer_id);

        $offer = offer::find($offer_id);

        if(!$offer)
            return redirect() -> back();

       $offer =  Offer::select('id','name_ar' , 'name_en' , 'details_ar' , 'details_en','price') -> find($offer_id);
        return view('offers.edit',compact('offer'));

    }
    public function delete($offer_id){
        //check id
       $offer = Offer::find($offer_id);
       if(!$offer)
           return redirect() -> back() -> with(['error' => __('messages.offer not exist')]);

       $offer -> delete();

       return redirect() -> route('offers.all') -> with(['success' => __('messages.offer deleted successfully')]);
    }
    public function updateOffer(OfferRequest $request,$offer_id){
        //validation
        //chek
        $offer = Offer::where('id', $offer_id)->first();
        $offer =  Offer::select('id','name_ar' , 'name_en' , 'details_ar' , 'details_en','price') -> find($offer_id);
        if(!$offer)
            return redirect() -> back();
        //update data
     //   $offer -> update([$request -> all()]);
         $offer -> update([
             'name_ar' => $request -> name_ar ,
             'name_en' => $request -> name_en ,
             'price' => $request -> price ,
             'details_ar' => $request -> details_ar ,
             'details_en' => $request -> details_en ,
       ]);

        return redirect() -> back() -> with(['success'=>'تم التحديث بنجاح']);


    }
    public function getVideo(){

        $video = Video::first();
        event(new VideoViewer($video));
        return view('video') -> with('video', $video);
    }
    public function getAllInactiveOffers(){
       return $inactiveOffers =  Offer::get();
    }
}
