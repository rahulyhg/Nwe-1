<?php

namespace App\Http\Controllers\Admin;

use App\CvTab;
use App\Repo\TabsRepo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TabsController extends Controller
{
    //
    public function __construct( Request $request, TabsRepo $tabsRepo)
    {
        $this->request = $request;
        $this->tabsRepo = $tabsRepo;
    }

    // Admin
    public function tabs(){
        $tabs = CvTab::all();
        return view('admin.tabs.index')->with(compact('tabs'));
    }

    public function ajaxTabs(){
        if($this->request->ajax('GET')){

            $tabs = $this->tabsRepo->getTabs($this->request->start,$this->request->length,$this->request->search['value']);
            $tabsTotal = $this->tabsRepo->getTotalTabs($this->request->search['value']);
            $data = [];

            foreach($tabs as $tab){
                $newdata = (object)[
                    'name'=> $tab->name,
                    'user_type' => $tab->user_type,
                    'input_type' => (object)[
                        'value' => $tab->input_type,
                        'id' => $tab->id
                    ],
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
        return view('admin.tabs.create');
    }

    public function postCreate(){

        $result = $this->tabsRepo->create($this->request);

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

        $tab = $this->tabsRepo->getTabById($id);
//        if(!empty($tab)){
//            $tab->user_type = json_decode($tab->user_type);
//        }
        return view('admin.tabs.edit')->with(compact('tab'));
    }

    public function postEdit($id){
        $tab = $this->tabsRepo->getTabById($id);
        if(!empty($tab)) {
            $result = $this->tabsRepo->edit($tab, $this->request);
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
