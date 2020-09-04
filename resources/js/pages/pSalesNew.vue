<template>
    <div class="container-fluid py-4">
        <div class="container">
            <div v-show="alerts.show" :class="alerts.class" class="alert alert-dismissible fade show" role="alert">
                {{alerts.text}}
            </div>
        </div>
        <div class="row" >
            <div class="col-md-4" >
                <div class="row" >
                    <div class="col-12" >
                        <div class="form-group">
                            <label for="userName">Cliente</label>
                            <select class="form-control" v-model="newSale.sale.client_id" >
                                <option v-for="client in newSaleModalContents.clients" :value="client.id" :key="client.id" >{{client.name}}</option>
                            </select>
                            <!-- <input v-model="newSale.name" type="text" class="form-control" > -->
                        </div>
                    </div>
                    <div class="col-12" >
                        <div class="form-group">
                            <label for="userName">Valor total</label>
                            <input v-model="newSale.sale.total_value" disabled type="text" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a href="#" @click.prevent="addProductToBasket()" class="btn btn-dark btn-block" >Adicionar produto</a>
                    </div>

                    <div class="col-md-6" >
                        <div class="form-group">
                            <label for="userName">Cliente</label>
                            <select class="form-control" v-model="newSale.basketProduct.id" >
                                <option v-for="product in newSaleModalContents.products" @click.prevent="newSale.basketProduct.value = product.value" :value="product.id" :key="product.id" >{{product.name}}</option>
                            </select>
                            <!-- <input v-model="newSale.name" type="text" class="form-control" > -->
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div class="form-group">
                            <label for="userName">Valor</label>
                            <input v-model="newSale.basketProduct.value" disabled type="text" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div class="form-group">
                            <label for="userName">Quantidade</label>
                            <input v-model="newSale.basketProduct.amount" @change="productTotalValue()" type="number" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div class="form-group">
                            <label for="userName">Total</label>
                            <input v-model="newSale.basketProduct.total_value" disabled type="text" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row" > 
                    <div class="col-12">
                        <a href="#" @click.prevent="closeSale()" class="btn btn-dark btn-block" >Finalizar compra</a>
                    </div>
                </div>
            </div>

            <div class="col-md-8" >
                <w-grid 
                    searchKey="name"
                    sortKey="produto"
                    controllers='true' 
                    searchControllers='false'
                    :titles='["Produto", "Quantidade", "Valor unitário", "Total Valor"]'
                    :contents='newSale.basket'
                ></w-grid>
            </div>
        </div>
    </div>
</template>
<script>
import wGrid    from '../components/wGrid.vue'
export default {
    name: 'pSalesNew',
    data(){
        return {
            newSaleModalContents : {
                clients     : [],
                products    : []
            },
            newSale         : {
                sale            : {
                    client_id       : 0,
                    total_value     : 0  
                },
                basket          : [],
                basketProduct   : {
                    id          : 0,
                    amount      : 0,
                    value       : 0,
                    total_value : 0
                }
            },
            alerts      : {
                show    : false,
                text    : '',
                class   : ''
            }
        }
    },
    methods: {
        clearInputs: function(){
            this.newSale = {
                sale            : {
                    client_id       : 0,
                    total_value     : 0  
                },
                basket          : [],
                basketProduct   : {
                    id          : 0,
                    amount      : 0,
                    value       : 0,
                    total_value : 0
                }
            };
        },

        getClients: function(){
            fetch( apiUrl + 'clients/', {
                method  : 'GET',
                headers : {
                    'Content-Type'  : 'application/json',
                    'w-auth-token'  : wAuthToken
                }
            })
                .then(response      => (response.json()))
                .then(responseJSON  => {
                    if(responseJSON.status == true){
                        this.newSaleModalContents.clients = responseJSON.response;
                    }else{
                        console.log('Erro requisição', '=>', responseJSON.response);    
                    }
                })
                .catch(response     => {
                    console.log('Erro requisição', '=>', response.message);
                });
        },
    
        getProducts: function(){
            fetch( apiUrl + 'products/', {
                method  : 'GET',
                headers : {
                    'Content-Type'  : 'application/json',
                    'w-auth-token'  : wAuthToken
                }
            })
                .then(response      => (response.json()))
                .then(responseJSON  => {
                    if(responseJSON.status == true){
                        this.newSaleModalContents.products = responseJSON.response;
                    }else{
                        console.log('Erro requisição', '=>', responseJSON.response);    
                    }
                })
                .catch(response     => {
                    console.log('Erro requisição', '=>', response.message);
                });
        },

        addProductToBasket  : function(){
            if(
                this.newSale.basketProduct.total_value <= 0 ||
                this.newSale.basketProduct.amount      <= 0
            ){
                alert('Não é possivel adicionar um produto sem valor.');
            }else{
                this.newSale.basket.push({
                    product_id  : this.newSale.basketProduct.id, 
                    amount      : this.newSale.basketProduct.amount,
                    value       : this.newSale.basketProduct.value, 
                    total_value : this.newSale.basketProduct.value * this.newSale.basketProduct.amount
                });

                this.newSale.sale.total_value           = this.newSale.sale.total_value + this.newSale.basketProduct.total_value;
                this.newSale.basketProduct.amount       = 0;
                this.newSale.basketProduct.total_value  = this.newSale.basketProduct.amount * this.newSale.basketProduct.value;
            }
        },

        productTotalValue: function(){
            this.newSale.basketProduct.total_value = this.newSale.basketProduct.amount * this.newSale.basketProduct.value;
        },

        closeSale: function(){
            if(this.newSale.basket.length <= 0){
                alert('Não é possivel concuir a venda com a cesta vazia.');
            }else{
                fetch( apiUrl + 'sales/create', {
                    method  : 'POST',
                    headers : {
                        'Content-Type'  : 'application/json',
                        'w-auth-token'  : wAuthToken
                    },
                    body    : JSON.stringify(this.newSale)
                })
                    .then(response      => (response.json()))
                    .then(responseJSON  => {
                        if(responseJSON.status == true){
                            this.alerts.show    = true;
                            this.alerts.text    = 'Venda cadastrada com sucesso.';
                            this.alerts.class   = 'alert-success';

                            this.clearInputs();
                        }else{
                            this.alerts.show    = true;
                            this.alerts.text    = 'Erro ao cadastrar venda.';
                            this.alerts.class   = 'alert-warning';
                        }
                    })
                    .catch(response     => {
                        this.alerts.show    = true;
                        this.alerts.text    = 'Erro ao conectar ao servidor.';
                        this.alerts.class   = 'alert-danger';
                    });
            }
        }
    },
    mounted(){
        this.getClients();
        this.getProducts();
    }
}
</script>
<style>

</style>