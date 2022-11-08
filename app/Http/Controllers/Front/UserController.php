<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function loginRegister(){
        return view('front.users.login_register'); 
    }

    public function userRegister(Request $request){
        if($request->ajax()){
            $data = $request->all(); 
            // echo "<pre>"; print_r($data); die;
            
            // Register the user 
            $user = new User(); 
            $user->name =  $data['name']; 
            $user->mobile= $data['mobile']; 
            $user->email=  $data['email']; 
            $user->password = bcrypt($data['password']); 
            $user->status = 1;
            $user->save();

            if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
                $redirectTo = url('cart'); 
                return response()->json([
                    'url' => $redirectTo
                ]);
            }
        }
    }
}
