<template>
  <q-page>
    <h6 align="center">Lista de Desembolso</h6>
    <div class="q-pa-md" style="max-width: 400px">
      <q-table :rows="rows" row-key="name" flat bordered align="center">
        <template v-slot:body-cell-id="{ row }">
          <div class="q-gutter-sm">
            <!-- <q-btn flat icon="edit" @click="editarAportante(row.id)" /> -->
            <q-btn
              flat
              icon="delete"
              @click="confirmarEliminarDesembolso(row.id)"
            />
            <q-btn flat icon="info" @click="verInformesDesembolso(row.id)" />
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
  name: "ListaDesembolso",

setup() {
    const router = useRouter();
    const rows = ref([]);
    const $q = useQuasar();

    async function cargarDesembolso() {
      try {
        let url = "/desembolso/list";
        const res = await api.get(url);
        console.log(res.data);
        rows.value = res.data.data.map((datos) => ({
          candidato: datos.nombre_candidato,
          fecha: datos.fecha_desembolso,
          desembolsado: datos.monto_desembolso+' Bs.',
          gastado: datos.monto_gastado+' Bs.',
          id: datos.id
        }));
        console.log(rows.value);
      } catch (error) {
        console.error("Error al obtener los datos:", error);
      }
    }
    // function editarAportante(id) {
    //   // Lógica para editar el aportante con el ID proporcionado
    //   console.log("Editar aportante:", id);
    //   router.push({ name: 'editar-aportante', params: { id } });
    // }

    function confirmarEliminarDesembolso(id) {
      if (confirm("¿Estás seguro de eliminar este aportante?")) {
        eliminarAportante(id);
      }
    }
    function verInformesDesembolso(id) {
      router.push({ name: 'seguimiento-desembolso', params: { id } });
    }

    async function eliminarAportante(id) {
      try {
        const url = `/desembolso/${id}/destroy`;
        const res = await api.delete(url);
        if (res.data.answer === true) {
          $q.notify({
            message: "Registro Eliminado",
            color: "positive",
            position: "top",
          });
          cargarDesembolso();
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

    onMounted(cargarDesembolso);

    return {
      rows,
      // editarAportante,
      confirmarEliminarDesembolso,
      verInformesDesembolso,
    };
  },
})
</script>
