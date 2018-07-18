<?php

namespace App\Http\Controllers\Client;

use App\CvTab;
use App\Job;
use App\Employer;
use App\Repo\EmployerRepo;
use App\TabOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class EmployerController extends Controller
{
    //
    public function __construct( Request $request, EmployerRepo $employerRepo)
    {
        $this->request = $request;
        $this->employerRepo = $employerRepo;
    
    }

    public function postEdit(){
        //dd($this->request->tabs[10]['options']['PWU5sBrWd']['icon']);
        //dd($this->request->all());

        $rules = array(
            'avatar' => 'mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:employers,email,'.Auth::guard('employers')->user()->id,
            'mobile_number' => 'required|max:20|unique:employers,mobile_number,'.Auth::guard('employers')->user()->id,
            'position' => 'max:255',
            //'job_id' => 'required',
        );
        
        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
       
        $employer = $this->employerRepo->getEmployerById(Auth::guard('employers')->user()->id);
       
        if(!empty($employer)) {
            $result = $this->employerRepo->update($employer, $this->request);
            if($result){
                $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.success("Cập nhật thành công");})</script>');
                return back();
            }else{
                $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.error("Cập nhật thất bại");})</script>');
                return back();
            }
        }else{
            $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.error("Cập nhật thất bại");})</script>');
            return back();
        }
    }

    public function getInfoCompany(){

        $user = Auth::guard('employers')->user();
        $role = 'employer';

        if($user){
            if(empty($user->tab_active)){
                $user->tab_active = "[]";
            }
            $user->tab_active = json_decode($user->tab_active);
            $tabs = CvTab::where('user_type','like','%'.$role.'%')->with('options')->with(['rows' => function ($query) use($user,$role){
                $query->where($role.'_id', '=', $user->id)->with('option')->orderBy('sort','asc');
            }])->orderBy('name', 'asc')->get();
            if(!empty($tabs)) {
                foreach ($tabs as $tab) {
                    if (!empty($tab->options)) {
                        foreach ($tab->options as $option) {

                            if (!empty($tab->rows)) {
                                foreach ($tab->rows as $row) {
                                    if ($option->id == $row->option_id) {
                                        $option->disabled = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($user->tab_sort)){
                //$newTabs = [];
                foreach(json_decode($user->tab_sort) as $key => $tab_id){
                    foreach($tabs as $tab) {
                        if ($tab->id == $tab_id) {
                            $tab->sort = $key;
                        }
                    }
                }
                $tabs = $tabs->sortBy('sort');
            }
        }else{
            abort(404);
        }
        

        return view('user.employer_info_company')->with(compact('user','tabs'));
    }

    public function postInfoCompany(){
        //dd($this->request->tabs[10]['options']['PWU5sBrWd']['icon']);
        //dd($this->request->all());

        $rules = array(
            'company_name' => 'required|max:255',
            'company_mobile' => 'max:255',
            'company_address' => 'max:255',
            'company_activity' => 'max:255'
            //'job_id' => 'required',
        );
        
        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
       
        $employer = $this->employerRepo->getEmployerById(Auth::guard('employers')->user()->id);
       
        if(!empty($employer)) {
            $result = $this->employerRepo->updateInfo($employer, $this->request);
            if($result){
                $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.success("Cập nhật thành công");})</script>');
                return back();
            }else{
                $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.error("Cập nhật thất bại");})</script>');
                return back();
            }
        }else{
            $this->request->session()->flash('alert', '<script>$(document).ready(function(){alertify.error("Cập nhật thất bại");})</script>');
            return back();
        }
    }

    
}