<?php

namespace App\Http\Controllers\Admin;

use App\CvTab;
use App\Repo\UtilityRepo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UtilitiesController extends Controller
{
    //
    public function __construct( Request $request, UtilityRepo $utilityRepo)
    {
        $this->request = $request;
        $this->utilityRepo = $utilityRepo;
    }

    // Admin
    public function utilities(){
        $tabs = CvTab::all();
        return view('admin.utilities.index')->with(compact('tabs'));
    }

    public function ajaxUtilities(){
        if($this->request->ajax('GET')){

            $tabs = $this->utilityRepo->getUtilities($this->request->start,$this->request->length,$this->request->search['value']);
            $tabsTotal = $this->utilityRepo->getTotalUtilities($this->request->search['value']);
            $data = [];

            foreach($tabs as $tab){
                $newdata = (object)[
                    'name'=> $tab->name,
                    'id'=> $tab->id
                ];
                array_push($data,$newdata);
            }

            $result = (object)[
                "draw" => $this->request->draw,
                "recordsTotal" => $tabsTotal,
                "recordsFiltered" => $tabsTotal,
                "data" => $data
            ];

            return response()->json($result);
        }
    }

    public function getCreate(){
        return view('admin.utilities.create');
    }

    public function postCreate(){

        $result = $this->utilityRepo->create($this->request);

        if($result){
            $this->request->session()->flash('alert-success', 'Success');
            $this->request->session()->flash('mg-success', 'Thêm mới thành công');
            return back();
        }else{
            $this->request->session()->flash('alert-danger', 'Error');
            $this->request->session()->flash('mg-danger', 'Thêm thất bại');
            return back();
        }
    }

    public function getEdit($id){

        $tab = $this->utilityRepo->getUtilityById($id);
//        if(!empty($tab)){
//            $tab->user_type = json_decode($tab->user_type);
//        }
        return view('admin.utilities.edit')->with(compact('tab'));
    }

    public function postEdit($id){
        $tab = $this->utilityRepo->getUtilityById($id);
        if(!empty($tab)) {
            $result = $this->utilityRepo->edit($tab, $this->request);
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

    public function postDelete($id)
    {
        $status = 0;
        $errors = 0;

        $utility = $this->utilityRepo->getUtilityById($id);
        if (!empty($utility)) {
            $result = $this->utilityRepo->delete($utility);
            if($result){
                $status = 1;
            }else{
                $status = 500;
                $errors = ['message'=>'not saved to the database'];
            }
        }else{
            $status = 400;
            $errors = ['message'=>'option not found'];
        }
        return response()->json(compact('status','errors'));
    }

    public function postValidate($param,$id = null){
        $tabs = CvTab::where($param, '=', $this->request->$param)->get();
        if(!empty($id)){
            $tabs = CvTab::where($param, '=', $this->request->$param)->where('id', '!=', $id)->get();
        }
        if(count($tabs) > 0){
            return 'false';
        }else{
            return 'true';
        }
    }
}
