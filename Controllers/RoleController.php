<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    public function create_role(RoleRequest $req){
        $req->validate([
            'name'=>'required|string',
            'permission'=>'nullable'
        ]);
        $role = Role::create(
            [
                'name'=>$req->name,
                'permission'=>json_encode($req->permission)
            ]
        );
        return response()->json([
            'message'=>'role created successfully'
        ]);

    }
    public function index(){
         $role = Role::get();
    }
    public function process(Role $role, Request $req){

        $role->name = $req->name;
        $role->permission = json_encode($req->permission);
        $role->save();
    }
    public function saveRole(RoleRequest $roleRequest){
        try{
        $role = $this->proccess(new Role,$roleRequest);
        if($role){
            return 'Role created successfully';
        }}catch(Exception $ex){
            return 'role could not be created';
        }


    }
}
