<template>
  <v-card class="mt-4">
    <!-- <AdditionHeader></AdditionHeader> -->
    <AdditionBody></AdditionBody>
  </v-card>
</template>

<script>
// import AdditionHeader from './addition/AdditionHeader.vue';
import AdditionBody from './addition/AdditionBody.vue';

export default {
  inject: ['theme'],
  name: 'Addition',
  components: {
    // AdditionHeader,
    AdditionBody,
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
