<template>
  <div>
    <div class="row justify-between q-gutter-x-lg flex flex-center" style="height: 90vh">
      <apexchart class="col-md-8" type="bar" :series="series" :options="options" />
    </div>
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

        series.value = [
          {
            name: "Dinero Recibido",
            data: data.map((item) => item.aportado),
          },
          {
            name: "Dinero Usado",
            data: data.map((item) => item.usado),
          },
        ];

        options.value = {
          chart: {
            type: "bar",
            width: 700,
            height: 600,
          },
          plotOptions: {
            bar: {
              horizontal: false,
            },
          },
          colors: ["#0088cc", "#ff0000"],
          xaxis: {
            categories: data.map((item) => item.nombre_candidatura),
          },
        };

        const chartOptions = {
          chart: options.value.chart,
          series: series.value,
          plotOptions: options.value.plotOptions,
          colors: options.value.colors,
          xaxis: options.value.xaxis,
        };

        new ApexCharts(document.querySelector(".col-md-8"), chartOptions).render();
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
