<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|max:191|Unique:users|E-Mail',
            'password' => 'required|max:191',
        ],[
            'name.required'=>'هذا ابحقل مطلوب القبض عليه ',
            'email.email'=> 'do it again',

        ]);

        if ($validator->fails()) {
            return $validator->errors();
        } else {
            $new_user = new User();
            $new_user->name = $request->name;
            $new_user->email = $request->email;
            $new_user->password = Hash::make($request->password);
            $new_user->api_token = Str::random(60);
            $new_user->save();

            return $new_user;
        }

    }

    public function login(Request $r)
    {
        if(auth()->attempt(['email'=>$r->email,
            'password'=>$r->password]))
        {
           $user=auth()->user();
           $user->api_token=Str::random(60);
           $user->save();
           return $user;
        }
        else
        {
            return 'no';
        }
    }
}
