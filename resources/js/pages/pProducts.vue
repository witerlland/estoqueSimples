<template>
    <div class="container-fluid" >
        <h2>Produtos mesmo</h2>

        <w-grid 
            searchKey="name"
            sortKey="id"
            controllers='true' 
            searchControllers='true'
            :titles='titles'
            :contents='contents'
            v-on:showModal="showModal"
        ></w-grid>

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
                    <div class="col-12" >
                        <a href="#" @click.prevent="enableChanges()" class="btn btn-primary btn-block" >Alterar</a>
                    </div>
                    <div class="col-12" >
                        <div class="form-group">
                            <label for="userName">Nome</label>
                            <input v-model="modalContent.name" disabled type="text" class="form-control enable-with-change" >
                        </div>
                    </div>
                    <div class="col-12" >
                        <div class="form-group">
                            <label for="userName">Descrição</label>
                            <input v-model="modalContent.description" disabled type="text" class="form-control enable-with-change" >
                        </div>
                    </div>
                    <div class="col-12" >
                        <div class="form-group">
                            <label for="userName">Descrição</label>
                            <input v-model="modalContent.value" disabled type="number" class="form-control enable-with-change" >
                        </div>
                    </div>
                    <div class="col-12" >
                        <div class="form-group">
                            <label for="userName">Marca</label>
                            <input v-model="modalContent.brand" disabled type="text" class="form-control enable-with-change" >
                        </div>
                    </div>
                    <div class="col-12" >
                        <div class="form-group">
                            <label for="userName">Cadastrado em</label>
                            <input v-model="modalContent.created_at" disabled type="text" class="form-control" >
                        </div>
                    </div>
                    <div class="col-12" >
                        <div class="form-group">
                            <label for="userName">Ultima atualização</label>
                            <input v-model="modalContent.updated_at" disabled type="text" class="form-control" >
                        </div>
                    </div>
                </div>
            </div>
        </w-modal>
    </div>
</template>
<script>
    import wGrid    from '../components/wGrid.vue'
    import wModal   from '../components/wModal.vue'
    export default {
        name: 'pProducts',
        components: {
            wGrid
        },
        data(){
            return {
                titles      : ['id', 'Nome', 'Descrição', 'Valor', 'Marca'],
                contents    : [],
                modal       : false,
                modalTitle  : '',
                modalContent: {},
                saveBtn     : false,
                alerts      : {
                    show    : false,
                    text    : '',
                    class   : ''
                }
            }
        },
        methods: {
            showModal   : function(data){
                fetch(apiUrl + 'products/' + data.id, {
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
                            this.modalTitle     = this.modalContent.name;
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
                this.modalTitle     = '';

                this.getProducts();
            },

            getProducts : function(){
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
                    "description"   : this.modalContent.description,
                    "value"         : this.modalContent.value,
                    "brand"         : this.modalContent.brand
                }

                fetch( apiUrl + 'products/createOrUpdate', {
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
            this.getProducts();
        }
    }
</script>