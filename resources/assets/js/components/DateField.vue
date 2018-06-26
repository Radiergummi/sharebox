<template>
  <div class="input-group">
    <flat-pickr
      v-bind:name="name"
      v-bind:config="config"
      v-bind:placeholder="placeholder"
      class="form-control"
      value=""
    >
    </flat-pickr>
    <div class="input-group-btn">
      <button class="btn btn-icon btn-light" type="button" v-bind:title="resetTitle" data-clear>
        <span class="material-icons">close</span>
      </button>
    </div>
  </div>
</template>

<script>
  // noinspection NpmUsedModulesInstalled
  import { German } from 'flatpickr/dist/l10n/de';
  import FlatPickr  from 'vue-flatpickr-component/src/component';

  export default {
    name: 'DateField',

    components: {
      FlatPickr
    },

    props: {
      name: {
        type:     String,
        required: true
      },

      initial: {
        type:     String,
        required: false
      },

      placeholder: {
        type:     String,
        required: false
      },

      resetTitle: {
        type:     String,
        required: false
      },

      min: {
        type:     String,
        required: false
      },

      max: {
        type:     String,
        required: false
      }
    },

    data () {
      return {
        baseConfig: {
          wrap:       true,
          altInput:   true,
          altFormat:  'j. F Y',
          dateFormat: 'Y-m-d',
          locale:     German
        }
      };
    },

    computed: {
      config () {
        return Object.assign( {}, this.baseConfig, {
          defaultDate: this.initial,
          minDate:     this.min,
          maxDate:     this.max
        } );
      }
    }
  };
</script>

<style scoped>

</style>
