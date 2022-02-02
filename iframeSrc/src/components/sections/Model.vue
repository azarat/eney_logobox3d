<template>
  <v-card class="mt-7">
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
.v-card {
  box-shadow: none;
  border: 1px solid #D2D6D9;
  border-radius: 4px;
}
</style>
