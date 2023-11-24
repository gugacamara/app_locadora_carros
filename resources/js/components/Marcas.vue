<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <!-- inicio do card de busca -->
            <card-component titulo="Busca de Marcas">
                <template v-slot:conteudo>
                    <div class="form-row">
                        <div class="col form-group">
                            <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional, Informe o ID da Marca">
                                <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID da Marca" v-model="busca.id">
                            </input-container-component>
                        </div>
                        <div class="col form-group">
                            <input-container-component titulo="Nome da marca" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional, Informe o nome da Marca">
                                <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da Marca" v-model="busca.nome">
                            </input-container-component>
                        </div>
                    </div>
                </template>
                <template v-slot:rodape>
                    <button type="submit" class="btn btn-primary float-right" @click="pesquisar()">Buscar</button>
                </template>
            </card-component>
            <!-- fim do card de busca -->

        <!-- inicio do card de listagem -->
            <card-component titulo="Relação de Marcas">
                <template v-slot:conteudo>
                    <table-component
                        :dados="marcas.data"
                        :visualizar="{ visivel: true, dataToggle:'modal', dataTarget:'#modalMarcaVisualizar' }"
                        :atualizar="{ visivel: true, dataToggle:'modal', dataTarget:'#modalMarcaAtualizar' }"
                        :remover="{ visivel: true, dataToggle:'modal', dataTarget:'#modalMarcaRemover' }"
                        :titulos="{
                            id: {titulo: 'ID', tipo: 'texto'},
                            nome: {titulo: 'Nome', tipo: 'texto'},
                            imagem: {titulo: 'Imagem', tipo: 'imagem'},
                            created_at: {titulo: 'Criação', tipo: 'data'},
                        }">
                    </table-component>
                     <!-- :titulos="['id', 'nome', 'imagem']" -->
                </template>
                <template v-slot:rodape>
                    <div class="row">
                        <div class="col-10">
                            <paginate-component>
                                <li v-for="l, key in marcas.links" :key="key" :class=" l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                    <a class="page-link" v-html="l.label"></a>
                                </li>
                            </paginate-component>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                        </div>
                    </div>
                </template>
            </card-component>
            <!-- fim do card de listagem -->
        </div>
    </div>

    <!-- Inicio Modal de inclusão de marca-->
    <modal-component id="modalMarca" titulo="Adicionar Marca">
        <template v-slot:alerta>
            <alert-component tipo="success" :detalhes="transacaoDetalhes" v-if="transacaoStatus == 'adicionado'" titulo="Marca adicionada com sucesso"></alert-component>
            <alert-component tipo="danger" :detalhes="transacaoDetalhes" v-if="transacaoStatus == 'erro'" titulo="Erro ao tentar cadastrar a marca"></alert-component>
        </template>

        <template v-slot:conteudo>
            <div class="form-group">
                <input-container-component titulo="Nome da marca" id="inputNovoNome" id-help="novoNomeHelp" texto-ajuda="Informe o nome da Marca">
                    <input type="text" class="form-control" id="inputNovoNome" aria-describedby="novoNomeHelp" placeholder="Nome da Marca" v-model="nomeMarca">
                </input-container-component>
            </div>
            <div class="form-group">
                <input-container-component titulo="Imagem" id="inputImagem" id-help="imagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                    <input type="file" class="form-control-file" id="inputImagem" aria-describedby="imagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                </input-container-component>
            </div>
        </template>

        <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
    </modal-component>
    <!-- Fim Modal de inclusão de marca -->

    <!-- Inicio do modal de visualização de marca-->
    <modal-component id="modalMarcaVisualizar" titulo="Marca">
        <template v-slot:alerta></template>
        <template v-slot:conteudo>
            <input-container-component titulo="ID">
                <input type="text" class="form-control" v-model="$store.state.item.id" disabled>
            </input-container-component>

            <input-container-component titulo="Nome da marca">
                <input type="text" class="form-control" v-model="$store.state.item.nome" disabled>
            </input-container-component>

            <input-container-component titulo="Imagem">
                <img :src=" '/storage/' + $store.state.item.imagem" v-if="$store.state.item.imagem" width="40px" height="40px">
            </input-container-component>

            <input-container-component titulo="Data de criação">
                <input type="text" class="form-control" v-model="$store.state.item.created_at" disabled>
            </input-container-component>
        </template>
        <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </template>
    </modal-component>
    <!-- Fim do modal de visualização de marca-->

    <!-- Inicio do modal de remoção de marca-->
    <modal-component id="modalMarcaRemover" titulo="Remover marca">
        <template v-slot:alerta>
            <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Marca removida" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
            <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar remover a marca" v-if="$store.state.transacao.status == 'erro'"></alert-component>
        </template>
        <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
            <input-container-component titulo="ID">
                <input type="text" class="form-control" v-model="$store.state.item.id" disabled>
            </input-container-component>

            <input-container-component titulo="Nome da marca">
                <input type="text" class="form-control" v-model="$store.state.item.nome" disabled>
            </input-container-component>
        </template>
        <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Excluir marca</button>
        </template>
    </modal-component>
    <!-- Fim do modal de remoção de marca-->

     <!-- Inicio do modal de atualização de marca-->
     <modal-component id="modalMarcaAtualizar" titulo="Atualizar Marca">
        <template v-slot:alerta>

        </template>

        <template v-slot:conteudo>
            <div class="form-group">
                <input-container-component titulo="Nome da marca" id="inputNovoNome" id-help="novoNomeHelp" texto-ajuda="Informe o nome da Marca">
                    <input type="text" class="form-control" id="inputNovoNome" aria-describedby="novoNomeHelp" placeholder="Nome da Marca" v-model="nomeMarca">
                </input-container-component>
            </div>
            <div class="form-group">
                <input-container-component titulo="Imagem" id="inputImagem" id-help="imagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                    <input type="file" class="form-control-file" id="inputImagem" aria-describedby="imagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                </input-container-component>
            </div>
        </template>

        <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
        </template>
    </modal-component>
    <!-- Fim do modal de atualização de marca-->

