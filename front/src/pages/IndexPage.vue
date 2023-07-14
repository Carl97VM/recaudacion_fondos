<template>
  <q-page >
    <Grafico />
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import Grafico from '../pages/Aportes/Grafico.vue'
export default defineComponent({
  name: 'IndexPage',
  components: {
    Grafico
  }
})
</script>

