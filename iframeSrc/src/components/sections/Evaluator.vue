<template>
  <v-card class="pb-4">
    <!-- <EvaluatorHeader></EvaluatorHeader> -->
    <EvaluatorBody></EvaluatorBody>
  </v-card>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
// import EvaluatorHeader from './evaluator/EvaluatorHeader.vue';
import EvaluatorBody from './evaluator/EvaluatorBody.vue';

export default {
  inject: ['theme'],
  name: 'Evaluator',
  components: {
    // EvaluatorHeader,
    EvaluatorBody,
  },
  computed: {
    ...mapGetters({
      isEvaluatorActive: 'main/isEvaluatorActive',
    }),
  },
  methods: {
    ...mapMutations({
      activateEvaluator: 'main/activateEvaluator',
      deactivateEvaluator: 'main/deactivateEvaluator',
    }),
  },
};
</script>

<style scoped>
  .v-card {
    box-shadow: none;
  }
</style>
