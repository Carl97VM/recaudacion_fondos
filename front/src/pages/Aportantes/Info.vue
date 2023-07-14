<template>
  <div>
    <h6 align="center">Informes de Aportes del Aportante {{ aportanteId }}</h6>
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

export default defineComponent({
  name: "InformesAportanteView",

  setup() {
    const $q = useQuasar();
    const route = useRoute();
    const aportanteId = ref(route.params.id);
    const informes = ref([]);
    const tituloInforme = `Aportes realizados hasta la fecha (${format(
      new Date(),
      "dd/MM/yyyy"
    )})`;

    // Definir las columnas para el q-table
    const columns = [
      {
        name: "candidatos_nombre",
        required: true,
        label: "Candidato",
        align: "left",
        field: "candidatos_nombre",
        sortable: true,
      },
      {
        name: "fecha_inicio_aporte",
        required: true,
        label: "Fecha de Inicio",
        align: "left",
        field: "fecha_inicio_aporte",
        sortable: true,
      },
      {
        name: "fecha_fin_aporte",
        required: true,
        label: "Fecha de Fin",
        align: "left",
        field: "fecha_fin_aporte",
        sortable: true,
      },
      {
        name: "descripcion",
        required: true,
        label: "Descripción",
        align: "left",
        field: "descripcion",
        sortable: true,
      },
      {
        name: "monto",
        required: true,
        label: "Monto",
        align: "left",
        field: "monto",
        sortable: true,
      },
    ];

    onMounted(async () => {
      try {
        const url = `/aportes_candidato/${aportanteId.value}/informes_aportantes`;
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
      const nombreArchivo = `informes_aportes_${aportanteId.value}_${fechaActual}.pdf`;
      const doc = new jsPDF();
      const tableData = informes.value.map((informe) => [
        informe.candidatos_nombre,
        informe.fecha_inicio_aporte,
        informe.fecha_fin_aporte,
        informe.descripcion,
        informe.monto,
      ]);
      doc.text(tituloInforme, 14, 10);
      doc.autoTableSetDefaults({
        headStyles: { fillColor: [79, 195, 247] },
      });

      doc.autoTable({
        head: [
          [
            "Candidato",
            "Fecha Inicio de Aporte",
            "Fecha Fin de Aporte",
            "Descripción",
            "Monto Aportado",
          ],
        ],
        body: tableData,
        startY: 20,
        styles: {
          halign: "center",
          valign: "middle",
        },
        didDrawPage: function (data) {
          // Ajusta el tamaño de la página del PDF según el contenido de la tabla
          doc.setPage(data.pageCount);
        },
      });

      doc.save(nombreArchivo);
    }

    return {
      aportanteId,
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

