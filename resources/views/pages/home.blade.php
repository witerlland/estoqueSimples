@extends('layouts.app')

@section('content')
    <div class="row py-4">
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h4 class="card-title">Title</h4>
                </div>
                <div class="card-footer text-center">
                    Novas vendas
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h4 class="card-title">Title</h4>
                </div>
                <div class="card-footer text-center">
                    Novos clientes
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h4 class="card-title">Title</h4>
                </div>
                <div class="card-footer text-center">
                    Novos produtos
                </div>
            </div>
        </div>
    </div>
@endsection
