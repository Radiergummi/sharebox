<template>
  <div class="input-group color-picker" ref="colorpicker">
    <input
      v-bind:name="name"
      v-bind:required="required"
      v-bind:placeholder="placeholder"
      v-model="colorValue"
      v-on:focus="showPicker"
      v-on:input="updateFromInput"
      type="text"
      class="form-control color-field"
    />
    <span class="input-group-addon color-picker-container">
      <span
        class="current-color"
        v-bind:style="`background-color: ${colorValue}`"
        v-on:click="togglePicker"
      ></span>
      <chrome-picker
        v-bind:value="colors"
        v-on:input="updateFromPicker"
        v-if="displayPicker"
      ></chrome-picker>
	</span>
  </div>
</template>

<script>
  import Chrome from 'vue-color/src/components/Chrome';

  export default {
    name: 'ColorField',

    components: {
      'chrome-picker': Chrome
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

      required: {
        type:    Boolean,
        default: false
      },

      placeholder: {
        type:    String,
        default: '#123456'
      }
    },

    data () {
      return {
        colors: {
          hex: '#000000'
        },

        colorValue:    '',
        displayPicker: false
      };
    },

    watch: {
      colorValue ( value ) {
        if ( value ) {
          this.updateColors( value );
          this.$emit( 'input', value );
        }
      }
    },

    mounted () {
      this.setColor( this.initial || '#000000' );
    },

    methods: {
      setColor ( color ) {
        this.updateColors( color );
        this.colorValue = color;
      },

      updateColors ( color ) {
        if ( color.slice( 0, 1 ) === '#' ) {
          this.colors = {
            hex: color
          };
        } else if ( color.slice( 0, 4 ) === 'rgba' ) {
          const rgba = color.replace( /^rgba?\(|\s+|\)$/g, '' ).split( ',' );
          const hex  = '#' + ( ( 1 << 24 ) + ( parseInt( rgba[ 0 ] ) << 16 ) + ( parseInt( rgba[ 1 ] ) << 8 ) + parseInt(
            rgba[ 2 ] ) ).toString( 16 ).slice( 1 );

          this.colors = {
            hex: hex,
            a:   rgba[ 3 ]
          };
        }
      },

      showPicker () {
        document.addEventListener( 'click', this.documentClick );
        this.displayPicker = true;
      },

      hidePicker () {
        document.removeEventListener( 'click', this.documentClick );
        this.displayPicker = false;
      },

      togglePicker () {
        this.displayPicker
        ? this.hidePicker()
        : this.showPicker();
      },

      updateFromInput () {
        this.updateColors( this.colorValue );
      },

      updateFromPicker ( color ) {
        this.colors = color;

        if ( color.rgba.a === 1 ) {
          this.colorValue = color.hex;
        } else {
          this.colorValue = `rgba(${color.rgba.r}, ${color.rgba.g}, ${color.rgba.b}, ${color.rgba.a})`;
        }
      },

      documentClick ( event ) {
        const pickerElement = this.$refs.colorpicker;

        if ( pickerElement !== event.target && !pickerElement.contains( event.target ) ) {
          this.hidePicker();
        }
      }
    }
  };
</script>

<style scoped>
  .vc-chrome {
    position:         absolute;
    bottom:           3.5rem;
    left:             3rem;
    z-index:          9;
    border-radius:    4px;
    background-color: var(--gray-dark);
  }

  .vc-chrome >>> .vc-chrome-saturation-wrap {
    border-radius: 4px 4px 0 0;
  }

  .vc-chrome >>> .vc-chrome-body {
    border-radius:    0 0 4px 4px;
    background-color: inherit;
  }

  .vc-chrome >>> .vc-input__input {
    border:           1px solid transparent;
    border-radius:    3px;
    background-color: rgba(255, 255, 255, 0.1);
    color:            var(--white);
    box-shadow:       none;
  }

  .vc-chrome >>> .vc-chrome-toggle-icon-highlight {
    background: rgba(255, 255, 255, 0.1);
  }

  .vc-chrome >>> .vc-chrome-toggle-icon path {
    fill: #969696 !important;
  }

  .vc-chrome >>> .vc-alpha-checkboard-wrap {
    border-radius: 2px;
  }

  .color-field {
    height:       2.5rem;
    border-right: 0;
  }

  .current-color {
    display:          inline-block;
    width:            2.5rem;
    height:           calc(2.5rem - 2px);
    margin:           1px 1px 1px 0;
    border-radius:    0 4px 4px 0;
    background-color: #000;
    cursor:           pointer;
  }
</style>
