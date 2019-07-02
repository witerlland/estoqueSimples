<template>
	<div>
		<div class="row" v-if="searchControllers == 'true'" >
			<div class="col-md-4">
				<div class="form-group">
					<label for="">Registros por página.</label>
					<select class="form-control" v-model="pageSize" >
						<option value="10">10</option>
						<option value="25">25</option>
						<option value="50">50</option>
					</select>
				</div>
			</div>
			<div class="col-md-8">
				<div class="form-group">
					<label for="">Buscar por registro</label>
					<input type="text" v-model="search" placeholder="Pesquisar registro" class="form-control" >
				</div>
			</div>
		</div>
		
		<div class="text-center container table-controllers" v-if="controllers == 'true'" >
			<a href="#" @click.prevent="prevPage" class="btn btn-sm btn-outline-dark"  >Anterior</a>
			<a href="#" @click.prevent="firstPage" class="btn btn-sm btn-outline-dark"  >1</a>
            <span class="btn btn-sm btn-dark"  >{{currentPage}}</span>
			<a href="#" @click.prevent="lastPage" class="btn btn-sm btn-outline-dark"  >{{totalOfPages}}</a>
			<a href="#" @click.prevent="nextPage" class="btn btn-sm btn-outline-dark"  >Proxima</a>
		</div>
		
		<div class="table-responsive" >
			<table class="table table-sm table-bordered table-striped table-hover">
				<thead>
					<tr>
						<td v-for="(title, index) in titles" @click="sort(title)" :key="index" >
							{{title}}
							<i v-if="currentSort === title && currentSortDir === 'asc'" class="fa fa-angle-double-down" aria-hidden="true"></i>
							<i v-if="currentSort === title && currentSortDir === 'desc'" class="fa fa-angle-double-up" aria-hidden="true"></i>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(content, index) in filterTable" v-on:click.stop="callModal(content)" :key="index" >
						<td v-for="(c, index) in content" :key="index" >
							<small>{{c}}</small>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
    </div>
</template>
<script>
    export default {
        name: 'wGrid',
        props: [
            'searchKey',
            'controllers',
            'searchControllers',
            'titles',
            'contents',
            'sortKey'
        ],
        data() {
            return {
                data		    : [],
                pageSize	    : 10,
                currentPage	    : 1,
                totalOfPages    : 1,
                currentSort	    : '',
                currentSortDir	: 'asc',
                search          : '',
                // searchKey       : ''
            }
        },
        methods: {
            sort: function(s){
                if(s === this.currentSort){
                    this.currentSortDir = this.currentSortDir==='asc'?'desc':'asc';
                }
                
                this.currentSort = s;
            },
            nextPage: function(){
                if(this.currentPage+1 <= this.totalOfPages){
                    this.currentPage ++;
                }
            },
            prevPage: function(){
                if(this.currentPage - 1 >= 1){
                    this.currentPage --;
                }
            },
            firstPage: function(){
                this.currentPage = 1;
            },
            lastPage: function(){
                this.currentPage = this.totalOfPages;
            },
            callModal: function(content){
                this.$emit('showModal', content);
            }
        },
        mounted(){
            // this.data = this.contents;
        },
        watch: {
            contents: function(newVal, oldVal){
                this.data = this.contents;
                this.currentSort = this.sortKey;
                // this.searchKey  = this.searchKey;
            }
        },
        computed: {
            // Funcao para adicionar a paginacao e organizar os elementos seguindo as regras de nomeclatura Asc e DEc
            filterTable(){
                let filter 				= this.search;
                let tableFiltered       =  _.orderBy(this.data, this.currentSort, this.currentSortDir);
                let index 				= Math.round((this.currentPage-1)*this.pageSize);
                
                this.totalOfPages       = Math.round((tableFiltered.length / this.pageSize) + 0.4 );
                tableFiltered 		    = tableFiltered.slice(index, index + this.pageSize);

                if(_.isEmpty(filter)){
                    return tableFiltered;
                }else{
                    return _.filter(tableFiltered, repo => repo[this.searchKey].indexOf(filter) >= 0);
                }
            }
        },
    }
</script>
<style scoped>
    .table-controllers{
        padding-bottom: 1rem;
        padding-top: 1rem;
    }

    .form-group{
        margin-bottom: 0;
    }
</style>