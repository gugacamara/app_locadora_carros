<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t, chave in titulos" :key="chave" class="text-uppercase">{{ t.titulo }}</th>
                    <th v-if=" visualizar.visivel || atualizar || remover.visivel "></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, objChave in dadosFiltrados" :key="objChave">
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'texto' ">
                            {{ valor }}
                        </span>
                        <span v-if="titulos[chaveValor].tipo == 'data' ">
                            {{ valor }}
                        </span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem' ">
                            <img :src=" '/storage/' + valor" alt="Imagem da Marca" width="30px" height="30px">
                        </span>
                    </td>
                    <td v-if=" visualizar.visivel || atualizar || remover.visivel ">
                        <button v-if='visualizar.visivel' :data-toggle="visualizar.dataToggle" :data-target="visualizar.dataTarget" @click="setStore(obj)" class="btn btn-outline-primary btn-sm">Visualizar</button>
                        <button v-if='atualizar' class="btn btn-outline-primary btn-sm">Atualizar</button>
                        <button v-if='remover.visivel' :data-toggle="remover.dataToggle" :data-target="remover.dataTarget" @click="setStore(obj)" class="btn btn-outline-danger btn-sm">Remover</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['dados', 'titulos', 'visualizar', 'atualizar', 'remover'],
        methods:{
            setStore(objeto){
                this.$store.state.transacao.status = '' // limpa a mensagem de erro ou sucesso no store a cada clique
                this.$store.state.transacao.mensagem = ''
                this.$store.state.item = objeto
            }
        },
        computed:{
            dadosFiltrados(){
                let campos = Object.keys(this.titulos)
                let dadosFiltrados = []
                this.dados.map((item, chave) => { // percorre o array de objetos
                    let itemFiltrado = {}
                    campos.forEach(campo => { // percorre o array de campos(titulos) para cada objeto do map
                        itemFiltrado[campo] = item[campo] // cria um novo objeto com os campos do array de campos
                    })
                    dadosFiltrados.push(itemFiltrado) // adiciona o objeto criado no array de objetos
                })
                return dadosFiltrados
            }
        }
    }
</script>
