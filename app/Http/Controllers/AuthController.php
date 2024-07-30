<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    
    private $userRepository;
    private $roleRepository;

    public function __construct(UserRepository $userRepository,RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }
    public function index(){
        return view("auth.login");
    }
    
    public function checkLogin(LoginRequest $request){
        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials)){
            return response()->json(["message"=>"Login Successful",'success' => true],200);
        }
        return response()->json(["message"=>"Invalid Credentials",'success' => false],401);
    }

    public function register(){
        $roleArr = $this->roleRepository->getDDArray("name","id");
        return view("auth.register",compact('roleArr'));
    }

    public function doRegister(RegisterRequest $request)
    {        

        $user =$this->userRepository->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $role_id = $request->get('role_id');
        $role = Role::find($role_id);
        $user->roles()->attach($role);
        
        return response()->json([
            'data' => ['user' => $user],
            'success' => true,
            'message' => 'Registration successful.'
        ]);
        
    }

    public function logout(Request $request)
    {
        
        if ($request->ajax()) {
            Auth::logout();
            return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Something went wrong.'], 401);
    }
}
