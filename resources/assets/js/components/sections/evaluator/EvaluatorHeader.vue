<template>
  <v-container
    fluid
    dense
    :style="evaluateContainerStyle">
    <v-row dense >
      <v-col md="4" sm="12" cols="12" align="center" justify="center">
        {{$t('evaluator.title')}}
      </v-col>
      <v-col md="4" sm="12" cols="12" align="center">
        <template v-if="isEvaluatorActive">
          <v-btn @click="deactivateEvaluator">
            {{$t('btns.activated')}}<v-icon>mdi-check-bold</v-icon>
          </v-btn>
        </template>
        <template v-else>
          <v-btn @click="activateEvaluator" color="#19487f">
            {{$t('btns.activate')}}<v-icon>mdi-power-off</v-icon>
          </v-btn>
        </template>
      </v-col>
      <v-col md="4" sm="12" cols="12" align="center">
        <v-btn
          @click="save"
          :disabled="!isEvaluatorActive">
          {{$t('btns.done')}}<v-icon>mdi-power-off</v-icon>
        </v-btn>
        <v-btn :disabled="!isEvaluatorActive">
          <v-icon>mdi-close-box</v-icon>
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapGetters, mapMutations, mapActions } from 'vuex';

export default {
  name: 'EvaluatorHeader',
  computed: {
    ...mapGetters({
      isEvaluatorActive: 'main/isEvaluatorActive',
    }),
    evaluateContainerStyle() {
      const style = {
        background: (this.isEvaluatorActive) ? '#19487f' : '#606060',
        padding: '0',
        margin: '0',
      };
      return style;
    },
  },
  methods: {
    ...mapMutations({
      activateEvaluator: 'main/activateEvaluator',
      deactivateEvaluator: 'main/deactivateEvaluator',
    }),
    ...mapActions({
      httpAllPrints: 'print/httpAllPrints',
      submitFullForm: 'print/submitFullForm',
    }),
    save() {
      // this.httpAllPrints();
      this.submitFullForm();
    },
  },
};
</script>

<style scoped>

</style>
