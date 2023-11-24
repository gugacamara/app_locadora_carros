<template>
    <div class="form-group">
        <label v-bind:for="id" class="form-label">{{ titulo }}</label>
        <!-- nao foi usado a tag input dentro do component, pois precisaremos da tag dentro de cada contexto-->
        <slot></slot> <!-- slot pega o conteudo interno das tags-->
        <small  v-bind:id="idHelp" class="form-text text-muted">{{ textoAjuda }}</small>
    </div>
</template>

<script>
    export default {
        props: ['id', 'titulo', 'idHelp', 'textoAjuda'],
    }
</script>
