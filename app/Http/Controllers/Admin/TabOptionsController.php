<?php

namespace App\Http\Controllers\Admin;

use App\Repo\TabOptionsRepo;
use App\Repo\TabsRepo;
use App\TabOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TabOptionsController extends Controller
{
    //
    public function __construct( Request $request, TabOptionsRepo $tabOptionsRepo, TabsRepo $tabsRepo)
    {
        $this->request = $request;
        $this->tabOptionsRepo = $tabOptionsRepo;
        $this->tabsRepo = $tabsRepo;
    }

    public function options($id){
        $tab = $this->tabsRepo->getTabById($id);
        return view('admin.options.index')->with(compact('tab'));
    }

    public function ajaxOptions($id){
        if($this->request->ajax('GET')){

            $options = $this->tabOptionsRepo->getTabOptions($id,$this->request->start,$this->request->length,$this->request->search['value']);
            $optionsTotal = $this->tabOptionsRepo->getTotalTabs($id,$this->request->search['value']);
            $data = [];

            foreach($options as $option){
                $newdata = (object)[
                    'icon'=> $option->icon,
                    'name'=> $option->name,
                    'id'=> $option->id
                ];
                array_push($data,$newdata);
            }

            $result = (object)[
                "draw" => $this->request->draw,
                "recordsTotal" => $optionsTotal,
                "recordsFiltered" => $optionsTotal,
                "data" => $data
            ];

            return response()->json($result);
        }
    }

    public function getCreate($id){
        $tab = $this->tabsRepo->getTabById($id);
        return view('admin.options.create')->with(compact('tab'));
    }

    public function postCreate($id){
        //dd($this->request->icon);
        $tab = $this->tabsRepo->getTabById($id);
        if(!empty($tab)){
            $result = $this->tabOptionsRepo->create($tab->id,$this->request);
        }
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

    public function getEdit($tabId,$id){

        $tab = $this->tabsRepo->getTabById($tabId);
        $option = $this->tabOptionsRepo->getTabOptionById($id);
        return view('admin.options.edit')->with(compact('tab','option'));
    }

    public function postEdit($tabId,$id){

        $option = $this->tabOptionsRepo->getTabOptionById($id);
        if(!empty($option)) {
            $result = $this->tabOptionsRepo->edit($option, $this->request);
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

        $employer = $this->tabOptionsRepo->getTabOptionById($id);
        if (!empty($employer)) {
            $result = $this->tabOptionsRepo->delete($employer);
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
        $tabs = TabOption::where($param, '=', $this->request->$param)->get();
        if(!empty($id)){
            $tabs = TabOption::where($param, '=', $this->request->$param)->where('id', '!=', $id)->get();
        }
        if(count($tabs) > 0){
            return 'false';
        }else{
            return 'true';
        }
    }
}
