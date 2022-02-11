<template>
    <div :class="estilo" role="alert">
        {{ titulo }}
        <hr>
        <p>{{ detalhes.mensagem }}</p>
        <ul v-if="detalhes.dados">
            <li v-for="e, key in detalhes.dados" :key="key">{{ e }}</li>
        </ul>
        <!-- {{ detalhes }} -->
    </div>
</template>

<script>
    export default {
        props:['tipo', 'detalhes', 'titulo'],
        computed:{
            estilo(){
                return 'alert alert-'+this.tipo
            }
        }
    }
</script>
