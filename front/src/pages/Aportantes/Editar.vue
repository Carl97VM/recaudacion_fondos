<template>
  <q-page>
    <h6 align="center">Editar Aportante</h6>
    <div class="q-pa-md" style="max-width: 400px">
      <q-form @submit.prevent="guardarEdicionAportante" class="q-gutter-md">
        <q-input filled v-model="form.ci" label="CARNET DE IDENTIDAD" />
        <q-input filled v-model="form.nombres" label="NOMBRES" />
        <q-input filled v-model="form.apellidos" label="APELLIDOS" />
        <q-input filled v-model="form.celular" label="CELULAR" />
        <q-input filled v-model="form.profesion_oficio" label="PROFESION OFICIO" />
        <q-input filled v-model="form.ciudad" label="CIUDAD" />
        <!-- <q-input type="hidden" v-model="form.id" /> -->
        <div align="center">
          <q-btn label="Volver" icon="arrow_back" @click="goBack" color="primary" flat />
          <q-btn label="Guardar" type="submit" color="primary" />
        </div>
      </q-form>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { useQuasar } from 'quasar'
import { api } from 'boot/axios'
import { useRoute } from 'vue-router'

export default defineComponent({
  name: 'EditarAportante',

  setup() {
    const $q = useQuasar()
    const form = ref({
      id: '',
      ci: '',
      nombres: '',
      apellidos: '',
      celular: '',
      profesion_oficio: '',
      ciudad: ''
    })

    const route = useRoute()
    const id = ref(route.params.id)
    console.log("ID para trabajar: ",id);
    onMounted(async () =>{
      try {
        let url = `/aportantes/${id.value}/edit`;
        // console.log("URL PARA CARGAR DATOS DE IDCION",url);
          const res = await api.get(url)
          console.log("Respuesta de la peticion: ",res.data);
          if (res.data.answer === true) {
          const aportante = res.data.data
          console.log("Datos del Backend",aportante);
          // Asigna los datos del aportante al formulario
          form.value.id = aportante.id
          form.value.ci = aportante.ci
          form.value.nombres = aportante.nombres
          form.value.apellidos = aportante.apellidos
          form.value.celular = aportante.celular
          form.value.profesion_oficio = aportante.profesion_oficio
          form.value.ciudad = aportante.ciudad
          } else {
          $q.notify({
            message: 'Aportante no encontrado',
            color: 'negative',
            position: 'top'
          })
          }
      } catch (e) {
        console.log("ERROR al obtener los datos", e);
      }
    })



    async function guardarEdicionAportante() {
      try {
        const url = `/aportantes/${id.value}/update`;
        console.log("RUTA PARA ACTUALIZAR: ", url);
        const res = await api.put(url, form.value);
        if (res.data.answer === true) {
          $q.notify({
            message: 'Aportante editado exitosamente',
            color: 'positive',
            position: 'top'
          });
        } else {
          $q.notify({
            message: 'Ocurrió un error al editar el aportante',
            color: 'negative',
            position: 'top'
          });
        }
      } catch (error) {
        console.log(error);
        $q.notify({
          message: 'Ocurrió un error al editar el aportante',
          color: 'negative',
          position: 'top'
        });
      }
    }


    return {
      form,
      guardarEdicionAportante
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1); // Vuelve a la página anterior en el historial del router
    },

    // Resto de los métodos del componente
  },
})
</script>
