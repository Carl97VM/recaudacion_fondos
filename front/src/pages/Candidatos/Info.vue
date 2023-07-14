<template>
  <div>
    <h6 align="center">Informes de Aportes del Candidato</h6>
    <h6 align="center">{{ tituloInforme }}</h6>

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
    </div>

    <!-- Mostrar los informes de aportes en un q-table -->
    <q-table
      :rows="informes"
      row-key="id"
      :rows-per-page-options="[5, 10, 15]"
      :pagination="true"
      :columns="columns"
    ></q-table>
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
  name: "InformesAportanteView",

  setup() {
    const $q = useQuasar();
    const route = useRoute();
    const candidatoId = ref(route.params.id);
    const informes = ref([]);
    const tituloInforme = `Aportes realizados hasta la fecha (${format(
      new Date(),
      "dd/MM/yyyy"
    )})`;

    // Definir las columnas para el q-table
    const columns = [
      {
        name: "nombre_aportantes",
        required: true,
        label: "Aportante",
        align: "center",
        field: "nombre_aportantes",
        sortable: true,
      },
      {
        name: "monto",
        required: true,
        label: "Monto Recivido",
        align: "center",
        format: (value) => numeral(value).format("$0,0.00"),
        field: "monto",
        sortable: true,
      },
      {
        name: "created_at",
        required: true,
        label: "Fecha Realizado",
        align: "center",
        format: (value) => format(new Date(value), "dd-MM-yyyy HH:mm:ss"),
        field: "created_at",
        sortable: true,
      },
      {
        name: "descripcion",
        required: true,
        label: "Descripcion",
        align: "center",
        field: "descripcion",
        sortable: true,
      },
    ];

    onMounted(async () => {
      try {
        const url = `/aportes_candidato/${candidatoId.value}/informes_candidato`;
        const res = await api.get(url);
        console.log(res.data);
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
          message: "Ocurrió un error al obtener los informes de aportes",
          color: "negative",
          position: "top",
        });
      }
    });

    // Función para imprimir todos los informes de aportes
    function imprimirTodos() {
      const fechaActual = format(new Date(), "dd/MM/yyyy");
      const nombreArchivo = `informes_Candidatos_${candidatoId.value}_${fechaActual}.pdf`;
      const doc = new jsPDF();
      const tableData = informes.value.map((informe) => [
        informe.nombre_aportantes,
        informe.monto,
        informe.created_at,
        informe.descripcion,
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
        head: [["Aportante", "Monto", "Fecha de Aporte", "Descripción"]],
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
      tituloInforme,
    };
  },
  methods: {
    goBack() {
      this.$router.go(-1); // Vuelve a la página anterior en el historial del router
    },

    // Resto de los métodos del componente
  },
});
</script>

