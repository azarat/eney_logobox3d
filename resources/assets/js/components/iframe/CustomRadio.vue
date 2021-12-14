<template>
  <div class="col-sm-10">
    <label
      v-for="option in options"
      :for="(radioName + (optionName ? option['id'] : option))">

      <div
        class="block"
        v-if="option['image']"
        :title="option[optionName]"
        :class="{'active': selectedOption === option}">
        <img :src="option['image']" />
      </div>
      <div
        class="block"
        v-else
        :class="{'active': selectedOption === option}">
        {{ optionName ? option[optionName] : option }}
      </div>

      <input
        type="radio"
        class="hidden"
        v-model="selectedOption"
        @change="change"
        :id="(radioName + (optionName ? option['id'] : option))" :value="option" />
    </label>
  </div>
</template>

<script>
  import lodash from 'lodash';

  export default {
    props: ['options', 'optionName', 'selected', 'centred', 'radioName'],
    data: function () {
      return {
        selectedOption: {}
      }
    },
    computed: {
      isCentred(){
        if(this.centred !== undefined){
          return this.centred
        }else{
          return false;
        }
      }
    },
    watch: {
      selected: function () {
        this.selectedOption = this.selected;
      }
    },
    mounted () {
      this.selectedOption = this.selected;
    },
    methods: {
      change (e) {
        this.$emit('change', this.selectedOption);
      }
    }
  };
</script>

<style lang="scss" type="text/scss">
  input {
    &.hidden{
      position: absolute;
      left: -9999px;
    }
  }
  .block {
    background-color: #eee;
    display: inline-block;
    padding: 8px 8px 4px 8px;
    margin: 5px;
    border-radius: 3px;
    cursor: pointer;
    &.active {
      background-color: #013766;
      color: white;
    }
  }
</style>
