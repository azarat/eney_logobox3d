<template>
  <div class="print-form" :style="{ backgroundColor: colors.bg_color || '#fff' }">
    <div :class="{'activated': isEvaluationActivated, 'not-activated': !isEvaluationActivated}">
      <div class="overlay"></div>
      <div class="top-panel">
        <div class="title col-1-3">
          {{ $lang.iframe.title }}
        </div>
        <div class="text-center col-1-3 overlayed-btn">
          <button v-if="!isEvaluationActivated"
            @click="isEvaluationActivated = true"
            class="btn btn-success btn-top">
            {{ $lang.iframe.activate }}
          </button>
          <button v-if="isEvaluationActivated"
                  @click="
                    clearPrints(),
                    isEvaluationActivated = false,
                    isAdditionalFilesActivated = false,
                    isMaketActivated = false"
                  class="btn btn-success btn-top">
            {{ $lang.iframe.activated }}
          </button>
        </div>
        <div class="buttons col-1-3">
          <button
            @click="saveState"
            class="btn btn-success">
            {{ $lang.iframe.done }}
          </button>
          <button
            @click="clearPrints"
            class="btn btn-light">
            {{ $lang.iframe.clearAll }}
          </button>
        </div>
      </div>
      <div class="top-panel-body">
        <div class="print-wrapper"
             v-for="(print, index) in prints">
          <div class="panel"
               :style="{color: colors.panel_text_color}">
            <div class="panel-title">
              <div class="flex-column">
                <div class="left">
                  {{ index + 1 }}
                  Тираж <input type="number" v-model="quantity">
                </div>
                <div class="center">
                  Ціна: <span>{{calcPrintPrice(print)}}</span>
                </div>
                <div class="right">
                  Ціна зі знижкою: <span>{{calcPrintPrice(print)}}</span>
                </div>
              </div>
            </div>
            <span class="panel-action close" @click="setPrintClose(print.id)">
              <svg-icon v-if="!print.isClose" icon="arrow-up"></svg-icon>
              <svg-icon v-else class="rotated" icon="arrow-down"></svg-icon>
            </span>
            <span
              :title="$lang.iframe.remove"
              class="panel-action remove"
              @click="removePrint(print.id)">
          <svg-icon icon="trush"></svg-icon>
        </span>
          </div>
          <div class="panel-body" v-show="!print.isClose">
            <div class="row">
              <div class="print-controll left application-types">
                <label :style="{ color: colors.label_text_color }">{{ $lang.iframe.applicationType }}</label>
                <custom-radio
                  option-name="name"
                  :radio-name="'first-' + print.id"
                  :selected="print.selectedApplicationType"
                  :options="print.applicationTypes"
                  @change="updateSelectedApplicationTypeForPrint($event, print.id)">
                </custom-radio>
              </div>
            </div>
            <div class="row">
              <div class="print-controll left areas">
                <label :style="{ color: colors.label_text_color }" for="">{{ $lang.iframe.area }}</label>
                <custom-radio
                  option-name="name"
                  :radio-name="'second-' + print.id"
                  :selected="print.selectedArea"
                  :options="print.areas"
                  @change="updateSelectedAreaForPrint($event, print.id)">
                </custom-radio>
              </div>
            </div>
            <div class="row">
              <div class="print-controll right colors" v-if="print.colors.length > 1">
                <label :style="{ color: colors.label_text_color }" for="">{{ $lang.iframe.colorCount }}</label>
                <custom-radio
                  :radio-name="'third-' + print.id"
                  :selected="print.selectedColor"
                  :options="print.colors"
                  @change="updateSelectedColorForPrint($event, print.id)">
                </custom-radio>
              </div>
            </div>
            <div class="row">
              <div class="print-controll right copys" v-if="print.copies.length > 1">
                <label :style="{ color: colors.label_text_color }">{{ $lang.iframe.copyCount }}</label>
                <custom-radio
                  :radio-name="'fourth-' + print.id"
                  :selected="print.selectedCopy"
                  :options="print.copies"
                  @change="updateSelectedCopyForPrint($event, print.id)">
                </custom-radio>
              </div>
            </div>
          </div>
        </div>
        <div>
          <div class="actions">
          <span @click="addPrint()"
                :style="{ color: colors.add_button_color }">
            <div class="block active">+</div> {{ $lang.iframe.addPrint }}
          </span>
          </div>
        </div>
      </div>
    </div>
    <div :class="{'activated': isMaketActivated, 'not-activated': !isMaketActivated}">
      <div class="overlay"></div>
      <div class="top-panel">
        <div class="title col-1-3">
          {{ $lang.iframe.maket }}
        </div>
        <div class="text-center col-1-3 overlayed-btn">
          <button v-if="!isMaketActivated"
                  @click="isMaketActivated = isEvaluationActivated"
                  class="btn btn-success btn-top">
            {{ $lang.iframe.activate }}
          </button>
          <button v-if="isMaketActivated"
                  @click="isMaketActivated = false"
                  class="btn btn-success btn-top">
            {{ $lang.iframe.activated }}
          </button>
        </div>
        <div class="buttons col-1-3">
        </div>
      </div>
      <div v-if="vizModelId > 0 && prints.length > 0">
        <iframe :src="vizModelHref"
                width="100%"
                height="560"
                frameborder="0"></iframe>
      </div>
    </div>
    <div :class="{'activated': isAdditionalFilesActivated, 'not-activated': !isAdditionalFilesActivated}">
      <div class="overlay"></div>
      <div class="top-panel">
        <div class="title col-1-3">
          {{ $lang.iframe.additionInfo}}
        </div>
        <div class="text-center col-1-3 overlayed-btn">
          <button v-if="!isAdditionalFilesActivated"
                  @click="isAdditionalFilesActivated = isEvaluationActivated"
                  class="btn btn-success btn-top">
            {{ $lang.iframe.activate }}
          </button>
          <button v-if="isAdditionalFilesActivated"
                  @click="isAdditionalFilesActivated = false"
                  class="btn btn-success btn-top">
            {{ $lang.iframe.activated }}
          </button>
        </div>
        <div class="buttons col-1-3">
        </div>
      </div>
      <div class="top-panel-body">
        <div class="row panel file-links" v-if="prints.length > 0">
          <div class="col-md-6 print-controll file-links-left">
            <label :style="{ color: colors.label_text_color }" for="">{{ $lang.iframe.attachImage }}</label>
            <div class="print-controll__radio-group">
              <div class="print-controll__radio">
                <label :style="{ color: colors.label_text_color }">
                  <input type="radio"
                         v-model="isFileLink"
                         @change="setIsFileLink(false)"
                         value="false">
                  {{ $lang.iframe.uploadFile }}
                </label>
              </div>
              <div class="print-controll__radio">
                <label :style="{ color: colors.label_text_color }">
                  <input type="radio"
                         v-model="isFileLink"
                         @change="setIsFileLink(true)"
                         value="true">
                  {{ $lang.iframe.linkToFile}}
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-6 print-controll file-links-right">
            <div class="attachment-input-wrapper">
              <div>
                <input class="attachment-input-white" type="file"
                       @change="uploadFile($event)"
                       :disabled="isFileLink">
                <a v-if="fileUrl != null && fileUrl != ''" :href="fileUrl" class="link-to-uploaded-file" target="_blank">{{ $lang.iframe.uploadedFile }}</a>
              </div>
              <div>
                <input type="text"
                       :value="remoteFileUrl"
                       @input="updateRemoteFileUrl($event)"
                       :disabled="!isFileLink">
              </div>
            </div>
          </div>
        </div>
        <div class="comment" v-if="prints.length > 0">
          <div class="comment-title">{{ $lang.iframe.addComment }}</div>
          <textarea class="comment-form"
                    @input="setComment($event.target.value)">{{ comment }}</textarea>
        </div>
      </div>

    </div>

    <div class="form-action">
    </div>
      <div class="info" v-if="displayInfo">
          {{ $lang.iframe.updated }}
      </div>
  </div>
