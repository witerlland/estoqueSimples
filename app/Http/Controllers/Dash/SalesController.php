<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\QueryException;
use Illuminate\Support\Facades\DB;

use Auth;
use App\User;
use App\Models\Basket;
use App\Models\Sale;

class SalesController extends Controller{
    public function index(){
        return view('pages.sales');
    }

    /**
     * Abre a venda criando uma nova venda na tabela sel. 
    */
    public function create(Request $request){
        // return Response()->json($request->sale['client_id']);
        // return Response()->json($request->basket[0]['amount']);
        $token = $request->header('w-auth-token');
        if(
            !empty($token)      ||
            !is_string($token)
        ){

            try {
                
                $user = User::where('remember_token', '=', $token)->firstOrFail();

                $sale = Sale::create([
                        'user_id'       => $user->id,
                        'client_id'     => $request->sale['client_id'],
                        'total_value'   => $request->sale['total_value']
                ]);

                foreach ($request->basket as $key) {
                    Basket::create([
                        'sale_id'       => $sale->id,
                        'product_id'    => $key['product_id'],
                        'amount'        => $key['amount'],
                        'total_value'   => $key['total_value']
                    ]);
                }

                return Response()->json(array(
                    'status'    => true,
                    'response'  => $sale->id
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
                        'response'  => DB::select(
                            DB::raw("
                                select
                                    sale.id,
                                    sale.total_value,
                                    client.name,
                                    client.email,
                                    client.phone,
                                    sale.created_at
                                from
                                    sale
                                join
                                    client on (sale.client_id = client.id)
                                where
                                    sale.user_id = $user->id
                            ")
                        )
                    ));
                }else{
                    return Response()->json(array(
                        'status'    => true,
                        'response'  => array(
                            'sale'   => DB::select(
                                DB::raw("
                                    select
                                        sale.id,
                                        sale.total_value,
                                        client.name,
                                        client.email,
                                        client.phone,
                                        sale.created_at
                                    from
                                        sale
                                    join
                                        client on (sale.client_id = client.id)
                                    where
                                        sale.user_id = $user->id and sale.id = $id
                                ")
                            ),
                            'basket' => DB::select(
                                DB::raw("
                                    select
                                        basket.id,
                                        product.name,
                                        product.description,
                                        product.brand,
                                        product.value,
                                        basket.amount,
                                        basket.total_value
                                    from
                                        sale
                                    join
                                        basket on (sale.id = basket.sale_id)
                                    join
                                        product on (basket.product_id = product.id)
                                    where
                                        sale.user_id = $user->id and sale.id = $id
                                ")
                            )
                        )
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
}