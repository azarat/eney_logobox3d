<template>
  <v-app>
    <v-content>
      <Printing/>
    </v-content>
  </v-app>
</template>

<script>
import Printing from './components/Printing.vue';

export default {
  name: 'App',
  components: {
    Printing,
  },
  data: () => ({
    //
  }),
};
</script>
