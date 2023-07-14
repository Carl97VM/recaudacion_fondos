<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn flat dense round icon="menu" aria-label="Menu" @click="toggleLeftDrawer" />

        <q-toolbar-title>
          Recaudación Elapas
        </q-toolbar-title>

      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
      <q-list>
        <q-item-label header>
          Menú
        </q-item-label>

        <EssentialLink v-for="link in essentialLinks" :key="link.title" v-bind="link" />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import EssentialLink from 'components/EssentialLink.vue'

const linksList = [
  {
    title: 'Escritorio',
    icon: 'fas fa-house',
    link: 'dashboard'
  },
  {
    title: 'Estadisticas',
    icon: 'fas fa-chart-bar',
    link: 'aporte.estadisticas'
  },
  {
    title: 'Lista de Aportante ',
    icon: 'list',
    link: 'aportante.index'
  },
    {
    title: 'Lista de Canidatos ',
    icon: 'list',
    link: 'candidato.index'
  },
  {
    title: 'Registro Aportante ',
    icon: 'person_add',
    link: 'aportante.crear'
  },
  {
    title: 'Registro Candidato ',
    icon: 'person_add',
    link: 'candidato.crear'
  },
  {
    title: 'Registro Aporte',
    icon: 'fas fa-hand-holding-dollar',
    link: 'aporte.crear'
  },
  {
    title: 'Desembolsos',
    icon: 'fas fa-hand-holding-dollar',
    link: 'desembolso.crear'
  },
  {
    title: 'Lista de Desembolsos',
    icon: 'list',
    link: 'desembolso.index'
  },

]


export default defineComponent({
  name: 'MainLayout',

  components: {
    EssentialLink
  },

  setup() {
    const leftDrawerOpen = ref(false)

    return {
      essentialLinks: linksList,
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  }
})
</script>
