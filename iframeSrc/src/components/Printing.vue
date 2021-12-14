<template>
  <div>
    <Evaluator></Evaluator>
    <template v-if="vizModelId && vizModelId !== ''">
      <Model></Model>
    </template>
    <Addition></Addition>
    <v-overlay :value="overlay">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
    <v-dialog
      v-model="dialog"
      max-width="500px"
    >
      <v-card>
        <v-card-title>
          <span>{{ $t('changes_saved') }}</span>
          <v-spacer></v-spacer>
          <v-menu bottom left>
            <template v-slot:activator="{ on }">
              <v-btn icon @click="dialog = false">
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </template>
          </v-menu>
        </v-card-title>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapActions, mapMutations, mapGetters } from 'vuex';
import Evaluator from './sections/Evaluator.vue';
import Model from './sections/Model.vue';
import Addition from './sections/Addition.vue';

export default {
  name: 'Printing',
  // props: ['lang', 'sessionId', 'productModel', 'siteId', 'vizModelId'],
  components: {
    Evaluator,
    Model,
    Addition,
  },
  computed: {
    ...mapGetters({
      vizModelId: 'main/vizModelId',
      mainOverlay: 'main/overlay',
      mainDialog: 'main/dialog',
    }),
    overlay: {
      get() {
        return this.mainOverlay;
      },
      set(value) {
        this.setter({
          prop: 'overlay',
          value,
        });
      },
    },
    dialog: {
      get() {
        return this.mainDialog;
      },
      set(value) {
        this.setter({
          prop: 'dialog',
          value,
        });
      },
    },
  },
  async created() {
    // eslint-disable-next-line no-console
    // console.log('PRINTING IS WORKING');
    await this.setLang(window.printFrame.lang);
    await this.setSessionId(window.printFrame.sessionId);
    await this.setProductModel(window.printFrame.productModel);
    await this.setSiteId(window.printFrame.siteId);
    await this.setVizModelId(window.printFrame.vizModelId);
    await this.setDiscount(window.printFrame.discount || '0');
    const printDataId = await this.getPrintDataId();
    this.printSetter({
      prop: 'printsDataId',
      value: printDataId,
    });

    // return 'https://giftcollection.info/api/template/preview/' + this.vizModelId + '?token=' + this.token + '&lang=' + this.lang + '&print=' + this.printsDataId;

    this.loadAreas();
    this.loadTypes();
    const prints = await this.loadPrints();
    if (prints && prints.status === 200 && prints.data && prints.data.printings) {
      prints.data.printings.forEach((item) => {
        this.destroyPrint(item.id);
      });
    }
  },
  methods: {
    ...mapActions({
      loadAreas: 'print/loadAreas',
      loadTypes: 'print/loadTypes',
      loadPrints: 'print/loadPrints',
      destroyPrint: 'print/destroyPrint',
      getPrintDataId: 'print/getPrintDataId',
    }),
    ...mapMutations({
      setter: 'main/setter',
      setLang: 'main/setLang',
      setSessionId: 'main/setSessionId',
      setProductModel: 'main/setProductModel',
      setSiteId: 'main/setSiteId',
      setVizModelId: 'main/setVizModelId',
      setDiscount: 'main/setDiscount',
      printSetter: 'print/setter',
    }),
  },
};
</script>

<style lang="scss">
  .activated {
    background: blue;
  }
  .deactivated {
    background: gray;
  }
  .border-radius-5 {
    border-radius: 5px;
  }
  .p-1px {
    padding: 1px;
  }
  .v-content {
    .theme--light.v-btn.v-btn--disabled:not(.v-btn--flat):not(.v-btn--text):not(.v-btn--outlined) {
      background-color: white !important;
      color: rgba(0, 0, 0, 0.26) !important;
    }
    .theme--light.v-btn.v-btn--disabled .v-icon,
    .theme--light.v-btn.v-btn--disabled .v-btn__loading {
      color: white!important;
    }
    .mim-w-auto {
      min-width: auto!important;
    }
    .width-auto {
      width: 150px;
    }
    .c-input {
      max-width: 150px;
      .v-input__control {
        max-height: 30px;
      }
      &.v-text-field--outlined.v-input--dense.v-text-field--outlined {
        &> .v-input__control > .v-input__slot {
          min-height: 30px;
          input {
            color: rgba(0, 0, 0, 0.6);
            text-align: center;
          }
        }
      }
    }
    .c-input-add {
      .v-input__control {
        max-height: 36px;
      }
      &.v-text-field--outlined.v-input--dense.v-text-field--outlined {
        &> .v-input__control > .v-input__slot {
          min-height: 36px;
        }
      }
    }
    .height-100ps {
      height: 100%;
    }
    .height-auto {
      height: auto;
    }
    .v-radio .accent--text {
      color: rgba(0, 0, 0, 0.6)!important;
    }
    .v-input--selection-controls {
      max-height: 30px;
      margin-top: 0;
    }
  }
  @media all and (max-width: 600px) {
    .evaluator {
      .d-flex {
        flex-direction: column;
      }
      .v-slide-group__content {
        justify-content: center;
      }
    }
    .row.row--dense {
      margin-left: 0;
      margin-right: 0;
    }
  }
  .v-input__prepend-outer {
    opacity: 0;
    width: 0;
    height: 0;
    z-index: -1;
    position: absolute;
    margin: 0;
  }
  .v-text-field.v-text-field--enclosed .v-text-field__details {
    padding: 0;
    margin: 0;
  }
</style>
