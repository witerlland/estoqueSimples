<template>
    <section class="w-auth-form py-5">
        <form id="w-form" class="border border-dark" >
            <header>
                <h3>{{title}}</h3>
                <hr class="border-dark" >
            </header>
            <div>
                <div class="container" >
                    <div v-show="alerts.show" :class="alerts.class" class="alert alert-dismissible fade show" role="alert">
                        {{alerts.text}}
                    </div>
                </div>
                <slot></slot>
                <hr>
            </div>
            <footer class="row" >
                <div class="col-6 text-left ">
                    <a href="#" @click.prevent="firstAction()" v-on:keydown.enter="firstAction()" class="btn btn-block btn-outline-dark">{{buttons.secondAction}}</a>
                </div>
                <div class="col-6 text-right">
                    <a href="#" @click.prevent="clearForm()" class="btn btn-block btn-outline-danger">{{buttons.firstAction}}</a>
                </div>
            </footer>
        </form>
    </section>
</template>

<script>
    export default {
        name: 'wAuthForm',
        props: [
            'title',
            'alerts',
            'buttons'
        ],
        data(){
            return{

            }
        },
        methods: {
            clearForm   : function(){
                document.getElementById('w-form').reset();
                this.$emit('clearForm');
            },
            firstAction : function(){
                let erros   = [];
                let require = document.getElementsByClassName('w-require');

                for (let index = 0; index < require.length; index++) {
                    let item = require.item(index).value;

                    if(item == '' || item == undefined || item == null){
                        require[index].classList.add('w-required-empty');
                        erros.push(index);
                    }else{
                        require[index].classList.remove('w-required-empty');
                        erros.shift(index);
                    }
                }

                if(erros.length > 0){
                    let data = {
                        data: false,
                        msg : 'Existem campos vazios',
                        type: 'alert-warning'
                    };

                    this.$emit('firstAction', data);
                }else{
                    this.$emit('firstAction', {
                        data: true
                    });
                }
            }
        },
        mounted() {
        }
    }
</script>
<style>
</style>