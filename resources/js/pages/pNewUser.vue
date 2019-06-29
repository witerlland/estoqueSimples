<template>
    <section class="container-fluid" >
        <w-auth-form
            title="Cadastro de usuário"
            :alerts="alerts"
            :buttons="{firstAction: 'Cancelar', secondAction: 'Salvar'}"
            v-on:firstAction="firstAction"
        >
            <div class="form-group">
                <label for="exampleInputEmail1">Usuário</label>
                <input v-model="user.name" type="text" class="form-control w-require" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Usuário de acesso">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input type="email" class="form-control w-require" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email de acesso">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Senha</label>
                <input type="password" class="form-control w-require" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Senha com 8 digitos ou mais">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Repetir Senha</label>
                <input type="password" class="form-control w-require" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Repita a senha">
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
                name    : ''
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
                console.log(this.user.name);
            }
        }
    },
    mounted(){
        
    }
}
</script>
<style scoped>

</style>