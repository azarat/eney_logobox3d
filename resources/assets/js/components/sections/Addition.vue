<template>
  <v-card>
    <AdditionHeader></AdditionHeader>
    <AdditionBody></AdditionBody>
  </v-card>
</template>

<script>
import AdditionHeader from './addition/AdditionHeader.vue';
import AdditionBody from './addition/AdditionBody.vue';

export default {
  inject: ['theme'],
  name: 'Addition',
  components: {
    AdditionHeader,
    AdditionBody,
  },
  computed: {},
  methods: {},
};
</script>

<style scoped>

</style>
