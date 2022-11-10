<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDO;

class UserController extends Controller
{
    public function loginRegister(){
        return view('front.users.login_register'); 
    }

    public function userRegister(Request $request){
        if($request->ajax()){
            $data = $request->all(); 
            // echo "<pre>"; print_r($data); die;
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100', 
                'mobile' => 'required|numeric|digits:10', 
                'email' => 'required|email|max:150|unique:users', 
                'password' => 'required|min:6', 
                'accept' => 'required'
            ], 
            [
                'accept.required' => 'Please accept our Terms and Conditions' 
            ]
        );

            if($validator->passes()){
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
                    // return response()->json([
                    //     'url' => $redirectTo
                    // ]);
                    return response()->json(['type' => 'success', 'url' => $redirectTo]);
                }
            }else{
                return response()->json(['type' => 'error', 'errors' => $validator->messages()]);
            }  
        }
    }

    // Logout 
    public function userLogout(){
        Auth::logout();
        return redirect('/'); 
    }
}
