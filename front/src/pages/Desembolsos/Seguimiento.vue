<!-- <template>
  <q-page>
    <h6 align="center">Seguimiento de Desembolso</h6>
    <div align="center">
      <q-btn label="Volver" icon="arrow_back" @click="goBack" color="primary" flat />
      <q-btn icon="fa-solid fa-plus" color="warning" @click="goToView" label="Registrar Detalles" />
      <ul>
        <li v-for="(fecha, index) in respuestas.fecha" :key="index">
          <strong>Fecha de Entrega de Dinero:</strong> {{ fecha }} <br>
          <strong>Monto Asignado: </strong>{{ respuestas.monto[index] }} Bs.
        </li>
      </ul>
    </div>
    <div class="q-pa-md" style="max-width: 400px">
      <q-table :rows="rows" row-key="id" :key="tableKey" flat bordered>
        <template v-slot:body-cell-id="{ row }">
          <div class="q-gutter-sm">
            <q-btn flat icon="delete" @click="confirmarEliminarDetalle(row.id)" />
          </div>
        </template>
      </q-table>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, reactive } from 'vue'
import { useQuasar } from 'quasar'
import { api } from 'boot/axios'
import { useRoute } from 'vue-router'

export default defineComponent({
  name: "ListaDesembolso",
  setup() {
    const route = useRoute();
    const id_desembolso = ref(route.params.id);
    const $q = useQuasar();
    const respuestas = reactive({
      fecha: [],
      monto: [],
    });
    const desembolsos = ref([]);
    const rows = ref([]);
    const tableKey = ref('');

    onMounted(async () => {
      try {
        let url = `/rendicion_cuentas/${id_desembolso.value}/index`;
        const res = await api.get(url);
        if (res.data.answer === true) {
          console.log("Index: ",res.data)
          const seguimiento = res.data.data;
          respuestas.fecha = seguimiento.map(
            (aportante) => aportante.fecha_desembolso
          );
          respuestas.monto = seguimiento.map(
            (aportante) => aportante.monto_desembolso
          );
        } else {
          $q.notify({
            message: "Aportante no encontrado",
            color: "negative",
            position: "top",
          });
        }
      } catch (error) {
        console.error("Error al obtener los datos:", error);
      }
      await listarRendicion();
    });

    async function listarRendicion() {
    try {
      let url = `/rendicion_cuentas/${id_desembolso.value}/list`;
      const res2 = await api.get(url);
      if (res2.data.answer === true) {
        console.log("List: ",res2.data.data);
        rows.value = res2.data.data.map((datos) => ({
          fecha: datos.fecha_rendicion,
          monto: datos.monto_rendicion,
          id: datos.id
        }));
      } else {
        $q.notify({
          message: "Aportante no encontrado",
          color: "negative",
          position: "top",
        });
      }
    } catch (error) {
      console.error("Error al obtener los datos:", error);
    }
    };
    function confirmarEliminarDetalle(id) {
      if (confirm("¿Estás seguro de eliminar este aportante?")) {
        eliminarDetalles(id);
      }
    }
    async function eliminarDetalles(id) {
      try {
        const url = `/rendicion_cuentas/${id}/destroy`;
        const res = await api.delete(url);
        if (res.data.answer === true) {
          console.log(res.data);
          tableKey.value = Math.random().toString();
          $q.notify({
            message: "Registro Eliminado",
            color: "positive",
            position: "top",
          });
          listarRendicion(); // Actualizar datos
        } else {
          $q.notify({
            message: res.data.message,
            color: "negative",
            position: "top",
          });
        }
      } catch (error) {
        console.log("Error seguimiento destroy",error);
        $q.notify({
          message: "Ocurrió un error al eliminar el registro",
          color: "negative",
          position: "top",
        });
      }
    }

    return {
      respuestas,
      listarRendicion,
      rows,
      confirmarEliminarDetalle,
      tableKey,
      // columns,
      // goToView,
    };
  },
  methods: {
    goBack() {
      this.$router.go(-1); // Vuelve a la página anterior en el historial del router
    },
    goToView() {
      this.$router.push({ name: 'registro-desembolso', params: { id: this.id } })
    },
  },
});
</script>
 -->

 <!-- <template>
  <q-page>
    <h6 align="center">Seguimiento de Desembolso</h6>
    <div align="center">
      <q-btn label="Volver" icon="arrow_back" @click="goBack" color="primary" flat />
      <q-btn icon="fa-solid fa-plus" color="warning" @click="goToView" label="Registrar Detalles" />
      <q-btn icon="print" color="primary" @click="imprimirTabla" label="Imprimir Tabla" />
      <div class="header">
        <p><strong>Candidato/a:</strong> {{ de[0] }}</p>
        <p><strong>Responsable:</strong> {{ para[0] }}</p>
      </div>
      <ul>
        <li v-for="(fecha, index) in respuestas.fecha" :key="index">
          <strong>Fecha de Entrega de Dinero:</strong> {{ fecha }} <br>
          <strong>Monto Asignado: </strong>{{ respuestas.monto[index] }} Bs.
        </li>
      </ul>
    </div>
    <div class="q-pa-md" style="max-width: 400px">
      <q-table :rows="rows" row-key="id" :key="tableKey" flat bordered>
        <template v-slot:body-cell-id="{ row }">
          <div class="q-gutter-sm">
            <q-btn flat icon="delete" @click="confirmarEliminarDetalle(row.id)" />
          </div>
        </template>
      </q-table>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, reactive } from 'vue'
