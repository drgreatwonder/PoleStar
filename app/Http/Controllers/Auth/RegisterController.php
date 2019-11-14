<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Intervention\Image\Facades\Image as Image;



class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'hobby' => ['string'],
            'country' => ['string'],
            'about_me' => ['string', 'max:500'],
            'avatar' => ['required']
        ]);

    //     if(request()->hasFile('avatar')) {

    //         request()->validator([

    //             'avatar' => 'file|image|max:5000'
    //         ]);

    //         $avatar->move(public_path().'/avatars/', $data);;
    //     }
    // }

    //     private function storeAvatar($user) {

    //         if(request()->has('avatar')) {

    //             $user->update([

    //                 'avatar' => request()->avatar->store('avatars', 'public'),
    //             ]);
    //         }
        }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $request = app('request');
        if($request->hasfile('avatar')){
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(300, 300)->save( public_path('/storage/avatars/' . $filename) );
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'hobby' => $data['hobby'],
            'country' => $data['country'],
            'about_me' => $data['about_me'],
            'avatar' => $data['avatar'],
        ]);
    }
}
