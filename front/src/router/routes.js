import EditarAportante from 'pages/Aportantes/Editar.vue';
import InformesAportanteView from 'pages/Aportantes/Info.vue';
import InformesCandidatosView from 'pages/Candidatos/Info.vue';
import SeguimientoDesembolsoView from 'pages/Desembolsos/Seguimiento.vue';
import RegistroDesembolsoView from 'pages/Desembolsos/Registro.vue';
import InformesCandidatosDesembolsosView from 'pages/Candidatos/Desembolsos.vue';

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', name: 'dashboard', component: () => import('pages/IndexPage.vue') },
      { path: 'aportes/estadisticas', name: "aporte.estadisticas", component: () => import('pages/Aportes/Estadisticas.vue') },
      { path: 'aportes/registrar', name: "aporte.crear", component: () => import('pages/Aportes/Crear.vue') },
      { path: 'aportes/grafico', name: "aporte.grafico", component: () => import('pages/Aportes/Grafico.vue') },
      // { path: 'aportes/estadisticas', name: "aporte.estadisticas", component: () => import('pages/Aportes/Estadisticas.vue') },

      { path: 'aportantes/index', name: "aportante.index", component: () => import('pages/Aportantes/Index.vue') },
      { path: 'aportantes/registrar', name: "aportante.crear", component: () => import('pages/Aportantes/Crear.vue') },
      { path: 'aportantes/editar/:id', name: 'editar-aportante', component: EditarAportante }, // Agrega esta línea
      {  path: 'aportantes/informes/:id', name: 'informes-aportante', component: InformesAportanteView },

      { path: 'candidatos/index', name: "candidato.index", component: () => import('pages/Candidatos/Index.vue') },
      { path: 'candidatos/registrar', name: "candidato.crear", component: () => import('pages/Candidatos/Crear.vue') },
      { path: 'candidatos/informes/:id', name: "informes-candidato", component: InformesCandidatosView },
      { path: 'candidatos/informes_desembolsos/:id', name: "informes-desembolsos", component: InformesCandidatosDesembolsosView },

      { path: 'desembolsos/registrar', name: "desembolso.crear", component: () => import('pages/Desembolsos/Crear.vue') },
      { path: 'desembolsos/index', name: "desembolso.index", component: () => import('pages/Desembolsos/Index.vue') },
      { path: 'desembolsos/seguimiento/:id', name: "seguimiento-desembolso", component: SeguimientoDesembolsoView },
      { path: 'desembolsos/registro/:id', name: "registro-desembolso", component: RegistroDesembolsoView },
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
];


export default routes;
