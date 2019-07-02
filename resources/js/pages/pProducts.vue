<template>
    <div class="container-fluid" >
        <h2>Produtos mesmo</h2>

        <w-grid 
            searchKey="name"
            sortKey="name"
            controllers='true' 
            searchControllers='true'
            :titles='titles'
            :contents='contents'
            v-on:showModal="showModal"
        ></w-grid>
        
    </div>
</template>
<script>
    import wGrid from '../components/wGrid.vue'
    export default {
        name: 'pProducts',
        components: {
            wGrid
        },
        data(){
            return {
                titles      : ['Nome', 'Descrição', 'Valor', 'Marca'],
                contents    : [],
                modal       : false,
                modalTitle  : '',
                modalContent: {}
            }
        },
        methods: {
            showModal   : function(){
                alert('Modal');
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
                            console.log('Retorno requisição', '=>', responseJSON);
                            if(responseJSON.status == true){
                                this.contents = responseJSON.response;
                            }else{
                                console.log('Erro requisição', '=>', responseJSON.response);    
                            }
                        })
                        .catch(response     => {
                            console.log('Erro requisição', '=>', response.message);
                        });
            }
        },
        mounted(){
            this.getProducts();
        }
    }
</script>