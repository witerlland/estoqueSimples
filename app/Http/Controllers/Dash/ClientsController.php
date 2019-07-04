<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\QueryException;
use Illuminate\Support\Facades\DB;

use Auth;
use App\User;
use App\Models\Client;
use App\Models\Stock;

class ClientsController extends Controller{
    public function index(){
        return view('pages.clients');
    }

    public function get(Request $request, $id = 0){
        $token = $request->header('w-auth-token');
        if(
            !empty($token)      ||
            !is_string($token)
        ){

            try {
                
                $user = User::where('remember_token', '=', $token)->firstOrFail();

                if($id == 0){
                    return Response()->json(array(
                        'status'    => true,
                        'response'  => Client::where([
                            ['user_id', '=', $user->id]
                        ])->get([
                            'id', 'name', 'email', 'phone'
                        ])
                    ));
                }else{
                    return Response()->json(array(
                        'status'    => true,
                        'response'  => Client::where([
                            ['user_id', '=', $user->id],
                            ['id', '=', $id]
                        ])->firstOrFail()
                    ));
                }

            } catch (ModelNotFoundException $e) {
                return Response()->json(array(
                    'status'    => false,
                    'response'  => 'Erro ao buscar registros.',
                    'errorMsg'  => $e->getMessage()
                ));                
            }

        }else{ // Fim do if que verifica a existencia do token
            return Response()->json(array(
                'status'    => false,
                'response'  => 'O token de usuário não esta presente na requisição.'
            ));
        }
    }

    public function createOrUpdate(Request $request){
        // return Response()->json($request->id);
        $token = $request->header('w-auth-token');
        if(
            !empty($token)      ||
            !is_string($token)
        ){

            try {
                
                $user = User::where('remember_token', '=', $token)->firstOrFail();

                $client = Client::updateOrCreate(
                    ['id' => $request->id, 'user_id' => $user->id],
                    [
                        'name'          => $request->name,
                        'email'         => ($request->email == '') ? 'user@mail.com' : $request->email,
                        'phone'         => ($request->phone == '') ? '(77) 00000-0000' : $request->phone
                    ]
                );

                return Response()->json(array(
                    'status'    => true,
                    'response'  => $client->id
                ));

            } catch (ModelNotFoundException $e) {
                return Response()->json(array(
                    'status'    => false,
                    'response'  => 'Usuário relacionado ao token é inexistente.'
                ));                
            }

        }else{ // Fim do if que verifica a existencia do token
            return Response()->json(array(
                'status'    => false,
                'response'  => 'O token de usuário não esta presente na requisição.'
            ));
        }
    }
}