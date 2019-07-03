<template>
    <div class="w-modal" v-show="showModal == true" >
        <div class="w-modal-content" >
            <header>
                <h3>{{title}}</h3>
                <hr>
            </header>
            <section class=w-modal-body >
                <slot></slot>
            </section>
            <footer class="w-modal-footer" >
                <hr>
                <div class="row" >
                    <div class="col-md-6" >
                        <a href="#" @click.prevent="closeModal" class="btn btn-outline-danger btn-block">Sair</a>
                    </div>
                    <div class="col-md-6" >
                        <a href="#" v-if="showSaveBtn" @click.prevent="saveAction" class="btn btn-outline-success btn-block">Salvar</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>
<script>
export default {
    name: 'wModal',
    props: [
        'title', 
        'showModal',
        'showSaveBtn'
    ],
    data() {
        return {
        }
    },
    mounted(){},
    watch: {},
    methods: {
        closeModal: function(){
            this.$emit('closeModal');
        },

        saveAction: function(){
            this.$emit('saveAction');
        }
    }
}
</script>
<style scoped >
    .w-modal{
        background-color: rgba(0, 0, 0, 0.2);
        padding-top: 3rem;
        padding-bottom: 3rem;
        position: fixed;
        z-index: 999;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .w-modal-content{
        width: 60vw;
        height: 100%;
        background-color: #FFF;
        display: flex;
        margin: auto;
        flex-direction: column;
        border-radius: 2px;
        padding: 1rem 1.5rem;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.5);
        overflow-x: scroll;
    }

    .w-modal-body{}

    .w-modal-footer{}
</style>