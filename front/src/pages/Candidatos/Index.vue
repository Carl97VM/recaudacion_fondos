<template>
  <q-page>
    <h6 align="center">Lista de Candidatos</h6>
    <div class="q-pa-md" style="max-width: 400px">
      <q-table :rows="rows" row-key="name" flat bordered>
        <template v-slot:body-cell-id="{ row }">
          <div class="q-gutter-sm">
            <!-- <q-btn flat icon="edit" @click="editarAportante(row.id)" /> -->
            <q-btn flat icon="delete" @click="confirmarEliminarAportante(row.id)" />
            <q-btn flat icon="info" @click="verInformesAportante(row.id)" />
            <q-btn flat icon="percent" @click="verInformesDesembolsos(row.id)" />
          </div>
        </template>
      </q-table>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, reactive } from "vue";
import { useQuasar } from "quasar";
import { api } from "boot/axios";
import { useRouter } from 'vue-router';

export default defineComponent({
  name: "CrearAporte",
  setup() {
    const router = useRouter();
    const rows = ref([]);
    const $q = useQuasar();

    async function cargarAportantes() {
      try {
        let url = "/candidatos/list";
        const res = await api.get(url);
        console.log(res.data);
        rows.value = res.data.data.map((datos) => ({
          names: datos.label,
          celular: datos.celular,
          candidatura: datos.nombre_candidatura,
          id: datos.id
        }));
      } catch (error) {
        console.error("Error al obtener los datos:", error);
      }
    }

    // function editarAportante(id) {
    //   // Lógica para editar el aportante con el ID proporcionado
    //   console.log("Editar aportante:", id);
    //   router.push({ name: 'editar-aportante', params: { id } });
    // }

    function confirmarEliminarAportante(id) {
      if (confirm("¿Estás seguro de eliminar este aportante?")) {
        eliminarAportante(id);
      }
    }
    function verInformesAportante(id) {
      router.push({ name: 'informes-candidato', params: { id } });
    }
    function verInformesDesembolsos(id) {
      router.push({ name: 'informes-desembolsos', params: { id } });
    }

    async function eliminarAportante(id) {
      try {
        const url = `/candidatos/${id}/destroy`;
        const res = await api.delete(url);
        if (res.data.answer === "ok") {
          $q.notify({
            message: "Registro Eliminado",
            color: "positive",
            position: "top",
          });
          cargarAportantes();
        } else {
          $q.notify({
            message: res.data.message,
            color: "negative",
            position: "top",
          });
        }
      } catch (error) {
        console.log(error);
        $q.notify({
          message: "Ocurrió un error al eliminar el registro",
          color: "negative",
          position: "top",
        });
      }
    }

    onMounted(cargarAportantes);

    return {
      rows,
      // editarAportante,
      confirmarEliminarAportante,
      verInformesAportante,
      verInformesDesembolsos,
    };
  },
});
</script>
