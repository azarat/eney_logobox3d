<template>
  <v-card>
    <ModelHeader></ModelHeader>
    <ModelBody></ModelBody>
  </v-card>
</template>

<script>
import ModelHeader from './model/ModelHeader.vue';
import ModelBody from './model/ModelBody.vue';

export default {
  inject: ['theme'],
  name: 'Model',
  components: {
    ModelHeader,
    ModelBody,
  },
  computed: {},
  methods: {},
};
</script>

<style scoped>

</style>
