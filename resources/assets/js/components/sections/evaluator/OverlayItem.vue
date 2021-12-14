<template>
  <div>
    <div class="header">
      <v-row dense align="center" justify="center">
        <v-col cols="12" md="4">
          <v-row dense align="center" justify="center">
            <v-col cols="2" md="1">
                {{index+1}}
            </v-col>
            <v-col cols="10" md="10">
              <v-text-field
                label="Тираж"
                outlined
                dense
                v-model="quantity"></v-text-field>
            </v-col>
          </v-row>


        </v-col>
        <v-col cols="12" md="4">
          <v-text-field
            label="Ціна"
            outlined
            disabled
            dense
            v-model="totalPrice"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field
            label="Ціна зі знижкою"
            outlined
            disabled
            dense
            v-model="totalPrice"></v-text-field>
        </v-col>
      </v-row>
    </div>
    <div class="content">
      <v-row justify="center" align="center">
        <v-col md="4" justify="center" align="center">
          Технология
          <v-chip-group
            v-model="selectedTypeIndex"
            column
            mandatory
          >
            <v-chip
              v-for="(type, index) in types"
              :key="index"
            >
              {{type.name}}
            </v-chip>
          </v-chip-group>
        </v-col>
      </v-row>
      <v-row justify="center" align="center">
        <v-col md="4" justify="center" align="center">
          Площадь
          <v-chip-group
            v-model="selectedAreaIndex"
            column
            mandatory
          >
            <v-chip
              v-for="(area, index) in filteredAreas"
              :key="index"
              tile
            >
              {{area.name}}
            </v-chip>
          </v-chip-group>
        </v-col>
      </v-row>
      <v-row justify="center" align="center">
        <v-col md="4" justify="center" align="center">
          Цветов
          <v-chip-group
            v-model="selectedColorsIndex"
            column
            mandatory
          >
            <v-chip
              v-for="(color, index) in filteredColors"
              :key="index"
              tile
            >
              {{color}}
            </v-chip>
          </v-chip-group>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';

export default {
  name: 'OverlayItem',
  props: ['index'],
  data() {
    return {
      quantity: 100,
      selectedTypeIndex: 0,
      selectedAreaIndex: 0,
      selectedColorsIndex: 0,
    };
  },
  computed: {
    ...mapGetters({
      areas: 'print/areas',
      types: 'print/types',
    }),
    filteredAreas() {
      const codeOfSelectedType = this.types[this.selectedTypeIndex].code;
      const result = this.areas.filter(item => item.code.indexOf(codeOfSelectedType) !== -1);
      if (result.length <= this.selectedAreaIndex) {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.selectedAreaIndex = 0;
      }
      return result;
    },
    filteredColors() {
      const colors = [];
      const maxColors = this.filteredAreas[this.selectedAreaIndex].max_colors;
      for (let i = 0; i < maxColors; i += 1) {
        colors.push((i + 1));
      }
      if (colors.length < this.selectedColorsIndex) {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.selectedColorsIndex = 0;
      }

      return colors;
    },
    totalPrice() {
      const selectedArea = this.filteredAreas[this.selectedAreaIndex];
      const preparePrice = parseFloat(selectedArea.prepare_price);
      const kx = parseFloat(selectedArea.kx);
      // eslint-disable-next-line radix
      const n = parseInt(this.selectedColorsIndex + 1);
      // eslint-disable-next-line radix
      const tirag = parseInt(this.quantity);
      const printPrice = parseFloat(selectedArea.print_price);
      const kz = parseFloat(selectedArea.kz);
      const stickingPrice = parseFloat(selectedArea.sticking_price);
      const roasingPrice = parseFloat(selectedArea.roasting_price);

      const onePrinting = (preparePrice * (1 + kx * (n - 1)) / tirag
        + printPrice * (1 + kz * (n - 1)) + stickingPrice + roasingPrice).toFixed(2);
      return parseFloat(onePrinting * tirag).toFixed(2);
    },
  },
  methods: {
    ...mapActions({

    }),
    ...mapMutations({
      setTypeOfPrint: 'print/setTypeOfPrint',
      setAreaOfPrint: 'print/setAreaOfPrint',
      setColorsOfPrint: 'print/setColorsOfPrint',
    }),
  },
  watch: {
    selectedTypeIndex(indexType, oldIndexType) {
      if (indexType !== oldIndexType) {
        this.setTypeOfPrint({
          index: this.index,
          value: this.types[indexType],
        });
      }
    },
    selectedAreaIndex(indexArea) {
      this.setAreaOfPrint({
        index: this.index,
        value: this.areas[indexArea],
      });
    },
    selectedColorsIndex(indexArea) {
      this.setColorsOfPrint({
        index: this.index,
        value: indexArea + 1,
      });
    },
  },
};
</script>

<style scoped>
.header {
  background: #eee;
}
.v-slide-item--active {
  background: rgb(25, 72, 127);
  color: #fff !important;
}
</style>
