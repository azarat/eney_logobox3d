<template>
  <v-container
    fluid
    dense
    :style="evaluateContainerStyle">
    <v-row dense >
      <v-col md="4" sm="12" cols="12" align="center" justify="center">
        {{$t('model.title')}}
      </v-col>
      <v-col md="4" sm="12" cols="12" align="center">
        <template v-if="isModelActive">
          <v-btn @click="deactivateModel">
            {{$t('btns.activated')}}<v-icon>mdi-check-bold</v-icon>
          </v-btn>
        </template>
        <template v-else>
          <v-btn @click="activateModel" color="#19487f">
            {{$t('btns.activate')}}<v-icon>mdi-power-off</v-icon>
          </v-btn>
        </template>
      </v-col>
      <v-col md="4" sm="12" cols="12" align="center"></v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';

export default {
  name: 'EvaluatorHeader',
  computed: {
    ...mapGetters({
      isEvaluatorActive: 'main/isEvaluatorActive',
      isModelActive: 'main/isModelActive',
    }),
    evaluateContainerStyle() {
      const style = {
        background: (this.isModelActive) ? '#19487f' : '#606060',
        padding: '0',
        margin: '0',
      };
      return style;
    },
  },
  methods: {
    ...mapMutations({
      activateModel: 'main/activateModel',
      deactivateModel: 'main/deactivateModel',
    }),
  },
};
</script>

<style scoped>

</style>
