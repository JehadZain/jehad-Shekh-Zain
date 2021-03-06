<?php

namespace App\Http\Controllers\Relation;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Phone;
use App\Models\Service;
use App\User;
use Illuminate\Http\Request;

class RelationsController extends Controller
{
    public function hasOneRelation()
    {
        $user = \App\User::with(['phone' => function ($q) {
            $q->select('code', 'phone', 'user_id');
        }])->find(9);

        return response()->json($user);
    }

    public function hasOneRelationReserve()
    {
        $phone = Phone::with('user')->find(1);
        //make some attribute visible
        $phone->makeVisible(['user_id']);
        //  $phone->makeHidden(['code']);
        return $phone;
        //get all data (phone + user)

    }

    public function getUserHasPhone()
    {
        // return User::whereHas('phone')-> get();

    }

    public function getUserNotHasPhone()
    {
        return User::whereDoesntHave('phone')->get();
    }

    public function getUserWhereHasPhoneWithCondition()
    {
        return User::whereHas('phone', function ($q) {
            $q->where('code', '0963');
        })->get();
    }

##################  one To many relationship methods #####################
    public function getHospitalDoctors()
    {
        $hospital = Hospital::with('doctors')->find(1); // Hospital::where('id',1)->first();

        $doctors = $hospital->doctors;

        /*foreach ($doctors as $doctor){
            echo $doctor -> name.'<br>';
        }*/

        $doctor = Doctor::find(3);
        return $doctor->hospital->name;
    }

    public function hospitals()
    {
        $hospitals = Hospital::select('id', 'name', 'address')->get()->all();
        return view('doctors.hospitals', compact('hospitals'));
    }

    public function doctors($hospital_id)
    {
        $hospital = Hospital::find($hospital_id);
        $doctors = $hospital->doctors;
        return view('doctors.doctors', compact('doctors'));
    }

    public function hospitalsHasDoctor()
    {
        $hospitals = Hospital::whereHas('doctors')->get();
        return $hospitals;
    }

    public function hospitalsHasOnlyMaleDoctors()
    {
        $hospitals = Hospital::with('doctors')->whereHas('doctors', function ($q) {
            $q->where('gender', 1);
        })->get();
        return $hospitals;
    }

    public function hospitals_not_has_doctors()
    {
        return Hospital::whereDoesntHave('doctors')->get();
    }

    public function deleteHospital($hospital_id)
    {
        $hospitals = Hospital::find($hospital_id);
        if (!$hospitals) {
            return abort('410');
        }
        $hospitals->doctors()->delete();
        $hospitals->delete();
        return redirect()->route('hospital.all');
    }

    public function getDoctorServices()
    {
        $doctor = Doctor::find(5);
        return $doctor->services;
    }

    public function getServiceDoctors()
    {
        $service = Service::find(1);
        return $service->doctors;
    }

    public function getDoctorServicesById($doctorId)
    {
        $doctor = Doctor::find($doctorId);
        $services = $doctor->services;  //doctor services
        $doctors = Doctor::select('id', 'name')->get();
        $allServices = Service::select('id', 'name')->get(); // all db serves
        return view('doctors.services', compact('services', 'doctors', 'allServices'));
    }

    public function saveServicesToDoctors(Request $request)
    {
        $doctor = Doctor::find($request->doctor_id);
        if (!$doctor)
            return abort('404');
        //  $doctor ->services()-> attach($request -> servicesId);  // many to many insert to database
        //  $doctor ->services()-> sync($request -> servicesId);
        $doctor->services()->syncWithoutDetaching($request->servicesId);
        return 'success';
    }

    public function getPatientDoctor()
    {
        $patient = Patient::find(2);
        return $patient->doctor;
    }

    public function getCountryDoctor()
    {
       return $doctors =  Doctor::select('id','name','gender') ->get();

        /*foreach ($doctors as $doctor){
            $doctor -> gender = $doctor -> gender == 1 ? 'male' : 'famle';
        }
        return $doctors;*/
    }

}
