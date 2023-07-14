<template>
  <q-page>
    <h6 align="center">Registrar Desembolso</h6>
    <div class="q-pa-md" style="max-width: 400px">
      <q-form @submit.prevent="guardarDetalleDesembolso" class="q-gutter-md">
        <q-input filled v-model="form.descripcion" label="DESCRIPCION" />
        <q-input filled v-model="form.monto_detalle" label="MONTO GASTADO" />
        <div align="center">
          <q-btn label="Volver" icon="arrow_back" @click="goBack" color="primary" flat />
          <q-btn label="Guardar" type="submit" color="primary" />
        </div>
      </q-form>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, reactive } from "vue";
import { useQuasar } from "quasar";
import { api } from "boot/axios";
import { useRoute } from 'vue-router'

export default defineComponent({
  name: "CrearDesembolso",
  setup(){
    const route = useRoute()
    const id = ref(route.params.id)
    console.log("ID para trabajar: ",id);
    const $q = useQuasar()
    const form = reactive({
      descripcion: '',
      monto_detalle: '',
      id: id.value,
    })

    function resetForm() {
      form.descripcion = '';
      form.monto_detalle = '';
    }

    async function guardarDetalleDesembolso() {
      console.table(form);
      try {
        const url = `/detalles/store`;
        console.log("RUTA PARA ACTUALIZAR: ", url);
        const res = await api.post(url, form);
        if (res.data.answer === true) {
          console.log("Respuesta Detalles: ",res.data);
          $q.notify({
            message: res.data.message,
            color: 'positive',
            position: 'top'
          });
          resetForm();
        } else {
          $q.notify({
            message: 'Ocurrió un error al Registrar el Detalle',
            color: 'negative',
            position: 'top'
          });
        }
      } catch (error) {
        console.log(error);
        $q.notify({
          message: 'Ocurrió un error al Registrar el Detalle',
          color: 'negative',
          position: 'top'
        });
      }
    }

    return {
      form,
      guardarDetalleDesembolso
    }

  },
  methods: {
    goBack() {
      this.$router.go(-1); // Vuelve a la página anterior en el historial del router
    },

    // Resto de los métodos del componente
  },
});
</script>

<style scoped>
.date-input {
  max-width: 300px;
}
</style>
