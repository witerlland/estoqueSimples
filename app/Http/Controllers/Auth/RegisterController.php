<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

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
     * @return \Illuminate\ContrTacts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function new(Request $request){
        $data = $request->data;
        if(!empty($data)){

            if($this->validateNewUser($data)){
                try {
                    $user = User::create([
                        'name'      => $data['user'],
                        'full_name' => $data['user'],
                        'email'     => $data['mail'],
                        'password'  => Hash::make($data['pass']),
                        'type'      => 'basic',
                        'active'    => 'T'
                    ]);

                    if($user){
                        return json_encode(array(
                            "status"    => true,
                            "response"  => 'Usuario cadastrado com sucesso.'
                        ));
                    }else{
                        return json_encode(array(
                            "status"    => false,
                            "response"  => 'Erro ao cadastrar usuario.'
                        ));
                    }

                } catch (\PDOException $e) {
                    //throw $th;
                    return json_encode(array(
                        'status'    => false,
                        'response'  => $e->getMessage()
                    ));
                }
            }else{
                return json_encode(array(
                    "status"    => false,
                    "response"  => 'Algum campo não corresponde ao esperado.'
                ));
            }

        }else{
            return json_encode(array(
                'status'    => false,
                'response'  => 'Nenhum dado enviado para cadastro de usuario.'
            ));
        }
    }

    private function validateNewUser(array $user) : bool {
        $errors = array();
        if(
            !is_string($user['user'])   ||
            empty($user['user'])        
        ){
            array_push($errors, 'user');
        }

        if(
            !is_string($user['pass'])   ||
            empty($user['pass'])        ||
            strlen($user['pass']) < 8
        ){
            array_push($errors, 'pass');
        }

        if(
            !is_string($user['mail'])   ||
            empty($user['mail'])        
        ){
            array_push($errors, 'mail');
        }

        $errorsCount = count($errors);

        if($errorsCount > 0){
            return false;
        }else{
            return true;
        }
    }
}
