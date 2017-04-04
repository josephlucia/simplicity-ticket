<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Domain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
        $domain = substr(strrchr($data['email'], "@"), 1);

        if(Domain::allowed($domain)) {
            return Validator::make($data, [
                'name' => 'required|max:100',
                'email' => 'required|email|max:100|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);
        } else {
            $messages = [
                'required' => 'This email domain is not permitted to register to this application.',
            ];
            return Validator::make($data, [
                'domain' => 'required'
            ], $messages);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'role' => 'user',
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
