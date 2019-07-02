<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\QueryException;

use Auth;
use App\User;
use App\Models\Product;

class ProductsController extends Controller
{
    public function index(){
        return view('pages.products');
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
                        'response'  => Product::where([
                            ['user_id', '=', $user->id]
                        ])->get()
                    ));
                }else{
                    return Response()->json(array(
                        'status'    => true,
                        'response'  => Product::where([
                            ['id', '=', $id],
                            ['user_id', '=', $user->id]
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

                return Response()->json(array(
                    'status'    => true,
                    'response'  => Product::updateOrCreate(
                        ['id' => $request->id, 'user_id' => $user->id],
                        [
                            'name'          => $request->name,
                            'description'   => $request->description,
                            'value'         => $request->value,
                            'brand'         => $request->brand
                        ]
                    )
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
