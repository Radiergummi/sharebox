<!--suppress HtmlFormInputWithoutLabel -->
<template>
  <div class="form-group file-upload-container">
    <div class="preview-image-container" v-if="!multiple">
      <div class="loading-indicator" v-show="loadingPreview">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
      <img v-bind:class="'preview-image ' + (preview ? 'has-preview' : 'no-preview')" :src="preview">
      <button
        class="btn remove-image-button"
        v-on:click="removeFile"
        v-on:keyup.enter="removeFile"
        v-if="preview"
      >
        <span class="material-icons">close</span>
      </button>
    </div>
    <div class="input-group">
      <label v-bind:for="'file-upload-' + name" class="input-group-btn">
        <button type="button" class="btn btn-primary" v-on:click="browse">
          <slot name="button-label"></slot>
        </button>
      </label>
      <input
        type="text"
        class="form-control read-only"
        readonly
        tabindex="-1"
        v-bind:value="fileNames"
      >
    </div>
    <input
      v-bind:id="'file-upload-' + name"
      v-bind:name="name"
      v-bind:multiple="multiple ? true : null"
      v-bind:accept="type"
      v-bind:required="required"
      ref="fileInput"
      type="file"
      style="display: none;"
      v-on:change="updateFile"
    >
  </div>
</template>

<script>
  export default {
    name: 'FileUpload',

    props: {
      name: {
        type:     String,
        required: true
      },

      required: {
        type:    Boolean,
        default: false
      },

      multiple: {
        type:    Boolean,
        default: false
      },

      type: {
        type:    String,
        default: '*'
      },

      initial: {
        type:     String,
        required: false
      }
    },

    data () {
      return {
        files:          [],
        preview:        null,
        loadingPreview: false
      };
    },

    computed: {
      fileNames () {
        if ( this.files.length === 0 ) {
          return '';
        }

        if ( this.files.length === 1 ) {
          return this.files[ 0 ].name;
        }

        return this.files.map( file => file.name ).join( ', ' );
      },

      isImageUpload () {
        return this.type.includes( 'image' );
      }
    },

    mounted () {
      if ( this.initial ) {
        this.preview = this.initial;
      }
    },

    methods: {
      browse () {
        this.$refs.fileInput.click();
      },

      updateFile ( event ) {
        this.files = event.target.files;

        if ( this.files[ 0 ].type.includes( 'image' ) ) {
          this.renderPreview();
        }
      },

      renderPreview () {
        if ( this.files && this.files[ 0 ] ) {
          this.loadingPreview = true;
          const reader        = new FileReader();

          reader.addEventListener( 'load', event => {
            this.preview        = event.target.result;
            this.loadingPreview = false;

            this.$emit( 'preview-rendered', this.preview );
          } );

          reader.readAsDataURL( this.files[ 0 ] );
        }
      },

      removeFile () {
        this.$refs.fileInput.value = null;
        this.files                 = [];
        this.preview               = null;
      }
    }
  };
</script>

<style scoped>
  .file-upload-container {
    display:        flex;
    flex-direction: column;
    align-items:    center;
  }

  .preview-image-container {
    position:         relative;
    width:            200px;
    height:           200px;
    margin-bottom:    1rem;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius:    50%;
    overflow:         hidden;
  }

  .input-group .input-group-btn {
    margin: 0;
    border: none;
  }

  .input-group .input-group-btn .btn {
    border-radius: 4px 0 0 4px;
  }

  .preview-image {
    min-width:  100%;
    width:      100%;
    min-height: 100%;
    height:     auto;
    object-fit: contain;
    transition: opacity 0.5s;
  }

  .preview-image.no-preview {
    opacity: 0;
  }

  .preview-image.has-preview {
    opacity: 1;
  }

  .remove-image-button {
    display:          flex;
    position:         absolute;
    top:              calc(50% - 2rem);
    left:             calc(50% - 2rem);
    width:            2rem;
    height:           2rem;
    padding:          2rem;
    border-radius:    50%;
    background-color: var(--primary);
    color:            var(--white);
    opacity:          0;
    transition:       all 0.5s;
  }

  .remove-image-button .material-icons {
    transform: translateY(-50%) translateX(-50%);
  }

  .remove-image-button:hover,
  .remove-image-button:focus {
    transform: scale(1.05);
  }

  .remove-image-button:active {
    transform: scale(0.95);
  }

  .preview-image-container:hover .remove-image-button,
  .preview-image-container:focus-within .remove-image-button {
    opacity:    1;
    box-shadow: 0 0 0 100px rgba(0, 0, 0, 0.5);
  }

  .loading-indicator {
    display:    inline-block;
    position:   relative;
    width:      200px;
    height:     200px;
    transition: opacity 0.25s;
  }

  .loading-indicator div {
    box-sizing:    border-box;
    display:       block;
    position:      absolute;
    width:         180px;
    height:        180px;
    margin:        10px;
    border:        3px solid var(--primary);
    border-radius: 50%;
    animation:     loading 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color:  var(--primary) transparent transparent transparent;
  }

  .loading-indicator div:nth-child(1) {
    animation-delay: -0.45s;
  }

  .loading-indicator div:nth-child(2) {
    animation-delay: -0.3s;
  }

  .loading-indicator div:nth-child(3) {
    animation-delay: -0.15s;
  }

  @keyframes loading {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
</style>
