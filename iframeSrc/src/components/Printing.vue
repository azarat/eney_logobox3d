<template>
  <div>

    <div class="product-data">
      <div class="product-data--image">
        <img :src="productImage" alt="" />
      </div>
      <div class="product-data--info">
        <h1>{{ productName }}</h1>
        <div class="product-articul">Артикул: {{ productSku }}</div>

        <div class="product-data--cost">
          <v-col cols="3">
            <div class="">
              <div class="text-uppercase pr-4">{{$t('quantity')}}:</div>
              <v-text-field class="d-flex c-input"
                            dense
                            background-color="accent"
                            outlined
                            disabled
                            v-model="quantity"></v-text-field>
            </div>
          </v-col>
          <v-col cols="3">
            <div class="height-100ps">
              <div class="text-uppercase pr-4">{{$t('price_without_sale')}}:</div>
              <v-text-field class="d-flex c-input"
                            dense
                            background-color="accent"
                            outlined
                            disabled
                            v-model="totalPrice"></v-text-field>
            </div>
          </v-col>
          <v-col cols="3">
            <div class="height-100ps">
              <div class="text-uppercase pr-4">{{$t('price_with_sale')}}</div>
              <v-text-field class="d-flex c-input"
                            dense
                            background-color="accent"
                            outlined
                            disabled
                            v-model="totalPriceWithDiscount"></v-text-field>
            </div>
          </v-col>
          <v-col cols="3">
            <div class="height-100ps d-flex product-data--buttons">
              <button @click="save" class="add-to-cart-btn">Додати у кошик</button>
              <button class="reset-btn" @click="goBack">Скасувати</button>
            </div>
          </v-col>
        </div>
      </div>
    </div>

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
// import { w3cwebsocket as W3CWebSocket } from 'websocket';
import WebSocketAsPromised from 'websocket-as-promised';

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
      productData: 'print/productData',
      prints: 'print/prints',
      quantityOfItems: 'print/quantityOfItems',
      printsCost: 'print/printsCost',
      printsCostDiscount: 'print/printsCostDiscount',
    }),
    totalPrice: {
      get() {
        return parseFloat(Object.values(this.printsCost)
          .reduce((total, price) => parseFloat(total) + parseFloat(price)))
          .toFixed(2);
      },
      set(value) {
        this.setter({
          prop: 'totalPrice',
          value,
        });
      },
    },
    totalPriceWithDiscount: {
      get() {
        return parseFloat(Object.values(this.printsCostDiscount)
          .reduce((total, price) => parseFloat(total) + parseFloat(price)))
          .toFixed(2);
      },
      set(value) {
        this.setter({
          prop: 'totalPriceWithDiscount',
          value,
        });
      },
    },
    quantity: {
      get() {
        return this.quantityOfItems;
      },
      set(value) {
        this.setter({
          prop: 'quantityOfItems',
          value,
        });
      },
    },
    productImage: {
      get() {
        const siteUrl = 'http://dev.eney.com.ua:8081/image/';
        return (this.productData) ? siteUrl + this.productData[0].option_image : '';
      },
      set(value) {
        this.setter({
          prop: 'productImage',
          value,
        });
      },
    },
    productName: {
      get() {
        return (this.productData) ? this.productData[0].name : '';
      },
      set(value) {
        this.setter({
          prop: 'productName',
          value,
        });
      },
    },
    productSku: {
      get() {
        return (this.productData) ? this.productData[0].model_option : '';
      },
      set(value) {
        this.setter({
          prop: 'productSku',
          value,
        });
      },
    },
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

    this.loadProductData();
    this.loadAreas();
    this.loadTypes();
    this.loadTypesTech();
    const prints = await this.loadPrints();
    if (prints && prints.status === 200 && prints.data && prints.data.printings) {
      prints.data.printings.forEach((item) => {
        this.destroyPrint(item.id);
      });
    }
  },
  methods: {
    ...mapActions({
      loadProductData: 'print/loadProductData',
      loadTypesTech: 'print/loadTypesTech',
      loadAreas: 'print/loadAreas',
      loadTypes: 'print/loadTypes',
      loadPrints: 'print/loadPrints',
      destroyPrint: 'print/destroyPrint',
      getPrintDataId: 'print/getPrintDataId',
      goBack: 'print/goBack',
      submitFullForm: 'print/submitFullForm',
    }),
    ...mapMutations({
      setter: 'main/setter',
      setLang: 'main/setLang',
      setSessionId: 'main/setSessionId',
      setProductData: 'print/setProductData',
      setProductModel: 'main/setProductModel',
      setSiteId: 'main/setSiteId',
      setVizModelId: 'main/setVizModelId',
      setDiscount: 'main/setDiscount',
      setPrintsCost: 'print/setPrintsCost',
      setPrintsCostDiscount: 'print/setPrintsCostDiscount',
      printSetter: 'print/setter',
    }),
    save() {
      this.overlay = true;
      this.submitFullForm();

      const wsp = new WebSocketAsPromised('ws://185.156.41.62:8086');

      wsp.open()
        .then(() => {
          const wsAddToCart = {
            quantity: this.quantity,
            session_id: window.printFrame.sessionId,
          };
          wsp.send(JSON.stringify(wsAddToCart));
        })
        .then(() => wsp.close())
        .catch((e) => {
          console.error('ws:logobox3d', e);
        });
    },
  },
};

</script>

<style lang="scss">
  .v-dialog__content {
    align-items: flex-start !important;
  }
  .add-to-cart-btn {
    background: #006EC7;
    border-radius: 4px;
    height: 46px;
    font-size: 16px;
    color: #ffffff;
    padding: 0px 10px;
    white-space: nowrap;
    font-weight: 500;
  }
  .reset-btn {
    background: transparent;
    border-radius: 4px;
    height: 46px;
    font-size: 16px;
    color: #7E7E7E;
    padding: 0px 10px;
    font-weight: 500;
  }
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

      .v-input__slot {
        margin: 0px;
      }

      .v-input__control {
        max-height: 46px;

        input {
          font-weight: 700;
          max-height: 46px;
          height: 46px;
        }
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
  .product-data {
    display: flex;
    margin-bottom: 20px;
    width: 100%;

    .product-data--cost {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;

      & > .col {
        padding: 10px 15px 0px 0px;
        width: auto;
        flex: none;
        max-width: none;
      }
    }

    .product-data--info {
      width: calc(100% - 200px);

      h1 {
        font-size: 19px;
      }

      .product-articul {
        color: #006ec7;
        font-size: 17px;
        font-weight: 600;
      }
    }

    .product-data--image {
      background: #FFFFFF;
      padding: 0px;
      position: relative;
      background-color: #f2f2f2;
      border: 1px solid #cbcbcb;
      width: 200px;
      margin-right: 20px;

      img {
        max-width: 100%;
        display: block;
      }
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
