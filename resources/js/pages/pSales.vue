<template>
    <div class="container-fluid" >
        <div class="row" >
            <div class="col-md-2" >
                <div class="container" style="padding-bottom: 1.5rem;" >
                    <ul class="list-group">
                        <a href="#" @click.prevent="newClientModal = true" class="list-group-item list-group-item-action active">
                            Nova venda.
                        </a>
                    </ul>
                </div>
            </div>
            <div class="col-md-10">
                <div class="container">
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Vendas
                        </li>
                    </ol>
                </div>
                <div class="container" >
                    <w-grid 
                        searchKey="name"
                        sortKey="id"
                        controllers='true' 
                        searchControllers='true'
                        :titles='titles'
                        :contents='contents'
                        v-on:showModal="showModal"
                    ></w-grid>
                </div>
            </div>
        </div>

        <w-modal
            title="Novo produto"
            :showModal='newClientModal'
            :showSaveBtn='true'
            v-on:saveAction='saveNew'
            v-on:closeModal="closeModalNew"
        >
            <div class="container" >
                <div>
                    <div v-show="alerts.show" :class="alerts.class" class="alert alert-dismissible fade show" role="alert">
                        {{alerts.text}}
                    </div>
                </div>
                <div class="row" >
                    <div class="col-12" >
                        <div class="form-group">
                            <label for="userName">Nome</label>
                            <input v-model="newClient.name" type="text" class="form-control" >
                        </div>
                    </div>
                    <div class="col-12" >
                        <div class="form-group">
                            <label for="userName">Email</label>
                            <input v-model="newClient.email" type="text" class="form-control" >
                        </div>
                    </div>
                    <div class="col-12" >
                        <div class="form-group">
                            <label for="userName">Telefone</label>
                            <input v-model="newClient.phone" type="text" class="form-control" >
                        </div>
                    </div>
                </div>
            </div>
        </w-modal>

        <w-modal
            :title="modalTitle"
            :showModal='modal'
            :showSaveBtn='saveBtn'
            v-on:saveAction='saveChanges'
            v-on:closeModal="closeModal"
        >
            <div class="container" >
                <div>
                    <div v-show="alerts.show" :class="alerts.class" class="alert alert-dismissible fade show" role="alert">
                        {{alerts.text}}
                    </div>
                </div>
                <div class="row" >
                    <header class="col-md-12 text-center">
                        <h3>Cliente</h3>
                    </header>
                    <div class="col-md-6" >
                        <div class="form-group">
                            <label for="userName">Cliente</label>
                            <input v-model="modalContent.sale[0].name" disabled type="text" class="form-control enable-with-change" >
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div class="form-group">
                            <label for="userName">Valor da compra</label>
                            <input v-model="modalContent.sale[0].total_value" disabled type="text" class="form-control enable-with-change" >
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div class="form-group">
                            <label for="userName">Email</label>
                            <input v-model="modalContent.sale[0].email" disabled type="text" class="form-control enable-with-change" >
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div class="form-group">
                            <label for="userName">Telefone</label>
                            <input v-model="modalContent.sale[0].phone" disabled type="text" class="form-control enable-with-change" >
                        </div>
                    </div>
                    <div class="col-md-12" >
                        <div class="form-group">
                            <label for="userName">Cadastrado em</label>
                            <input v-model="modalContent.sale[0].created_at" disabled type="text" class="form-control" >
                        </div>
                    </div>
                </div>

                <hr>

                <div class="container" >
                    <h3 class="text-center" >Produtos</h3>
                    <w-grid 
                        searchKey="name"
                        sortKey="id"
                        controllers='false' 
                        searchControllers='false'
                        :titles='["Id","Produto", "Descrição", "Marca", "Valor", "Quantidade", "Total Valor"]'
                        :contents='modalContent.basket'
                    ></w-grid>
                </div>
            </div>
        </w-modal>
    </div>
