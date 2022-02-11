<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- CARD de busca -->
            <card-component card_header="Buscar Marcas">
                <template v-slot:conteudo>
                    <div class="row">                    
                        <div class="col-sm-4 mb-3">
                            <input-container-component id="inputId" titulo="ID" nomeHelp="idHelp" textoHelp="Opcional. Informe o ID do registro.">
                                <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID">
                            </input-container-component>                            
                        </div>
                        <div class="col-sm-8 mb-3">
                            <input-container-component id="inputNome" titulo="NOME" nomeHelp="nomeHelp" textoHelp="Opcional. Informe o nome da marca.">
                                <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="NOME DA MARCA">
                            </input-container-component>
                        </div>
                    </div>
                </template>
                <template v-slot:rodape>
                    <button type="submit" class="btn btn-primary btn-sm">pesquisa</button>
                </template>
            </card-component>
            <!-- CARD de listage -->
            <card-component card_header="Relação de Marcas">
                <template v-slot:conteudo>
                    <table-component :dados="marcas" :titulos="['id', 'nome', 'imagem']"></table-component>
                </template>
                <template v-slot:rodape>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                </template>
            </card-component>
      
            <!-- Modal -->
            <modal-component id_modal="modalMarca" modal_title="Cadastrar Marca">   
                <template v-slot:alertas>                
                    <alert-component tipo="success" :detalhes="transacao_detalhes" titulo="Sucesso ao cadastrar a marca"     v-if="transacao_status == 'sucesso'"></alert-component>
                    <alert-component tipo="danger"  :detalhes="transacao_detalhes" titulo="Erro ao tentar cadastrar a marca" v-if="transacao_status == 'erro'"></alert-component>
                </template>

                <template v-slot:conteudo>
                    <div class="mb-3">
                        <input-container-component id="inputNomeMarca" titulo="Nome" nomeHelp="nomeMarcaHelp" textoHelp="Obrigatório. Informe o nome da marca.">
                            <input type="text" class="form-control" id="inputNomeMarca" aria-describedby="nomeMarcaHelp" placeholder="NOME DA MARCA" v-model="nomeMarca">
                        </input-container-component>                         
                    </div>
                    <div class="mb-3">
                        <input-container-component id="inputImagem" titulo="Imagem" nomeHelp="imagemHelp" textoHelp="Obrigatório. Insira o logo da marca em formato PNG.">
                            <input type="file" class="form-control" id="inputImagem" aria-describedby="imagemHelp" placeholder="Logo" @change="carregarImagem($event)">
                        </input-container-component>                            
                    </div>
                </template>
                
                <template v-slot:rodape>                    
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
                </template>
            </modal-component>
            <!-- fim modal -->
        </div>
    </div>
</div>
</template>

<script>
    export default {
        computed:{
            token(){
                // pegando o token do navegador

                //retorna: token=errmkfmvkddddddvmkdfvl
                let token = document.cookie.split(';').find(indice => {
                    return indice.includes('token=')
                })
                //retorna: [0]=>token, [1]=>errmkfmvkddddddvmkdfvl
                token = token.split('=')[1]

                token = 'Bearer ' + token
                return token
            }
        },
        data(){
            return{
                url_base: 'http://127.0.0.1:8000/api/v1/marca',
                nomeMarca: '',
                arquivoImagem: [],
                transacao_status: '',
                transacao_detalhes: [],
                marcas: [],
            }
        },
        methods:{
            carregarLista(){
                let config = {
                    headers:{
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.get(this.url_base, config)
                    .then(response =>{
                        this.marcas = response.data
                        // console.log(this.marcas)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            carregarImagem(e){
                this.arquivoImagem = e.target.files
            },
            salvar(){
                let formData = new FormData();
                formData.append('nome',this.nomeMarca)
                formData.append('imagem',this.arquivoImagem[0])

                let config = {
                    headers:{
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(this.url_base, formData, config)
                    .then(response => {
                        this.transacao_status   = 'sucesso'
                        this.transacao_detalhes = {
                            mensagem: 'ID do registro: ' + response.data[0].id
                        }
                    })
                    .catch(errors => {
                        this.transacao_status   = 'erro'
                        this.transacao_detalhes = {
                            mensagem: errors.response.data.message,
                            dados: errors.response.data.errors
                        }
                    })
            }
        },
        mounted(){
            this.carregarLista()
        }
        
    }
</script>
