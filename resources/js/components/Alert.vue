<template>
    <div v-bind:class="estilo" role="alert">
    {{titulo}}
    <hr>         <!-- O componente Alert acessa a mensagem de errors.response.message.data.message do axios-->
    <p>{{ detalhes.mensagem }}</p>
    <ul v-if="detalhes.dados">
        <li v-for="(item, indice) in detalhes.dados" :key="indice">
            {{item[0]}}
        </li>
    </ul>
    </div>
</template>

<script>
    export default {
        props: ['tipo', 'detalhes', 'titulo'],
        computed: {
            estilo(){
                return 'alert alert-' + this.tipo;
            }
        }
    }
</script>
