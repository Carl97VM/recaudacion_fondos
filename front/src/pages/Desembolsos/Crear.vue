<template>
  <q-page>
    <h6 align="center">Registrar Desembolso</h6>
    <div class="q-pa-md">

    </div>
    <div class="q-pa-md" style="max-width: 400px">
      <q-form @submit.prevent="guardarDesembolso" class="q-gutter-md" enctype="multipart/form-data" >

        <q-select filled v-model="form.responsable" :options="options.value" label="RESPONSABLE" lazy-rules
          :rules="[ (val) => (val && val != null) || 'SELECCIONE UN RESPONSABLE', ]" />

        <q-select filled v-model="form.candidato_id" :options="options2.value" label="CANDIDATO" lazy-rules
          :rules="[ (val) => (val && val != null) || 'SELECCIONE UN CANDIDATO', ]" />

        <q-input filled v-model="form.fecha_desembolso" label="Fecha" mask="date" :rules="['date']" >
          <template v-slot:append>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy cover transition-show="scale" transition-hide="scale" >
                <q-date v-model="form.fecha_desembolso">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>

        <q-input filled v-model="form.monto_desembolso" label="MONTO PARA EL DESEMBOLSO" lazy-rules
          :rules="[ (val) => (val && val.length > 0) || 'Introduce una suma de dinero', ]"
          @input="handleInput" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
        />

        <div align="center">
          <q-btn label="Registrar" type="submit" color="primary" />
        </div>
      </q-form>
      <!-- <pre>{{ form }}</pre> -->
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, reactive } from "vue";
import { useQuasar } from "quasar";
import { api } from "boot/axios";

export default defineComponent({
  name: "CrearDesembolso",

  methods: {
    handleInput(value) {
      this.form.monto_desembolso = value.replace(/[^0-9.]/g, "");
    },
  },

  setup() {
    const date = ref(new Date().toISOString().substr(0, 10));
    const $q = useQuasar();
    const formData = new FormData();
    const form = ref({
      fecha_desembolso: date,
      monto_desembolso: "",
      responsable: "",
      candidato_id: "",
    });
    const state = reactive({
      options: [],
      selectedOption: null,
    });
    const state2 = reactive({
      options2: [],
      selectedCandadatos: null,
    });
    function guardarDesembolso() {
      const formData = new FormData();
      formData.append("fecha_desembolso", form.value.fecha_desembolso);
      formData.append("monto_desembolso", form.value.monto_desembolso);
      formData.append("responsable", form.value.responsable);
      formData.append("candidato_id", form.value.candidato_id);
      console.table(form.value);
      api
        .post("/desembolso/store", form.value, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          if (res.data.answer) {
            form.value = {
              fecha_desembolso: date,
              monto_desembolso: "",
              responsable: "",
              candidato_id: "",
            };
            console.log(res.data);
            $q.notify({
              message: "Datos Registrados",
              color: "positive",
              position: "top",
            });
          } else {
            console.log(res.data);
            $q.notify({
              message: "No se pudo guardar",
              color: "negative",
              position: "top",
            });
          }
        })
        .catch((err) => {
          console.log(err);
          console.log(err.response?.data.errors);
          $q.notify({
            message: res.data.message,
            color: "negative",
            position: "top",
          });
        });
    }
    onMounted(async () => {
      try {
        let url = "/aportantes/index"; // cambiar a la url de usuarios
        const res = await api.get(url);
        console.log(res.data);

        // Asignar los datos al estado reactivo
        state.options.value = res.data.data.map((aportante) => ({
          label: aportante.label,
          value: aportante.id,
        }));
        console.log(state.options.value);

        let urlCandidatos = "/candidatos/index_canidatos";
        const resCandidatos = await api.get(urlCandidatos);
        console.log(resCandidatos.data);
        // Asignar los datos de candidatos al estado reactivo
        state2.options2.value = resCandidatos.data.data.map((candidatos) => ({
          label: candidatos.label + ' Tiene Bs. ' + (candidatos.monto_recaudado - candidatos.monto_usado),
          value: candidatos.id,
          monto_recaudado: candidatos.monto_recaudado
        }));
        console.log("Candidatos: ",state2.options2);
      } catch (error) {
        console.error("Error al obtener los datos:", error);
      }
    });

    return {
      form,
      ...state,
      ...state2,
      guardarDesembolso,
      date,
    };
  },
});
</script>
<style scoped>
.date-input {
  max-width: 300px;
}
</style>
