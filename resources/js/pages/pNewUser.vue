<template>
    <section class="container-fluid py-5" >
        <w-auth-form
            title="Cadastro de usuário"
            :alerts="alerts"
            :buttons="{firstAction: 'Cancelar', secondAction: 'Salvar'}"
            v-on:firstAction="firstAction"
            v-on:clearForm="clearForm"
        >
            <div class="form-group">
                <label for="userName">Usuário</label>
                <input v-model="user.user" type="text" class="form-control w-require" id="userName" aria-describedby="emailHelp" placeholder="Usuário de acesso">
            </div>
            <div class="form-group">
                <label for="userMail">Email</label>
                <input v-model="user.mail" type="email" class="form-control w-require" id="userMail" aria-describedby="emailHelp" placeholder="Email de acesso">
            </div>
            <div class="form-group">
                <label for="userPass">Senha</label>
                <input v-model="user.pass" type="password" class="form-control w-require" id="userPass" aria-describedby="emailHelp" placeholder="Senha com 8 digitos ou mais">
            </div>
            <div class="form-group">
                <label for="userPass2">Repetir Senha</label>
                <input v-model="user.pass2" type="password" class="form-control w-require" id="userPass2" aria-describedby="emailHelp" placeholder="Repita a senha">
            </div>
        </w-auth-form>
    </section>
</template>
<script>
import wAuthForm from '../components/wAuthForm'
export default {
    name: 'pNewUser',
    components: {
        wAuthForm
    },
    data(){
        return {
            alerts  : {
                show    : false,
                text    : 'Usuário cadastrado com sucesso',
                class   : 'alert-success'
            },
            user    : {
                user    : '',
                mail    : '',
                pass    : '',
                pass2   : ''
            }
        }
    },
    methods: {
        // Funcao para cadastrar um novo usuario
        firstAction : function(data){
            console.log('retorno do $emit', '=>', data);
            if(data.data == false){
                this.alerts = {
                    show    : true,
                    text    : data.msg,
                    class   : data.type
                }
            }else{
                if(this.user.pass != this.user.pass2){
                    this.alerts = {
                        show    : true,
                        text    : 'As senhas diferem entre si.',
                        class   : 'alert-warning'
                    }
                }else{
                    fetch( apiUrl + 'user/new', {
                        method  : 'POST',
                        headers : {
                            'data'          : this.user,
                            'Content-Type'  : 'application/json'
                        },
                        body    : JSON.stringify({
                            'data' : this.user
                        })
                    })
                        .then(response => (response.json()))
                        .then(responseJSON => {
                            console.log(responseJSON);
                            if(responseJSON.status == true){
                                this.alerts = {
                                    show    : true,
                                    text    : 'Usuário cadastrado com sucesso.',
                                    class   : 'alert-success'
                                }
                            }else{
                                this.alerts = {
                                    show    : true,
                                    text    : 'Erro ao cadastrar novo usuáro.',
                                    class   : 'alert-warning'
                                }
                            }
                        })
                        .catch(error => {
                            console.log("Error", "=>", error);
                            this.alerts = {
                                show    : true,
                                text    : 'Erro ao tentar se conectar ao servidor.',
                                class   : 'alert-danger'
                            }
                        });
                }
            }
        },
        // Funcao para limpar o formulário e as models
        clearForm   : function(){
            this.user = {
                user    : '',
                mail    : '',
                pass    : '',
                pass2   : ''
            }
        }
    },
    mounted(){
        
    }
}
</script>
<style scoped>

</style>