</template>

<script>
import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
import HTTP from './http.js';
import helpers from './helpers.js';

export default {
  props: ['lang', 'sessionId', 'productModel', 'siteId', 'vizModelId'],
  data: function () {
    return {
      template: '',
      token: 'qweqwe',
      productId: 1,
      displayInfo: false,
      isButtonClicked: false,

      isEvaluationActivated: false,
      isMaketActivated: false,
      isAdditionalFilesActivated: false,
      quantity: 100,
    }
  },
  created () {
    this.$lang.setLang(this.lang);
    // HTTP().get(`${this.lang}/template/${this.token}/${this.productId}`)
    //  .then((data) => {
    //    this.template = data.data.template;
    //
    //    //let iframe = document.createElement('iframe');
    //    //iframe.style.cssText = "width: 100%; min-height: 800px;";
    //    //iframe.src = 'data:text/html;charset=utf-8,' + encodeURI(this.template);
    //    //document.body.appendChild(iframe);
    //  });
  },
  async mounted () {
    this.setLang(this.lang);
    this.setSessionId(this.sessionId);
    this.setProductModel(this.productModel);
    this.setSiteId(this.siteId);
    await this.fetchColors();
    await this.fetchApplicationTypes({
      lang: this.lang,
      productModel: this.productModel}
    );
    await this.fetchAreas({
      lang: this.lang,
      productModel: this.productModel}
    );
    await this.fetchDuringPrints();
    await this.fetchDuringPrintsData();
  },
  watch: {
    prints: function (newVal, oldVal) {
        // console.log(newVal, oldVal);
    },
  },
  computed: {
    ...mapState('area',[
      'areas',
    ]),
    ...mapState('applicationTypes', [
      'applicationTypes',
    ]),
    ...mapState('prints', [
      'prints'
    ]),
    ...mapState('printsData', [
      'printsDataId',
      'isFileLink',
      'comment',
      'fileUrl',
      'remoteFileUrl',
    ]),
    ...mapState('settings', [
      'colors',
    ]),
    ...mapActions('prints', [
      'areasFilteredByApplicationType'
    ]),
    ...mapGetters('settings', [
      'getMainButtonStyle'
    ]),
    saveButtonText: function () {
      if (this.isButtonClicked) {
        return this.$lang.iframe.printingsSaved;
      } else {
        return this.$lang.iframe.save;
      }
    },
    vizModelHref: function () {
      if (this.printsDataId != undefined) {
        return 'https://giftcollection.info/api/template/preview/' + this.vizModelId + '?token=' + this.token + '&lang=' + this.lang + '&print=' + this.printsDataId;
      } else {
        return '';
      }
    }
  },
  methods: {
    ...mapActions('area', [
      'fetchAreas',
    ]),
    ...mapActions('applicationTypes', [
      'fetchApplicationTypes',
    ]),
    ...mapActions('prints', [
      'httpAllPrints',
      'addPrint',
      'removePrint',
      'clearPrints',
      'pushPrints',
      'fetchDuringPrints',
    ]),
      ...mapActions('printsData', [
          'fetchDuringPrintsData',
          'pushPrintsData',
      ]),
    ...mapActions('settings', [
      'fetchColors'
      ]),
    ...mapMutations('settings', [
      'setLang',
      'setSessionId',
      'setProductModel',
      'setSiteId',
      'setMainButtonHovered',
      'setMainButtonNonHovered',
      'setMainButtonActive',
    ]),
    ...mapMutations('prints', [
      'setSelectedApplicationTypeForPrint',
      'setSelectedAreaForPrint',
      'setSelectedColorForPrint',
      'setSelectedCopyForPrint',
      'setAreasForPrint',
      'setColorsForPrint',
      'setCopiesForPrint',
      'setPrintClose',
    ]),
      ...mapMutations('printsData', [
          'setComment',
          'setIsFileLink',
          'setRemoteFileUrl',
          'setFileUrl',
      ]),
    calcPrintPrice(print) {
        // debugger;
      let preparePrice = parseFloat(print.selectedArea.prepare_price);
      let kx = parseFloat(print.selectedArea.kx);
      let n = parseInt(print.selectedColor);
      let tirag = parseInt(this.quantity);
      let printPrice = parseFloat(print.selectedArea.print_price);
      let kz = parseFloat(print.selectedArea.kz);
      let stickingPrice = parseFloat(print.selectedArea.sticking_price);
      let roasingPrice = parseFloat(print.selectedArea.roasting_price);

      let onePrinting = (preparePrice*(1+kx*(n-1))/tirag + printPrice*(1+kz*(n-1)) + stickingPrice + roasingPrice).toFixed(2);
      return parseFloat(onePrinting*tirag).toFixed(2);
    },
    updateSelectedApplicationTypeForPrint(selected, printId) {
      this.setSelectedApplicationTypeForPrint({
        printId: printId,
        applicationType: selected
      });
      this.updateAreasForPrint(this.areas, printId);
    },
    updateAreasForPrint(areas, printId) {
      let filteredAreas = helpers.filterAreasByApplicationType (
        this.areas,
        helpers.getPrintSelectedApplicationType(this.prints, printId)
      );
      this.setAreasForPrint({
        printId: printId,
        areas: filteredAreas,
      });
      this.updateSelectedAreaForPrint(_.last(filteredAreas), printId);
    },
    updateSelectedAreaForPrint(selected, printId) {
      this.setSelectedAreaForPrint({
        printId: printId,
        area: selected
      });
      this.updateColorsForPrint(printId);
      this.updateCopyForPrint(printId);
    },
    updateColorsForPrint(printId) {
      let colors = helpers.getPrintColors(this.prints, printId);
      this.setColorsForPrint({
        printId: printId,
        colors: colors,
      });
      let selectedColor = helpers.getPrintSelectedColor(
        this.prints,
        printId
      );
      if (_.includes(colors, selectedColor)) {
        this.updateSelectedColorForPrint(selectedColor, printId);
      } else {
        this.updateSelectedColorForPrint(_.head(colors), printId);
      }
    },
    updateSelectedColorForPrint(selected, printId) {
      this.setSelectedColorForPrint({
        printId: printId,
        color: selected
      });
    },
    updateCopyForPrint(printId) {
      let copies = helpers.getPrintCopies(this.prints, printId);
      this.setCopiesForPrint({
        printId: printId,
        copies: copies,
      });
      let selectedCopy = helpers.getPrintSelectedCopy(
        this.prints,
        printId
      );
      if (_.includes(copies, selectedCopy)) {
        this.updateSelectedCopyForPrint(selectedCopy, printId);
      } else {
        this.updateSelectedCopyForPrint(_.head(copies), printId);
      }
    },
    updateSelectedCopyForPrint(selected, printId) {
      this.setSelectedCopyForPrint({
        printId: printId,
        copy: selected
      });
    },
    udpateCommentForPrint(event, printId) {
      this.setCommentForPrint({
        printId: printId,
        comment: event.target.value
      });
    },
    displayStatus () {
        this.displayInfo = true;
        setTimeout(() => {
            this.displayInfo = false;
        }, 1500);
    },
    saveState() {
      this.httpAllPrints();
      this.pushPrints({
        productModel: this.productModel,
        sessionId: this.sessionId,
      });
      this.pushPrintsData();
      this.displayStatus();
    },
    submitForm() {
      this.setMainButtonActive();
      this.isButtonClicked = true;
      this.pushPrints({
        productModel: this.productModel,
        sessionId: this.sessionId,
      });
      this.pushPrintsData();
    },
    updateRemoteFileUrl(event, printId) {
      this.setRemoteFileUrl(event.target.value)
    },
    async uploadFile(event, printId) {
      let formData = new FormData();
      formData.append('file', event.target.files[0]);
      await HTTP().post( '/upload-file', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((data) => {
        this.setFileUrl(data.data);
        this.pushPrintsData();
      }) .catch(function () {
        console.log('FAILURE!!');
      });
    },
  },
};
</script>

<style lang="scss" type="text/scss">
  body, #app, .print-form {
    padding: 0;
    margin: 0;
  }
  #app .print-form {
    padding: 0;
  }
  .col-1-3 {
    width: 33%;
  }
  .text-center {
    text-align: center;
  }
  button.btn-top {
    margin: 5px auto;
  }
  .flex-column {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
  }
  .activated {
    .top-panel {
      background: #19487B;
      color: #fff;
    }
  }
  .not-activated {
    position: relative;
    button.btn-top {
      z-index: 20;
      position: relative;
    }

    .top-panel {
      background: #606060;
      color: #fff;
    }

    .overlay {
      z-index: 10;
      background: rgba(0,0,0,0.7);
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
    }
  }

  .print-wrapper {
    margin-bottom: 0px;
  }
  .print-form {
    padding: 10px;
  }
  .title {
    font-family: "Helvetica Neue";
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
  }
  .panel {
    background-color: rgba(219, 240, 238, .9);
    border: 1px solid #ebebeb;
    padding: 11px 12px;
    &-title {
      width: 90%;
      display: inline-block;
      font-family: "Helvetica Neue";
      font-size: 14px;
      font-weight: 300;
      line-height: 14.23px;
    }
    &-body {
      padding: 15px 0 0;
    }
    &-action.remove {
      float: right;
      margin: 0 8px;
      cursor: pointer;
    }
    &-action.close {
      float: right;
      margin: 0 8px;
      cursor: pointer;
    }
  }
  .actions {
    font-family: "Helvetica Neue";
    font-size: 14px;
    font-weight: 700;
    text-decoration: underline;
    cursor: pointer;
    text-align: center;
    padding: 5px 0;
  }
  .row {
    display: flex;
    flex-direction: row;
    padding: 15px 0 4px;
    .print-controll {
       &.file-links-left {
         flex-basis: 52%;
         select {
           min-width: 242px;
         }
       }
       &.file-links-right {
         flex-basis: 48%;
         dislay: flex;
         justify-content: flex-end;
         label {
           width: 38%;
         }
         select {
           min-width: 68px;
         }
       }
      display: flex;
      & > label {
        display: flex;
        align-items: center;
      }
      label {
        // width: 50%;
        font-family: "Helvetica Neue";
        font-size: 14px;
        font-weight: 700;
        line-height: 14.23px;
      }
      &__radio-wrapper {
        display: block;
      }
      &__radio {
        padding: 8px 0 8px;
        label {
          font-weight: 300;
        }
      }
    }
  }
  .form-action {
    margin-top: 30px;
    margin-bottom: 30px;
    text-align: center;
    button {
      font-family: "Helvetica Neue";
      font-size: 14px;
      font-weight: 700;
      line-height: 31.63px;
      padding: 7px 65px;
      border: #fb6401;
      cursor: pointer;
    }
  }
  .rotated {
    transform: rotate(180deg);
  }
  .comment {
    &-title {
      font-family: "Helvetica Neue";
      font-size: 14px;
      font-weight: 700;
      line-height: 14.23px;
      margin-bottom: 25px;
      margin-top: 40px;
    }
    &-form {
      width: 100%;
      border: none;
      border: 1px solid #ebebeb;
      min-height: 50px;
    }
  }
  .info {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #2c962c;
    color: white;
    padding: 15px 5px;
    text-align: center;
    text-transform: uppercase;
    font-family: "Helvetica Neue";
    font-weight: bold;
  }
  .fade-enter-active {
    transition: all .3s ease;
  }
  .fade-leave-active {
    transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
  }
  .fade-enter, .fade-leave-to
    /* .slide-fade-leave-active below version 2.1.8 */ {
    transform: translateY(100px);
    opacity: 0;
  }
  .attachment {
    &-input {
      &-wrapper {
        width: 100%;
        input {
          width: 95%;
          border: 1px solid #ebebeb;
          padding: 6px;
          transform: translateY(-9px);
        }
        & > div {
          padding: 0 0 15px;
          &:last-child {
            padding: 0;
          }
        }
      }
      &-white {
        background-color: white;
      }
    }
  }
  .link-to-uploaded-file {
    font-family: "Helvetica Neue";
    font-size: 14px;
  }
  .addition-info-title {
    font-family: "Helvetica Neue";
    font-size: 16px;
    padding: 10px 0 15px;
  }
  .file-links {
    padding: 10px 10px;
    input {
      transform: translateY(0);
    }
    & .print-controll {
      & > label {
        padding-top: 12px;
        align-items: flex-start;
      }
      .print-controll__radio-group {
        margin-left: 10%;
      }
    }
  }
  .top-panel {
    display: flex;
    justify-content: space-between;
    /*border-bottom: 1px solid #ebebeb;*/
    margin-bottom: 5px;
    .title {
      align-self: center;
      padding-top: 5px;
    }
    .buttons {
      padding: 5px 0;
      text-decoratin: none;
    }
  }
  .btn {
    display: inline-block;
    padding: 2px 10px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    touch-action: manipulation;
    cursor: pointer;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 2px;
    text-transform: uppercase;
    font-weight: 700;
    &.btn-sm {
      padding: 5px 10px;
      font-size: 12px;
      line-height: 1.5;
      border-radius: 3px;
    }
    &.btn-light {
      background-color: grey;
      border-color: grey;
      color: white;
    }
    &.btn-success {
      background-color: #2c962c;
      border-color: #2c962c;
      color: white;
    }
  }
  hr {
    border-color: #013766;
  }
</style>
