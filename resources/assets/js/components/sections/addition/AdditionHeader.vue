<template>
  <v-container
    fluid
    dense
    :style="evaluateContainerStyle">
    <v-row dense >
      <v-col md="4" sm="12" cols="12" align="center" justify="center">
        {{$t('addition.title')}}
      </v-col>
      <v-col md="4" sm="12" cols="12" align="center">
        <template v-if="isAdditionActive">
          <v-btn @click="deactivateAddition">
            {{$t('btns.activated')}}<v-icon>mdi-check-bold</v-icon>
          </v-btn>
        </template>
        <template v-else>
          <v-btn @click="activateAddition" color="#19487f">
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
  name: 'AdditionHeader',
  computed: {
    ...mapGetters({
      isEvaluatorActive: 'main/isEvaluatorActive',
      isAdditionActive: 'main/isAdditionActive',
    }),
    evaluateContainerStyle() {
      const style = {
        background: (this.isAdditionActive) ? '#19487f' : '#606060',
        padding: '0',
        margin: '0',
      };
      return style;
    },
  },
  methods: {
    ...mapMutations({
      activateAddition: 'main/activateAddition',
      deactivateAddition: 'main/deactivateAddition',
    }),
  },
};
</script>

<style scoped>

</style>