</template>
<script>
    import wGrid    from '../components/wGrid.vue'
    import wModal   from '../components/wModal.vue'
    export default {
        name: 'pSales',
        components: {
            wGrid
        },
        data(){
            return {
                titles      : ['Id', 'Valor', 'Cliente', 'Email', 'Telefone', 'Data'],
                contents    : [],
                modal       : false,
                modalTitle  : '',
                modalContent: {
                    sale    : [{}],
                    basket  : []
                },
                saveBtn     : false,
                alerts      : {
                    show    : false,
                    text    : '',
                    class   : ''
                },
                newClientModal     : false,
                newClient          : {
                    name        : '',
                    phone       : '',
                    email       : '',
                }
            }
        },
        methods: {
            saveNew     : function(){
                var data = {
                    "name"          : this.newClient.name,
                    "email"         : this.newClient.email,
                    "phone"         : this.newClient.phone
                }

                fetch( apiUrl + 'clients/createOrUpdate', {
                    method  : 'POST',
                    headers : {
                        'Content-Type'  : 'application/json',
                        'w-auth-token'  : wAuthToken
                    },
                    body    : JSON.stringify(data)
                })
                    .then(response      => (response.json()))
                    .then(responseJSON  => {
                        if(responseJSON.status == true){
                            this.alerts.show    = true;
                            this.alerts.text    = 'Cliente cadastrado com sucesso.';
                            this.alerts.class   = 'alert-success';

                            this.getSales();
                        }else{
                            this.alerts.show    = true;
                            this.alerts.text    = 'Erro ao cadastrar cliente.';
                            this.alerts.class   = 'alert-warning';
                        }
                    })
                    .catch(response     => {
                        this.alerts.show    = true;
                        this.alerts.text    = 'Erro ao conectar ao servidor.';
                        this.alerts.class   = 'alert-danger';
                    });
            },

            closeModalNew   : function(){
                this.newClient.name     = '';
                this.newClient.email    = '';
                this.newClient.phone    = '';

                this.alerts.show    = false;
                this.newClientModal = false;
            },

            showModal   : function(data){
                fetch(apiUrl + 'sales/' + data.id, {
                    method: 'GET',
                    headers: {
                        'Content-Type'  : 'application/json',
                        'w-auth-token'  : wAuthToken
                    }
                })
                    .then(response => (response.json()))
                    .then(responseJSON => {
                        if(responseJSON.status == true){
                            this.modalContent   = responseJSON.response;
                            this.modal          = true;
                            this.modalTitle     = 'Venda para: ' + this.modalContent.sale[0].name;
                        }else{
                            console.log("status", "=>", responseJSON.response);
                        }
                    })
                    .catch(error => (console.log('Error', '=>', error)));
            },

            closeModal: function(){
                var itens = document.getElementsByClassName('enable-with-change');
                for (let index = 0; index < itens.length; index++) {
                    // const element = array[index];
                    // let item = require.item(index).value;
                    itens[index].disabled = true;
                }

                this.modal          = false;
                this.alerts.show    = false;
                this.modalTitle     = '';

                this.getSales();
            },

            getSales : function(){
                fetch( apiUrl + 'sales/', {
                    method  : 'GET',
                    headers : {
                        'Content-Type'  : 'application/json',
                        'w-auth-token'  : wAuthToken
                    }
                })
                    .then(response      => (response.json()))
                    .then(responseJSON  => {
                        if(responseJSON.status == true){
                            this.contents = responseJSON.response;
                        }else{
                            console.log('Erro requisição', '=>', responseJSON.response);    
                        }
                    })
                    .catch(response     => {
                        console.log('Erro requisição', '=>', response.message);
                    });
            },

            enableChanges: function(){
                var itens = document.getElementsByClassName('enable-with-change');
                for (let index = 0; index < itens.length; index++) {
                    // const element = array[index];
                    // let item = require.item(index).value;
                    itens[index].disabled = false;
                }
                this.saveBtn = true;
            },

            saveChanges: function(){
                var data = {
                    "id"            : this.modalContent.id,
                    "name"          : this.modalContent.name,
                    "email"         : this.modalContent.email,
                    "phone"         : this.modalContent.phone
                }

                fetch( apiUrl + 'clients/createOrUpdate', {
                    method  : 'POST',
                    headers : {
                        'Content-Type'  : 'application/json',
                        'w-auth-token'  : wAuthToken
                    },
                    body    : JSON.stringify(data)
                })
                    .then(response      => (response.json()))
                    .then(responseJSON  => {
                        if(responseJSON.status == true){
                            this.alerts.show    = true;
                            this.alerts.text    = 'Dados atualizados com sucesso.';
                            this.alerts.class   = 'alert-success';
                        }else{
                            this.alerts.show    = true;
                            this.alerts.text    = 'Erro ao atualizar dados.';
                            this.alerts.class   = 'alert-warning';
                        }
                    })
                    .catch(response     => {
                        this.alerts.show    = true;
                        this.alerts.text    = 'Erro ao conectar ao servidor.';
                        this.alerts.class   = 'alert-danger';
                    });
            }
        },
        mounted(){
            this.getSales();
        }
    }
</script>