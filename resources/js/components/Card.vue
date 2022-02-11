<template>
    <div class="card mb-3">
        <div class="card-header">{{ card_header }}</div>

        <div class="card-body">
            <slot name="conteudo"></slot>
        </div>
        <div class="card-footer text-end">
            <slot name="rodape"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props:['card_header']
    }
</script>
