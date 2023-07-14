<!-- <template>
  <div
    class="row justify-between q-gutter-x-lg flex flex-center"
    style="height: 90vh"
  >
    <apexchart
      class="col-md-5"
      type="donut"
      :series="series"
      :options="options"
    />
  </div>
</template>
<script>
import { defineComponent, ref, onMounted, reactive } from "vue";
import { useQuasar } from "quasar";
import { api } from "boot/axios";
export default defineComponent({
  name: "chartVenta",
  setup() {
    const state = reactive({
      options: [],
      candidatos: [],
      selectedOption: null,
    });
    // primer apexchart
    const series = ref([]);
    const options = ref([]);
    onMounted(async () => {
      try {
        let url = "/aportes_candidato/estadisticas";
        const res = await api.get(url);
        console.log("SOLO RESPUESTA DEL BACKEND", res.data);
        console.log("TODA LA RESPUESTA DEL BACKEND", res);
        state.options = res.data.data.map((estadisticas) => ({
          aportes: estadisticas.aportes,
        }));

        series.value = state.options.map((estadistica) => estadistica.aportes);

        const data = res.data;

        options.value = {
          chart: {
            type: "donut",
          },
          labels: data.data.map(
            (item) => item.nombre_candidatura + " - " + item.candidatos + ' - Bs. ' + (item.aportado - item.usado)
          ),
        };
      } catch (error) {
        console.error("Error al obtener los datos:", error);
      }
    });
    options.value.chart = {
      type: "donut",
      width: 700,
      height: 600,
    };
    options.value.plotOptions = {
      pie: {
        size: 500,
        donut: {
          size: "1%",
        },
      },
      dropShadow: {
        enabled: true,
        top: 0,
        left: 0,
        blur: 3,
        opacity: 0.5,
      },
    };
    options.value.colors = [
      "#0088cc",
      "#ff0000",
      "#00cccc",
      "#fbcb00",
      "#c1bdff",
      "#008e00",
      "#14155a",
    ];

    return {
      series,
      options,
    };
  },
});
</script> -->

<template>
  <div class="row justify-between q-gutter-x-lg flex flex-center" style="height: 90vh">
    <apexchart class="col-md-5" type="donut" :series="series" :options="options" />
  </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import { api } from "boot/axios";
import ApexCharts from "apexcharts";

export default defineComponent({
  name: "chartVenta",
  setup() {
    const series = ref([]);
    const options = ref({});

    onMounted(async () => {
      try {
        const url = "/aportes_candidato/estadisticas";
        const res = await api.get(url);
        console.log("SOLO RESPUESTA DEL BACKEND", res.data);
        console.log("TODA LA RESPUESTA DEL BACKEND", res);

        const data = res.data.data;

        series.value = data.map((item) => item.aportes);
        options.value = {
          chart: {
            type: "donut",
            width: 700,
            height: 600,
          },
          plotOptions: {
            pie: {
              size: 500,
              donut: {
                size: "1%",
              },
            },
            dropShadow: {
              enabled: true,
              top: 0,
              left: 0,
              blur: 3,
              opacity: 0.5,
            },
          },
          colors: ["#0088cc", "#ff0000", "#00cccc"],
          labels: data.map(
            (item) =>
              item.nombre_candidatura +
              " - " +
              item.candidatos +
              " - Bs. " +
              (item.aportado - item.usado)
          ),
        };

        const chartOptions = {
          chart: options.value.chart,
          series: series.value,
          labels: options.value.labels,
          plotOptions: options.value.plotOptions,
          colors: options.value.colors,
        };

        new ApexCharts(document.querySelector(".col-md-5"), chartOptions).render();
      } catch (error) {
        console.error("Error al obtener los datos:", error);
      }
    });

    return {
      series,
      options,
    };
  },
});
</script>
