<template>
  <q-item clickable tag="a" @click="navegacion">
    <q-item-section v-if="icon" avatar>
      <q-icon :name="icon" />
    </q-item-section>

    <q-item-section>
      <q-item-label>{{ title }}</q-item-label>
      <q-item-label caption>{{ caption }}</q-item-label>
    </q-item-section>
  </q-item>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { useRouter } from 'vue-router'

export default defineComponent({
  name: 'EssentialLink',
  props: {
    title: {
      type: String,
      required: true
    },

    caption: {
      type: String,
      default: ''
    },

    link: {
      type: String,
      default: '#'
    },

    icon: {
      type: String,
      default: ''
    },
  },

  setup(props) {
    const router = useRouter();
    const navegacion = () => {
      if (props.link.startsWith('http')) {
        window.open(props.link, '_blank');
      } else {
        router.push({ name: props.link });
      }
    };
    return { navegacion };
  }
})
</script>
