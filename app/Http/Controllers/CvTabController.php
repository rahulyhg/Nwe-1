<?php

namespace App\Http\Controllers;

use App\CvTab;
use App\CvRow;
use App\Job;
use App\Repo\TabsRepo;
use App\Repo\EmployerRepo;
use App\Repo\UserRepo;
use Illuminate\Http\Request;

class CvTabController extends Controller
{
    //
    public function __construct( Request $request, TabsRepo $tabsRepo, EmployerRepo $employerRepo, UserRepo $modelRepo)
    {
        $this->request = $request;
        $this->tabsRepo = $tabsRepo;
     	$this->employerRepo = $employerRepo;
     	$this->userRepo = $modelRepo;
    }

    // Api
    public function getCvTabs($id = null){
    	$status = 0;
    	$errors = (object)[];
    	$tabs = (object)[];
    	$tab_active = [];
    	$tab_sort = [];

    	$rol = $this->request['user']['rol'];
		$sub = $this->request['user']['sub'];

    	if($rol == 'employer'){
    		$model = $this->employerRepo->getEmployerById($sub);
    	}else if($rol == 'user'){
    		$model = $this->userRepo->getUserById($sub);
    	}
    	if(!empty($id)){
    		$model = Job::where('id','=',$id)->first();
			if(!empty($model)){
				$rol = 'job';
				$sub = $id;
			}
    	}
        
        if(!empty($model)){
        	if(empty($model->tab_active)){
	            $tab_active = "[]";
	        }
       		$tab_active = json_decode($model->tab_active);
       		if(empty($model->tab_sort)){
	            $tab_sort = "[]";
	        }
       		$tab_sort = json_decode($model->tab_sort);

        	$tabs = CvTab::where('user_type','like','%'.$rol.'%')->with('options')->with(['rows' => function ($query) use ($sub,$rol) {
	            $query->where($rol.'_id', '=', $sub)->with('option')->orderBy('sort','asc');
	        }])->orderBy('name', 'asc')->get();
	        $status = 1; 
        }else{
			$errors = ['message'=>['other'=>'not found']];
		}
        
        return response()->json(compact('status','errors','tabs','tab_active','tab_sort'));
    }

    public function postRows($id = null){
    	$status = 0;
    	$errors = (object)[];
    	$tabs = (object)[];
    	$tab_active = [];
    	$tab_sort = [];

    	$rol = $this->request['user']['rol'];
		$sub = $this->request['user']['sub'];

    	if($rol == 'employer'){
    		$model = $this->employerRepo->getEmployerById($sub);
    	}else if($rol == 'user'){
    		$model = $this->userRepo->getUserById($sub);
    	}
    	if(!empty($id) && $rol == 'employer'){
    		$model = Job::where('id','=',$id)->where('employer_id','=',$sub)->first();
			if(!empty($model)){
				$rol = 'job';
				$sub = $id;
			}
    	}

    	$rules = array(
            // 'email' => 'required|email',
            'id' => 'required',
            'tab_id' => 'required',
        );
        $tab = CvTab::find($this->request->tab_id);
        if(!empty($tab) && !empty($tab['input_type'])){
            if($tab['input_type'] == "select"){
                $rules['option_id'] = 'required';
            }
            if($tab['input_type'] == "text"){
                $rules['name'] = 'required';
            }
        }

        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            $errorsMessage = $validator->errors();
            $status = 400;
            $errors = ['message'=> $errorsMessage];
        } else {
        	
	        	if(!empty($tab)){
	        		if(!empty($model)){
		        	$_row = CvRow::where('id','=',$this->request->id)->where($rol.'_id','=',$sub)->first();
	                if(empty($_row) && $this->request->id == "0"){
	                    $_row = new CvRow;
	                }

	                if(!empty($tab['input_type'])){
                        if($tab['input_type'] == "select"){
                            $_row->option_id = $this->request->option_id;
                        }
                        if($tab['input_type'] == "text"){
                            $_row->name = $this->request->name;

                        }
                    }
                    $_row->description = $this->request->description;
                    $_row->date_start = $this->request->date_start;
                    $_row->date_end = $this->request->date_end;
                    // $_row->sort = $rowSort;
                    //$_row->date_end = (!empty($request->date_end) && $request->date_end !="null")?$request->date_end:null;
                    $_row->cv_tab_id = $this->request->tab_id;
                    $_row[$rol.'_id'] = $model->id;
                    
                    if($_row->save()){
                        if(!empty($row['icon']) ){
                            $file =  $row['icon'];
                            $path = 'uploads/'.$rol.'s/'.$model->id.'/';
                            $modifiedFileName = time().'-'.$file->getClientOriginalName();
                            if($file->move($path,$modifiedFileName)){
                                $oldFile = str_replace(config('app.api_url'),'',$_row->icon);
                                if(!empty($_row->icon) && is_file($oldFile)){
                                    unlink($oldFile);
                                }
                                $_row->icon = $path.$modifiedFileName;
                                $_row->save();
                            }
                        }
                    }

		        }else{
					$errors = ['message'=>['other'=>'not found']];
				}
        	}else{
        		$errors = ['message'=>['other'=>'Tab not found']];
        	}
        }

	}
}
