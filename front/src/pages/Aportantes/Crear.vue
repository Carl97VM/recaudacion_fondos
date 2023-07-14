<template>
  <q-page>
    <h6 align="center">Registro de Aportantes</h6>
    <div class="q-pa-md" style="max-width: 400px">

      <q-form @submit.prevent="guardarAportante" class="q-gutter-md">
        <q-input filled v-model="form.ci" label="CARNET DE IDENTIDAD"
          hint="En caso de tener complemento separar con guión Ej: 111111-1B " lazy-rules
          :rules="[val => val && val.length > 0 || 'Introduce un valor']" />
        <q-input filled v-model="form.nombres" label="NOMBRES" lazy-rules
          :rules="[val => val && val.length > 0 || 'Introduce un valor']" />
        <q-input filled v-model="form.apellidos" label="APELLIDOS" lazy-rules
          :rules="[val => val && val.length > 0 || 'Introduce un valor']" />
        <q-input filled v-model="form.celular" label="CELULAR" lazy-rules
          :rules="[val => val && val.length > 0 || 'Introduce un valor']" />
        <q-input filled v-model="form.profesion_oficio" label="PROFESION OFICIO" lazy-rules
          :rules="[val => val && val.length > 0 || 'Introduce un valor']" />
        <q-input filled v-model="form.ciudad" label="CIUDAD" lazy-rules
          :rules="[val => val && val.length > 0 || 'Introduce un valor']" />
        <div>
          <q-btn label="Submit" type="submit" color="primary" />
        </div>
      </q-form>

    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { useQuasar } from 'quasar'
import { api } from 'boot/axios'
export default defineComponent({
  name: 'AportanteCrear',

  setup() {
    const $q = useQuasar();
    const form = ref({
      ci: "",
      nombres: "",
      apellidos: "",
      celular: "",
      profesion_oficio: "",
      ciudad: "",
    })
    function guardarAportante() {
      console.log('enviando')
      api.post('/aportantes/store', form.value).then((res) => {

        if (res.data.answer) {
          form.value = {
            ci: "",
            nombres: "",
            apellidos: "",
            celular: "",
            profesion_oficio: "",
            ciudad: "",
          };
          $q.notify({
            message: 'Datos Registrados',
            color: 'positive',
            position:'top'
          })
        } else {
          $q.notify({
            message: 'No se pudo guardar',
            color: 'negative',
            position:'top'
          })
        }
      }).catch((err) => {
        console.log(err)
        console.log(err.response?.data.errors)
        $q.notify({
          message: 'Ocurrio un error',
          color: 'negative',
          position:'top'
        })
      })
    }
    return {
      form,
      guardarAportante
    }
  }
})
</script>
