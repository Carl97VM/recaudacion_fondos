<template>
  <div>
    <h6 align="center">Informes de Desembolsos del Candidato</h6>
    <div class="q-mb-md">
      <!-- Botón para imprimir todos los informes de aportes -->
      <q-btn
        label="Volver"
        icon="arrow_back"
        @click="goBack"
        color="primary"
        flat
      />
      <q-btn
        class="q-ml-md print-button"
        label="Imprimir Aportes"
        color="primary"
        @click="imprimirTodos"
      />

      <!-- Mostrar los informes de aportes en un q-table -->
      <q-table
        :rows="informes"
        row-key="id"
        :rows-per-page-options="[5, 10, 15]"
        :pagination="true"
        :columns="columns"
      >
        <template v-slot:body="props">
          <q-tr :props="props">
            <q-td v-for="col in columns" :key="col.name" :props="props">
              <!-- Personaliza el formato del campo created_at -->
              <template v-if="col.name === 'created_at'">
                {{ formatDate(props.row[col.name]) }}
              </template>
              <!-- Resto de los campos -->
              <template v-else>
                {{ props.row[col.name] }}
              </template>
            </q-td>
          </q-tr>
        </template>
      </q-table>
    </div>
  </div>
</template>
<style>
.print-button {
  margin-left: 5px; /* Ajusta este valor según tu preferencia */
}
</style>
<script>
import { defineComponent, onMounted, ref } from "vue";
import { useQuasar } from "quasar";
import { api } from "boot/axios";
import { useRoute } from "vue-router";
import { jsPDF } from "jspdf";
import autoTable from "jspdf-autotable";
import { format } from "date-fns";
import numeral from "numeral";
import "numeral/locales/es";

export default defineComponent({
  name: "InformesDesembolsosView",
  setup() {
    const $q = useQuasar();
    const route = useRoute();
    const candidatoId = ref(route.params.id);
    const informes = ref([]);
    const tituloInforme = `Desembolsos realizados hasta la fecha (${format(
      new Date(),
      "dd/MM/yyyy"
    )})`;
    const columns = [
      {
        name: "nombre_candidato",
        required: true,
        label: "Candidato",
        align: "center",
        field: "nombre_candidato",
        sortable: true,
      },
      {
        name: "nombre_candidatura",
        required: true,
        label: "Candidatura",
        align: "center",
        field: "nombre_candidatura",
        sortable: true,
      },
      {
        name: "nombre_responsable",
        required: true,
        label: "Responsable",
        align: "center",
        field: "nombre_responsable",
        sortable: true,
      },
      {
        name: "monto_usado",
        required: true,
        label: "Monto Gastado",
        align: "center",
        format: (value) => numeral(value).format("$0,0.00"),
        field: "monto_usado",
        sortable: true,
      },
      {
        name: "created_at",
        required: true,
        label: "Fecha Desembolso",
        align: "center",
        format: (value) => format(new Date(value), "dd-MM-yyyy HH:mm:ss"),
        field: "created_at",
        sortable: true,
      },
    ];
    onMounted(async () => {
      try {
        const url = `/desembolso/${candidatoId.value}/informes_candidato_desembolsos`;
        const res = await api.get(url);
        console.log("Informe Desembolsos: ", res.data);
        if (res.data.answer === true) {
          informes.value = res.data.data;
        } else {
          $q.notify({
            message: "Sin datos para el aportante",
            color: "negative",
            position: "top",
          });
        }
      } catch (error) {
        console.log(error);
        $q.notify({
          message: "Ocurrió un error al obtener los desembolsos del candidato",
          color: "negative",
          position: "top",
        });
      }
    });

    // Función para imprimir todos los informes de aportes
    function imprimirTodos() {
      const fechaActual = format(new Date(), "dd/MM/yyyy");
      const nombreArchivo = `informes_Candidatos_Desembolsos_${candidatoId.value}_${fechaActual}.pdf`;
      const doc = new jsPDF();
      const tableData = informes.value.map((informe) => [
        informe.nombre_candidato,
        informe.nombre_candidatura,
        informe.nombre_responsable,
        informe.monto_usado,
        informe.created_at,
      ]);

      doc.text(tituloInforme, 14, 10);

      doc.autoTableSetDefaults({
        headStyles: { fillColor: [79, 195, 247] },
        styles: {
          cellPadding: 5,
          fontSize: 10,
          fontStyle: "normal",
          halign: "center",
          valign: "middle",
        },
      });

      doc.autoTable({
        head: [
          [
            "Candidato",
            "Candidatura",
            "Responsable",
            "Monto Gastado",
            "Fecha de Desembolso",
          ],
        ],
        body: tableData,
        startY: 20,
        styles: {
          cellPadding: 5,
          fontSize: 8,
          fontStyle: "normal",
          halign: "center",
          valign: "middle",
        },
        headStyles: {
          fillColor: [79, 195, 247],
          textColor: [255, 255, 255],
          fontSize: 9,
          fontStyle: "bold",
        },
        columnStyles: {
          1: { fontStyle: "bold", halign: "right" }, // Estilos para la columna de monto
          2: { fontStyle: "bold", halign: "center" }, // Estilos para la columna de fecha
        },
        didDrawPage: function (data) {
          // Ajusta el tamaño de la página del PDF según el contenido de la tabla
          doc.setPage(data.pageCount);
        },
      });

      doc.save(nombreArchivo);
    }

    return {
      candidatoId,
      informes,
      columns,
      imprimirTodos,
    };
  },
  methods: {
    goBack() {
      this.$router.go(-1); // Vuelve a la página anterior en el historial del router
    },
    formatDate(date) {
      return format(new Date(date), "dd-MM-yyyy HH:mm:ss");
    },
  },
});
</script>
