<?php

namespace App\Http\Controllers\Admin;

use App\Repo\UserRepo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    //
    public function __construct( Request $request, UserRepo $userRepo)
    {
        $this->request = $request;
        $this->userRepo = $userRepo;
    }

    public function users(){

        return view('admin.users.index');
    }

    public function ajaxUsers(){
        if($this->request->ajax('GET')){

            $users = $this->userRepo->getUsers($this->request->start,$this->request->length,$this->request->search['value']);
            $usersTotal = $this->userRepo->getTotalUsers($this->request->search['value']);
            $data = [];

            foreach($users as $user){
                $newdata = (object)[
                    'avatar'=> $user->avatar,
                    'name' => $user->name,
                    'mobile_number' => $user->mobile_number,
                    'email' => $user->email,
                    'slug'=> $user->slug,
                    'id'=> $user->id
                ];
                array_push($data,$newdata);
            }

            $result = (object)[
                "draw" => $this->request->draw,
                "recordsTotal" => $usersTotal,
                "recordsFiltered" => $usersTotal,
                "data" => $data
            ];

            return response()->json($result);
        }
    }
}