</div>
</template>

<script>
import axios from 'axios';
import InputContainer from './InputContainer.vue';
import Alert from './Alert.vue';

    export default{
  components: { InputContainer, Alert },
        data(){
            return {
                urlBase: 'http://localhost:8000/api/v1/marca',
                urlPaginacao: '',
                urlFiltro: '',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                marcas: { data: [] }, // o data: [] é para evitar o erro de não encontrar o data na primeira vez que o componente é montado
                busca: {
                    id: '',
                    nome: '',
                }
            }
        },
        computed:{
                token(){
                    let token = document.cookie.split(';').find(indice =>{
                        return indice.startsWith('token=')
                })
                token = token.split('=')[1]
                token = 'Bearer ' + token
                return token
            }
        },
        methods: {
            remover(){
                let confirmacao = confirm('Deseja realmente excluir a marca ' + this.$store.state.item.nome + '?')
                if(!confirmacao){
                    return false
                }

                let formData = new FormData();
                formData.append('_method', 'DELETE')

                let url = this.urlBase + '/' + this.$store.state.item.id

                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                axios.delete(url, formData, config)
                    .then(response => {
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg
                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.erro
                        this.carregarLista()
                    })
            },
            pesquisar(){
                let filtro = ''
                for(let chave in this.busca){
                    if(this.busca[chave]){
                        if(filtro != ''){
                            filtro += ';' // separa a partir do segundo filtro por ;
                        }
                        if(this.busca[chave] != ''){
                            filtro += chave + ':like:' + this.busca[chave]
                        }
                    }
                }
                if(filtro != ''){
                    this.urlPaginacao = '' // limpando a paginação, para pesquisar sempre do início
                    this.urlFiltro = '&filtro=' + filtro
                }else{
                    this.urlFiltro = '' // limpando o filtro
                }
                this.carregarLista()
            },
            paginacao(pagina){
                if(pagina.url){
                    //this.urlBase = pagina.url // atualiza a urlBase com a url da página clicada
                    this.urlPaginacao = pagina.url.split('?')[1] // Obtem a page= da url
                    this.carregarLista() // carrega a lista com a nova url
                }

            },
            carregarImagem(event){
                this.arquivoImagem = event.target.files;
            },
            carregarLista(){
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.get(url, config)
                    .then(response => {
                        this.marcas = response.data
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            salvar(){
                let formData = new FormData();
                    formData.append('nome', this.nomeMarca)
                    formData.append('imagem', this.arquivoImagem[0])

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data', // o Content-Type é para fazer o Laravel entender que o envio é de um formulário
                        'Accept': 'application/json', // o Accept é para o Laravel saber que o retorno é um JSON
                        'Authorization': this.token // o Authorization é para o Laravel saber que o retorno é um JSON
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado'
                        this.transacaoDetalhes = {
                            mensagem: 'ID do registro: ' + response.data.id,
                        }
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'erro'
                        this.transacaoDetalhes = {
                            mensagem: errors.response.data.message,
                            dados: errors.response.data.errors,
                        }
                    })
            }
        },
        mounted(){ // O mounted é executado quando o componente é montado
            this.carregarLista()
        }

    }
</script>

