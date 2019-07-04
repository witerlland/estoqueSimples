<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\QueryException;
use Illuminate\Support\Facades\DB;

use Auth;
use App\User;
use App\Models\Product;
use App\Models\Stock;

class ProductsController extends Controller{
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
                        'response'  => DB::select(
                            DB::raw("
                                select 
                                    product.id, 
                                    product.name, 
                                    product.description, 
                                    product.value, 
                                    product.brand,
                                    stok.stok as stock
                                from 
                                    product
                                join
                                    stok on (product.id = stok.product_id)
                                where 
                                    stok.id = (select max(stok.id) from stok where stok.product_id = product.id)
                                and
                                    product.user_id = $user->id
                            ")
                        )
                    ));
                }else{
                    return Response()->json(array(
                        'status'    => true,
                        'response'  => DB::select(
                            DB::raw("
                                select 
                                    product.id, 
                                    product.name, 
                                    product.description, 
                                    product.value, 
                                    product.brand,
                                    product.created_at,
                                    product.updated_at,
                                    stok.stok as stock,
                                    stok.created_at as stock_created_at
                                from 
                                    product
                                join
                                    stok on (product.id = stok.product_id)
                                where 
                                    stok.id = (select max(stok.id) from stok where stok.product_id = product.id)
                                and
                                    product.user_id = $user->id
                                and 
                                    product.id = $id
                            ")
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

    public function createOrUpdate(Request $request){
        // return Response()->json($request->id);
        $token = $request->header('w-auth-token');
        if(
            !empty($token)      ||
            !is_string($token)
        ){

            try {
                
                $user = User::where('remember_token', '=', $token)->firstOrFail();

                $product = Product::updateOrCreate(
                    ['id' => $request->id, 'user_id' => $user->id],
                    [
                        'name'          => $request->name,
                        'description'   => ($request->description) ? 'Sem descrição' : $request->description,
                        'value'         => $request->value,
                        'brand'         => ($request->brand == '') ? 'Sem marca' : $request->brand
                    ]
                );

                $stock = Stock::create(
                    [
                        'user_id' => $user->id, 
                        'product_id' => $product->id,
                        'stok' => $request->stock
                    ]
                );

                return Response()->json(array(
                    'status'    => true,
                    'response'  => $product->id
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
