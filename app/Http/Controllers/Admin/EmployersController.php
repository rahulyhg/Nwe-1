<?php

namespace App\Http\Controllers\Admin;

use App\CvTab;
use App\Employer;
use App\Repo\EmployerRepo;
use App\TabOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class EmployersController extends Controller
{
    //
    public function __construct( Request $request, EmployerRepo $employerRepo)
    {
        $this->request = $request;
        $this->employerRepo = $employerRepo;
    }

    public function employers(){
        return view('admin.employers.index');
    }

    public function ajaxEmployers(){
        if($this->request->ajax('GET')){

            $employers = $this->employerRepo->getEmployers($this->request->start,$this->request->length,$this->request->search['value']);
            $employersTotal = $this->employerRepo->getTotalEmployers($this->request->search['value']);
            $data = [];

            foreach($employers as $employer){
                $newdata = (object)[
                    'avatar'=> $employer->avatar,
                    'name' => $employer->name,
                    'mobile_number' => $employer->mobile_number,
                    'email' => $employer->email,
                    'company_name' => $employer->company_name,
                    'id'=> $employer->id
                ];
                array_push($data,$newdata);
            }

            $result = (object)[
                "draw" => $this->request->draw,
                "recordsTotal" => $employersTotal,
                "recordsFiltered" => $employersTotal,
                "data" => $data
            ];

            return response()->json($result);
        }
    }

    public function getCreate(){
        return view('admin.employers.create');
    }

    public function postCreate(){

        $result = $this->employerRepo->a_create($this->request);

        if($result){
            $this->request->session()->flash('alert-success', 'Success');
            $this->request->session()->flash('mg-success', 'Thêm mới thành công');
            return redirect('/employer/edit/'.$result);
        }else{
            $this->request->session()->flash('alert-danger', 'Error');
            $this->request->session()->flash('mg-danger', 'Thêm thất bại');
            return back();
        }
    }

    public function getEdit($id){
        $this->id = $id;
        if (Auth::guard('employers')->user()->isAdmin() ){
            $employer = $this->employerRepo->getEmployerById($id);
        }else{
            $employer = $this->employerRepo->getEmployerById(Auth::guard('employers')->user()->id);
        }
        
        if(empty($employer->tab_active)){
            $employer->tab_active = "[]";
        }
        $employer->tab_active = json_decode($employer->tab_active);
        $tabs = CvTab::where('user_type','like','%employer%')->with('options')->with(['rows' => function ($query) {
            $query->where('employer_id', '=', $this->id)->with('option')->orderBy('sort','asc');
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
        if(!empty($employer->tab_sort)){
            //$newTabs = [];
            foreach(json_decode($employer->tab_sort) as $key => $tab_id){
                foreach($tabs as $tab) {
                    if ($tab->id == $tab_id) {
                        $tab->sort = $key;
                    }
                }
            }
            $tabs = $tabs->sortBy('sort');
        }

        //dd($tabs);
//        if(count($tabs)){
//            foreach ($tabs as $tab){
//                if($tab->input_type == "select"){
//                    $options = TabOption::where('tab_id','=',$tab->id)->orderBy('name', 'asc')->get();
//                    $tab->options = $options;
//                }
//            }
//        }
        return view('admin.employers.edit')->with(compact('employer','tabs'));
    }

    public function postEdit($id){
        //dd($this->request->tabs[10]['options']['PWU5sBrWd']['icon']);
        //dd($this->request->all());
        if (Auth::guard('employers')->user()->isAdmin() ){
            $employer = $this->employerRepo->getEmployerById($id);
        }else{
            $employer = $this->employerRepo->getEmployerById(Auth::guard('employers')->user()->id);
        }
        if(!empty($employer)) {
            $result = $this->employerRepo->a_edit($employer, $this->request);
            if($result){
                $this->request->session()->flash('alert-success', 'Success');
                $this->request->session()->flash('mg-success', 'Sửa thành công');
                return back();
            }else{
                $this->request->session()->flash('alert-danger', 'Error');
                $this->request->session()->flash('mg-danger', 'Sửa thất bại');
                return back();
            }
        }else{
            $this->request->session()->flash('alert-danger', 'Error');
            $this->request->session()->flash('mg-danger', 'Sửa thất bại');
            return back();
        }
    }

    public function updateGallery($id){
        $status = 0;
        $errors = 0;
        $result = "";

        if (Auth::guard('employers')->user()->isAdmin() ){
            $employer = $this->employerRepo->getEmployerById($id);
        }else{
            $employer = $this->employerRepo->getEmployerById(Auth::guard('employers')->user()->id);
        }
        if(!empty($employer)) {
            $result = $this->employerRepo->updateGallery($employer, $this->request);
            if($result){
                $status = 1;
            }else{
                $status = 500;
                $errors = ['message'=>'not saved to the database'];
            }
        }else{
            $status = 400;
            $errors = ['message'=>'user not found'];
        }
        return response()->json(compact('status','errors','result'));
    }

    public function  deleteGallery($id){
        $status = 0;
        $errors = 0;
        //dd($this->request->image);
        if (Auth::guard('employers')->user()->isAdmin() ){
            $employer = $this->employerRepo->getEmployerById($id);
        }else{
            $employer = $this->employerRepo->getEmployerById(Auth::guard('employers')->user()->id);
        }
        if(!empty($employer)) {
            $result = $this->employerRepo->deleteGallery($employer, $this->request);
            if($result){
                $status = 1;
            }else{
                $status = 500;
                $errors = ['message'=>'not saved to the database'];
            }
        }else{
            $status = 400;
            $errors = ['message'=>'user not found'];
        }
        return response()->json(compact('status','errors'));
    }

    public function postValidate($param, $id = null){
        $employer = Employer::where($param, '=', $this->request->$param)->get();
        if(!empty($id)){
            $employer = Employer::where($param, '=', $this->request->$param)->where('id', '!=', $id)->get();
        }
        if(count($employer) > 0){
            return 'false';
        }else{
            return 'true';
        }
    }

    public function postDelete($id)
    {
        $status = 0;
        $errors = 0;

        $employer = $this->employerRepo->getEmployerById($id);
        if (!empty($employer)) {
            $result = $this->employerRepo->delete($employer);
            if($result){
                $status = 1;
            }else{
                $status = 500;
                $errors = ['message'=>'not saved to the database'];
            }
        }else{
            $status = 400;
            $errors = ['message'=>'job not found'];
        }
        return response()->json(compact('status','errors'));
    }

}
