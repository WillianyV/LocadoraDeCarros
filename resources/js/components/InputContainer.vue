<template>
    <div class="form-group">
        <label :for="id" class="form-label">{{ titulo }}</label>
        <slot></slot>        
        <div :id="nomeHelp" class="form-text">{{ textoHelp }}</div>
    </div>
</template>

<script>
    export default {
        props:[
            'id', 'titulo','nomeHelp', 'textoHelp']
    }
</script>
