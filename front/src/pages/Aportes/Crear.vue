<template>
  <q-page>
    <h6 align="center">Registro de Aportes</h6>
    <div class="q-pa-md" style="max-width: 400px">
      <q-form
        @submit.prevent="guardarAportes"
        class="q-gutter-md"
        enctype="multipart/form-data"
      >
        <q-select
          filled
          v-model="form.aportantes_id"
          :options="options.value"
          label="APORTANTE"
          lazy-rules
          :rules="[(val) => (val && val != null) || 'SELECCIONE UN APORTANTE']"
        />
        <!-- <template v-slot:option="{ label }">
            <div>{{ label }}</div>
          </template>
          <template v-slot:selected="{ label }">
            <div>{{ label }}</div>
          </template>
        </q-select> -->

        <q-select
          filled
          v-model="form.tipo_aporte"
          :options="tipoAporteOptions"
          label="TIPO DE APORTE"
          lazy-rules
          :rules="[(val) => (val && val.length > 0) || 'Selecciona una opción']"
        />

        <q-input
          filled
          v-model="form.fecha_inicio_aporte"
          label="INICIO DE APORTE"
          mask="date"
          :rules="['date']"
        >
          <template v-slot:append>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-date v-model="form.fecha_inicio_aporte">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>

        <q-input
          filled
          v-model="form.fecha_fin_aporte"
          label="FIN DE APORTE"
          mask="date"
          :rules="['date']"
        >
          <template v-slot:append>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-date v-model="form.fecha_fin_aporte">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>

        <q-input
          filled
          v-model="form.descripcion"
          label="DESCRIPCION DEL APORTE"
          lazy-rules
          :rules="[(val) => (val && val.length > 0) || 'Introduce un valor']"
        />
        <q-select
          filled
          v-model="form.candidato_id"
          :options="options2.value"
          label="CANDIDATO"
          lazy-rules
          :rules="[(val) => (val && val != NULL) || 'SELECCIONE UN CANDIDATO']"
        />

        <q-input
          filled
          v-model="form.monto_avaluado"
          label="MONTO PARA EL CANDIDATO"
          lazy-rules
          :rules="[
            (val) => (val && val.length > 0) || 'Introduce una suma de dinero',
          ]"
          @input="handleInput"
          onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
        />
        <!-- <q-file
          filled
          v-model="form.archivo"
          label="ARCHIVO DEL APORTE"
          accept="application/pdf"
          lazy-rules
          :rules="[(val) => (val && val.name) || 'Selecciona un archivo PDF']"
        /> -->
        <q-file
          filled
          bottom-slots
          v-model="form.archivo"
          label="ARCHIVO DEL APORTE"
          accept="application/pdf"
          lazy-rules
          :rules="[(val) => val || 'Selecciona un archivo PDF']"
          counter
        >
          <template v-slot:prepend>
            <q-icon name="cloud_upload" @click.stop.prevent />
          </template>
          <template v-slot:append>
            <q-icon
              name="close"
              @click.stop.prevent="model = null"
              class="cursor-pointer"
            />
          </template>

          <template v-slot:hint> Field hint </template>
        </q-file>

        <div>
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
  name: "CrearAporte",

  methods: {
    handleInput(value) {
      this.form.monto_avaluado = value.replace(/[^0-9.]/g, "");
    },
  },

  setup() {
    const date = ref(new Date().toISOString().substr(0, 10));
    const date1 = ref(new Date().toISOString().substr(0, 10));
    const $q = useQuasar();
    const form = ref({
      tipo_aporte: "",
      fecha_inicio_aporte: date,
      fecha_fin_aporte: date1,
      descripcion: "",
      archivo: "",
      aportantes_id: "",
      candidato_id: "",
      monto_avaluado: "",
    });
    const state = reactive({
      options: [],
      selectedOption: null,
    });
    const state2 = reactive({
      options2: [],
      selectedCandadatos: null,
    });
    const tipoAporteOptions = ref(["DINERO", "OTROS"]);
    function guardarAportes() {
      const fechaInicio = new Date(form.value.fecha_inicio_aporte);
      const fechaFin = new Date(form.value.fecha_fin_aporte);
      const formData = new FormData();

      // Agregar los campos del formulario al FormData
      formData.append("tipo_aporte", form.value.tipo_aporte);
      formData.append("fecha_inicio_aporte", form.value.fecha_inicio_aporte);
      formData.append("fecha_fin_aporte", form.value.fecha_fin_aporte);
      formData.append("descripcion", form.value.descripcion);
      formData.append("aportantes_id", form.value.aportantes_id);
      formData.append("candidato_id", form.value.candidato_id);
      formData.append("monto_avaluado", form.value.monto_avaluado);
      formData.append("archivo", formData);
      if (fechaFin < fechaInicio) {
        $q.notify({
          message: "La fecha de fin no puede ser menor a la fecha de inicio",
          color: "negative",
          position: "top",
        });
        return;
      }
      console.table(form.value);
      api
        .post("/aportes/store", form.value, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          if (res.data.answer) {
            form.value = {
              tipo_aporte: "",
              fecha_inicio_aporte: date,
              fecha_fin_aporte: date1,
              descripcion: "",
              aportantes_id: "",
              candidato_id: "",
              archivo: "",
              monto_avaluado: "",
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
            message: "Ocurrio un error",
            color: "negative",
            position: "top",
          });
        });
    }
    onMounted(async () => {
      try {
        let url = "/aportantes/index";
        const res = await api.get(url);
        console.log(res.data);

        // Asignar los datos al estado reactivo
        state.options.value = res.data.data.map((aportante) => ({
          label: aportante.label,
          value: aportante.id,
        }));
        console.log(state.options.value);

        let urlCandidatos = "/candidatos/index";
        const resCandidatos = await api.get(urlCandidatos);
        console.log(resCandidatos.data);
        // Asignar los datos de candidatos al estado reactivo
        state2.options2.value = resCandidatos.data.data.map((candidatos) => ({
          label: candidatos.label,
          value: candidatos.id,
        }));
        console.log(state2.options2.value);
      } catch (error) {
        console.error("Error al obtener los datos:", error);
      }
    });

    return {
      form,
      ...state,
      ...state2,
      guardarAportes,
      date,
      date1,
      tipoAporteOptions,
    };
  },
});
</script>
<style scoped>
.date-input {
  max-width: 300px;
}
</style>
