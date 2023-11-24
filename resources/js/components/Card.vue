<template>
    <div class="card mb-3">
        <div class="card-header">{{ titulo }}</div>
        <div class="card-body">
            <slot name="conteudo"></slot><!--  mais de um conteudo no slot deve-se informar o nome do slot -->
        </div>
        <div class="card-footer">
            <slot name="rodape"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['titulo']
    }
</script>