import { useQuasar } from 'quasar'
import { api } from 'boot/axios'
import { useRoute } from 'vue-router'

export default defineComponent({
  name: "ListaDesembolso",
  setup() {
    const route = useRoute();
    const id_desembolso = ref(route.params.id);
    const $q = useQuasar();
    const respuestas = reactive({
      fecha: [],
      monto: [],
    });
    const desembolsos = ref([]);
    const rows = ref([]);
    const tableKey = ref('');
    const de = ref('');
    const para = ref('');

    onMounted(async () => {
      try {
        let url = `/rendicion_cuentas/${id_desembolso.value}/index`;
        const res = await api.get(url);
        if (res.data.answer === true) {
          console.log("Index: ",res.data)
          const seguimiento = res.data.data;
          respuestas.fecha = seguimiento.map(
            (aportante) => aportante.fecha_desembolso
          );
          respuestas.monto = seguimiento.map(
            (aportante) => aportante.monto_desembolso
          );
          de.value = seguimiento.map(
            (aportante) => aportante[0].nombre_candidato
          );
          para.value = seguimiento.map(
            (aportante) => aportante[0].nombre_responsable
          );
        } else {
          $q.notify({
            message: "Aportante no encontrado",
            color: "negative",
            position: "top",
          });
        }
      } catch (error) {
        console.error("Error al obtener los datos:", error);
      }
      await listarRendicion();
    });
    console.log("de:",de);
    console.log("para:",para);

    async function listarRendicion() {
    try {
      let url = `/rendicion_cuentas/${id_desembolso.value}/list`;
      const res2 = await api.get(url);
      if (res2.data.answer === true) {
        console.log("List: ",res2.data.data);
        rows.value = res2.data.data.map((datos) => ({
          fecha: datos.fecha_rendicion,
          monto: datos.monto_rendicion,
          id: datos.id
        }));
      } else {
        $q.notify({
          message: "Aportante no encontrado",
          color: "negative",
          position: "top",
        });
      }
    } catch (error) {
      console.error("Error al obtener los datos:", error);
    }
    };
    function confirmarEliminarDetalle(id) {
      if (confirm("¿Estás seguro de eliminar este aportante?")) {
        eliminarDetalles(id);
      }
    }
    async function eliminarDetalles(id) {
      try {
        const url = `/rendicion_cuentas/${id}/destroy`;
        const res = await api.delete(url);
        if (res.data.answer === true) {
          console.log(res.data);
          tableKey.value = Math.random().toString();
          $q.notify({
            message: "Registro Eliminado",
            color: "positive",
            position: "top",
          });
          listarRendicion(); // Actualizar datos
        } else {
          $q.notify({
            message: res.data.message,
            color: "negative",
            position: "top",
          });
        }
      } catch (error) {
        console.log("Error seguimiento destroy",error);
        $q.notify({
          message: "Ocurrió un error al eliminar el registro",
          color: "negative",
          position: "top",
        });
      }
    }

    function imprimirTabla() {
      const printWindow = window.open('', '', 'width=600,height=600');
      printWindow.document.write(`
        <html>
          <head>
            <title>Impresión de tabla</title>
            <style>
              .header {
                text-align: center;
                margin-bottom: 20px;
              }
              table {
                width: 100%;
                border-collapse: collapse;
              }
              th, td {
                border: 1px solid black;
                padding: 8px;
              }
            </style>
          </head>
          <body>
            <div class="header">
              <h4>Seguimiento de Desembolso</h4>
              <p><strong>Candidato/a:</strong> ${de.value}</p>
              <p><strong>Responsable:</strong> ${para.value}</p>
            </div>
            <table>
              <thead>
                <tr>
                  <th>Fecha de Entrega de Dinero</th>
                  <th>Monto Asignado</th>
                </tr>
              </thead>
              <tbody>
                ${rows.value.map(row => `
                  <tr>
                    <td>${row.fecha}</td>
                    <td>${row.monto} Bs.</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </body>
        </html>
      `);
      printWindow.document.close();
      printWindow.print();
    }

    return {
      respuestas,
      listarRendicion,
      rows,
      confirmarEliminarDetalle,
      tableKey,
      de,
      para,
      imprimirTabla
    };
  },
  methods: {
    goBack() {
      this.$router.go(-1); // Vuelve a la página anterior en el historial del router
    },
    goToView() {
      this.$router.push({ name: 'registro-desembolso', params: { id: this.id } })
    },
  },
});
</script>

<style scoped>
.header {
  text-align: center;
  margin-bottom: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  border: 1px solid black;
  padding: 8px;
}
</style> -->

<template>
  <q-page>
    <h6 align="center">Seguimiento de Desembolso</h6>
    <div align="center">
      <q-btn label="Volver" icon="arrow_back" @click="goBack" color="primary" flat />
      <q-btn icon="fa-solid fa-plus" color="warning" @click="goToView" label="Registrar Detalles" />
      <q-btn icon="print" color="primary" @click="imprimirTabla" label="Imprimir Tabla" />
      <div class="header">
        <p><strong>Candidato/a:</strong> {{ candidato }}</p>
        <p><strong>Responsable:</strong> {{ responsable }}</p>
        <!-- <p><strong>Fecha:</strong> {{ fecha }}</p> -->
      </div>
      <ul>
        <li v-for="(fecha, index) in respuestas.fecha" :key="index">
          <strong>Fecha de Entrega de Dinero:</strong> {{ fecha }} <br>
          <strong>Monto Asignado: </strong>{{ respuestas.monto[index] }} Bs.
        </li>
      </ul>
    </div>
    <div class="q-pa-md" style="max-width: 400px">
      <q-table :rows="rows" row-key="id" :key="tableKey" flat bordered>
        <template v-slot:body-cell-id="{ row }">
          <div class="q-gutter-sm">
            <q-btn flat icon="delete" @click="confirmarEliminarDetalle(row.id)" />
          </div>
        </template>
      </q-table>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, reactive } from 'vue'
import { useQuasar } from 'quasar'
import { api } from 'boot/axios'
import { useRoute } from 'vue-router'

export default defineComponent({
  name: "ListaDesembolso",
  setup() {
    const route = useRoute();
    const id_desembolso = ref(route.params.id);
    const $q = useQuasar();
    const respuestas = reactive({
      fecha: [],
      monto: [],
    });
    const desembolsos = ref([]);
    const rows = ref([]);
    const tableKey = ref('');

    let candidato = ref('');
    let responsable = ref('');
    let fecha = ref('');

    onMounted(async () => {
      try {
        let url = `/rendicion_cuentas/${id_desembolso.value}/index`;
        const res = await api.get(url);
        if (res.data.answer === true) {
          console.log("Index: ", res.data)
          const seguimiento = res.data.data;
          respuestas.fecha = seguimiento.map(
            (aportante) => aportante.fecha_desembolso
          );
          respuestas.monto = seguimiento.map(
            (aportante) => aportante.monto_desembolso
          );
          if (seguimiento.length > 0) {
            candidato.value = seguimiento[0].nombre_candidato;
            responsable.value = seguimiento[0].nombre_responsable;
          }
          if (respuestas.fecha.length > 0) {
            fecha.value = respuestas.fecha[0];
          }
        } else {
          $q.notify({
            message: "Aportante no encontrado",
            color: "negative",
            position: "top",
          });
        }
      } catch (error) {
        console.error("Error al obtener los datos:", error);
      }
      await listarRendicion();
    });

    async function listarRendicion() {
      try {
        let url = `/rendicion_cuentas/${id_desembolso.value}/list`;
        const res2 = await api.get(url);
        if (res2.data.answer === true) {
          console.log("List: ", res2.data.data);
          rows.value = res2.data.data.map((datos) => ({
            fecha: datos.fecha_rendicion,
            monto: datos.monto_rendicion,
            id: datos.id
          }));
        } else {
          $q.notify({
            message: "Aportante no encontrado",
            color: "negative",
            position: "top",
          });
        }
      } catch (error) {
        console.error("Error al obtener los datos:", error);
      }
    };

    function confirmarEliminarDetalle(id) {
      if (confirm("¿Estás seguro de eliminar este aportante?")) {
        eliminarDetalles(id);
      }
    }

    async function eliminarDetalles(id) {
      try {
        const url = `/rendicion_cuentas/${id}/destroy`;
        const res = await api.delete(url);
        if (res.data.answer === true) {
          console.log(res.data);
          tableKey.value = Math.random().toString();
          $q.notify({
            message: "Registro Eliminado",
            color: "positive",
            position: "top",
          });
          listarRendicion(); // Actualizar datos
        } else {
          $q.notify({
            message: res.data.message,
            color: "negative",
            position: "top",
          });
        }
      } catch (error) {
        console.log("Error seguimiento destroy", error);
        $q.notify({
          message: "Ocurrió un error al eliminar el registro",
          color: "negative",
          position: "top",
        });
      }
    }

    function imprimirTabla() {
      const printWindow = window.open('', '', 'width=600,height=600');
      printWindow.document.write(`
        <html>
          <head>
            <title>Impresión de tabla</title>
            <style>
              .header {
                text-align: center;
                margin-bottom: 20px;
              }
              table {
                width: 100%;
                border-collapse: collapse;
              }
              th, td {
                border: 1px solid black;
                padding: 8px;
              }
            </style>
          </head>
          <body>
            <div class="header">
              <h4>Seguimiento de Desembolso</h4>
              <p><strong>Candidato/a:</strong> ${candidato.value}</p>
              <p><strong>Responsable:</strong> ${responsable.value}</p>
              <p><strong>Fecha:</strong> ${fecha.value}</p>
            </div>
            <table>
              <thead>
                <tr>
                  <th>Fecha de Entrega de Dinero</th>
                  <th>Monto Asignado</th>
                </tr>
              </thead>
              <tbody>
                ${rows.value.map(row => `<tr><td>${row.fecha}</td><td>${row.monto}</td></tr>`).join('')}
              </tbody>
            </table>
          </body>
        </html>
      `);
      printWindow.document.close();
      printWindow.print();
    }

    return {
      respuestas,
      listarRendicion,
      rows,
      confirmarEliminarDetalle,
      tableKey,
      candidato,
      responsable,
      fecha,
      imprimirTabla
    };
  },
  methods: {
    goBack() {
      this.$router.go(-1); // Vuelve a la página anterior en el historial del router
    },
    goToView() {
      this.$router.push({ name: 'registro-desembolso', params: { id: this.id } })
    },
  },
});
</script>
