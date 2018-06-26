<template>
  <article v-bind:class="['card', 'file-browser', { loading } ]">
    <header class="card-header d-flex justify-content-between align-items-center">
      {{ selectedFile || currentDirectory }}
      <div class="loading-indicator">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </header>
    <section class="card-body file-list">
      <div class="list-wrap">
        <ul class="list-group list-group-flush current-directory-content">
          <li
            class="list-group-item error-item"
            v-if="error"
            tabindex="-1"
          >
            <span class="material-icons item-icon">warning</span>
            <span class="item-name">{{ error }}</span>
          </li>
          <li
            class="list-group-item parent-directory"
            v-show="currentDirectory !== parentDirectory"
            v-on:click="navigate(parentDirectory)"
            v-on:keyup.enter="navigate(parentDirectory)"
            v-on:keyup.space.prevent="navigate(parentDirectory)"
            tabindex="0"
          >
            <span class="material-icons item-icon">arrow_upward</span>
            <span class="item-name">..</span>
          </li>
          <li
            class="list-group-item directory"
            v-for="directory in directories"
            v-bind:key="directory"
            v-on:click="navigate(directory)"
            v-on:keyup.enter="navigate(directory)"
            v-on:keyup.space.prevent="navigate(directory)"
            tabindex="0"
          >
            <span class="material-icons item-icon">folder</span>
            <span class="item-name">{{ getBasename(directory) }}</span>
          </li>
          <li
            v-for="file in files"
            v-bind:class="getFileClasses(file)"
            v-bind:key="file"
            v-on:click="selectFile(file)"
            v-on:keyup.enter="selectFile(file)"
            v-on:keyup.space.prevent="selectFile(file)"
            tabindex="0"
          >
            <div class="loading-indicator">
              <div></div>
              <div></div>
              <div></div>
              <div></div>
            </div>
            <span class="material-icons item-icon">
            {{ file === selectedFile ? 'check' : 'insert_drive_file' }}
          </span>
            <span class="item-name">{{ getBasename(file) }}</span>
          </li>
        </ul>
      </div>
    </section>
    <footer class="card-footer d-flex justify-content-between">
      <div class="metadata">
        <span v-show="selectedFileSize">{{ getFilesize(selectedFileSize) }}</span>
        <span v-show="selectedFileLastModified">{{ getDate(selectedFileLastModified) }}</span>
      </div>
      <div class="statistics">
        <span class="material-icons statistics-item statistics-directories">folder</span>
        <span class="material-icons statistics-item statistics-files">insert_drive_file</span>
      </div>
    </footer>
    <input type="hidden" name="path" v-bind:value="selectedFile">
  </article>
</template>

<script>
  import { basename, dirname, extname } from 'path';

  /**
   * Provides a file browser to choose a path on a remote file system. The component allows navigating a
   * file system structure and retrieving meta data for a file.
   * All navigation is performed asynchronously.
   */
  export default {
    name: 'FileBrowser',

    props: {

      /**
       * Initial path to navigate to after load
       *
       * @type {String}
       */
      path: {
        type:     String,
        required: false
      },

      filesApi: {
        type:    String,
        default: '/api/files'
      },

      metaApi: {
        type:    String,
        default: '/api/meta'
      }
    },

    data () {
      return {

        /**
         * Holds the currently shown directory path
         *
         * @type {String}
         */
        currentDirectory: '/',

        /**
         * Holds the list of files inside the current directory
         *
         * @type {String[]}
         */
        files: [],

        /**
         * Holds the list of directories inside the current directory
         *
         * @type {String[]}
         */
        directories: [],

        /**
         * Whether the browser is currently loading data from the server
         *
         * @type {Boolean}
         */
        loading: false,

        /**
         * Holds the path to the selected file. This is also being set as the value
         * of the hidden input.
         *
         * @type {String}
         */
        selectedFile: null,

        /**
         * Holds the size of the currently selected file in bytes
         *
         * @type {Number}
         */
        selectedFileSize: null,

        /**
         * Holds the last modification date of the currently selected file
         *
         * @type {String}
         */
        selectedFileLastModified: null,

        error: null
      };
    },

    computed: {

      /**
       * Retrieves the name of the parent directory
       *
       * @returns {string}
       */
      parentDirectory () {
        return dirname( this.currentDirectory );
      }
    },

    async mounted () {

      // if the initial path has an extension, load the parent folder and select the file.
      // this is a cheap little trick, since there could also be files without extensions,
      // however there isn't really a better solution I'm aware of.
      if ( extname( this.path ) ) {
        await this.loadDirectoryContent( dirname( this.path ) );
        this.selectFile( this.path );
      } else {

        // otherwise, we've got a directory, so we'll just navigate to that.
        this.loadDirectoryContent( this.path );
      }
    },

    methods: {

      /**
       * Loads the files and directories for a given path.
       *
       * @param   {String}        [path] path to navigate to. Defaults to the root directory of the
       *                                 remote file system
       * @returns {Promise<void>}
       */
      async loadDirectoryContent ( path = '/' ) {

        // enable loading mode
        this.loading = true;
        let response;

        try {
          this.$emit( 'fetch:files:start', path );

          // fetch the path content from the API
          response = await window.axios.get( `${this.filesApi}/${path.replace( /^\/+/g, '' )}` );

          // set the current directory to the (validated) path from the server
          this.currentDirectory = response.data.path;

          // set the files and directories as retrieved from the API
          this.files       = response.data.files;
          this.directories = response.data.directories;

          this.$emit( 'fetch:files:success', {
            files:       this.files,
            directories: this.directories
          } );
        } catch ( error ) {

          // TODO: Handle the error in a meaningful way
          console.error( `Could not load directory: ${error.message}` );
          this.error = error.message;
          this.$emit( 'fetch:files:error', error );
        } finally {

          // whatever the outcome, disable loading mode to prevent endless loading for errors
          this.loading = false;
        }
      },

      /**
       * Loads metadata for a given path
       *
       * @param   {String}        path file path to load metadata for
       * @returns {Promise<void>}
       */
      async loadFileInfo ( path ) {
        let response;

        try {
          this.$emit( 'fetch:meta:start', path );

          // fetch the path metadata from the API
          response = await window.axios.get( `${this.metaApi}/${path.replace( /^\/+/g, '' )}` );

          // Set the file metadata
          this.selectedFileSize         = response.data.size;
          this.selectedFileLastModified = response.data.lastModified;

          this.$emit( 'fetch:meta:success', {
            fileSize:         this.selectedFileSize,
            fileLastModified: this.selectedFileLastModified
          } );
        } catch ( error ) {

          // TODO Handle the error in a meaningful way
          console.error( `Could not load file info: ${error.message}` );
          this.error = error.message;
          this.$emit( 'fetch:meta:error', error );
        }
      },

      /**
       * Navigates to a filesystem path. Basically just a wrapper for loadDirectoryContent.
       *
       * @param   {String}        path filesystem path to the directory
       * @returns {Promise<void>}
       */
      async navigate ( path ) {
        this.$emit( 'navigate:before', path );
        await this.loadDirectoryContent( path );
        this.$emit( 'navigate:after', path );
      },

      /**
       * Retrieves the basename of a file path
       *
       * @param   {String} path filesystem path to the file or directory
       * @returns {String}
       */
      getBasename ( path ) {
        return basename( path );
      },

      /**
       * Converts the file size into a human-readable string, converted to the next SI unit.
       * It looks complicated, but is 5th-grade math actually. Okay, maybe 6th.
       *
       * @param   {Number} bytes file size in bytes
       * @returns {String}       human readable size string with unit suffix
       */
      getFilesize ( bytes ) {

        // TODO:   Dude, I swear, next year, we're all going to download exabytes! This code
        // TODO:   is *SO* going to produce undefined index errors by then! Like WTH
        const sizes = [ 'Bytes', 'KB', 'MB', 'GB', 'TB' ];

        // no bytes, no calculation
        if ( bytes === 0 ) {
          return 'n/a';
        }

        // noinspection JSCheckFunctionSignatures
        const i = parseInt( Math.floor( Math.log( bytes ) / Math.log( 1024 ) ), 10 );

        if ( i === 0 ) {
          return `${bytes} ${sizes[ i ]}`;
        }

        return `${( bytes / ( 1024 ** i ) ).toFixed( 1 )} ${sizes[ i ]}`;
      },

      /**
       * Converts a timestamp into a formatted and localized date string
       *
       * @param   {String|Number} timestamp date or date-time as a timestamp
       * @returns {String}                  formatted date string
       */
      getDate ( timestamp ) {
        return new Date( timestamp ).toLocaleDateString();
      },

      /**
       * Retrieves all CSS classes for a file list entry
       *
       * @param   {String}   file file path to generate classes for
       * @returns {String[]}      list of CSS classes
       */
      getFileClasses ( file ) {
        const selected = ( file === this.selectedFile );

        return [
          'list-group-item',
          'file',
          { selected },
          { loading: selected && this.selectedFileSize === null }
        ];
      },

      /**
       * Selects a file. If the same file is selected twice, it's deselected.
       *
       * @param   {String}        file file to select
       * @returns {Promise<void>}
       */
      async selectFile ( file ) {
        if ( this.selectedFile === file ) {
          this.$emit( 'deselect', file );
          this.selectedFile             = null;
          this.selectedFileLastModified = null;
          this.selectedFileSize         = null;
        } else {
          this.$emit( 'select', file );
          this.selectedFile             = file;
          this.selectedFileLastModified = null;
          this.selectedFileSize         = null;
          await this.loadFileInfo( file );
        }
      }
    }
  };
</script>

<style scoped>
  .file-browser {
    margin:        1rem 0;
    border:        none;
    counter-reset: directories 0 files 0;
    color:         rgba(255, 255, 255, 0.75);
  }

  .file-list {
    padding: 0;
  }

  .card-header,
  .card-footer {
    border: 1px solid rgba(255, 255, 255, 0.25);
  }

  .card-header {
    border-radius: 4px 4px 0 0;
  }

  .card-footer {
    border-radius: 0 0 4px 4px;
  }

  .list-wrap {
    max-height: 250px;
    overflow-y: auto;
  }

  .current-directory-content {
    min-height:   3rem;
    padding:      1px 0;
    border-left:  1px solid rgba(255, 255, 255, 0.25);
    border-right: 1px solid rgba(255, 255, 255, 0.25);
  }

  .file-browser.loading .current-directory-content {
    pointer-events: none;
  }

  .file-browser.loading .current-directory-content .list-group-item {
    opacity: 0.5;
  }

  .current-directory-content .list-group-item {
    position:    relative;
    display:     flex;
    align-items: center;
    cursor:      pointer;
    transition:  background 0.125s;
    min-height:  3rem;
    user-select: none;
  }

  .current-directory-content .list-group-item .item-icon {
    margin-right: 1rem;
    color:        var(--gray);
    transition:   opacity 0.125s;
  }

  .current-directory-content .list-group-item:focus,
  .current-directory-content .list-group-item:hover {
    background-color: rgba(255, 255, 255, 0.25);
  }

  .current-directory-content .list-group-item:active {
    background-color: rgba(255, 255, 255, 0.15);
  }

  .current-directory-content .list-group-item:active,
  .current-directory-content .list-group-item:focus {
    outline: none;
  }

  .current-directory-content .list-group-item.directory {
    font-weight:       bold;
    counter-increment: directories;
  }

  .current-directory-content .list-group-item.file {
    counter-increment: files;
    color:             rgba(255, 255, 255, 0.65);
  }

  .current-directory-content .list-group-item.file .loading-indicator {
    position:    absolute;
    margin-left: 0;
  }

  .current-directory-content .list-group-item.file.loading .loading-indicator {
    opacity: 1;
  }

  .current-directory-content .list-group-item.file.loading .item-icon {
    opacity: 0;
  }

  .current-directory-content .list-group-item.file.selected {
    outline: 1px solid var(--green);
    color:   var(--green);
  }

  .current-directory-content .list-group-item.file.selected .item-icon {
    color: inherit;
  }

  .statistics span + span,
  .metadata span + span {
    margin-left: 1rem;
  }

  .statistics .statistics-item {
    position: relative;
    display:  inline-flex;
  }

  .statistics .statistics-item::before {
    margin:      auto;
    font-family: var(--font-family-sans-serif);
    font-size:   0.75rem;
    font-weight: bold;
  }

  .statistics .statistics-directories::before {
    content: counter(directories);
  }

  .statistics .statistics-files::before {
    content: counter(files);
  }

  /**
   * Yeah, I know, the whole thing looks convoluted as hell. Sorry. If you come up with something
   * better, feel free to replace this.
   */
  .loading-indicator {
    display:    inline-block;
    position:   relative;
    width:      26px;
    height:     26px;
    opacity:    0;
    transition: opacity 0.25s;
  }

  .loading-indicator div {
    box-sizing:    border-box;
    display:       block;
    position:      absolute;
    width:         13px;
    height:        13px;
    margin:        6px;
    border:        1px solid #ccc;
    border-radius: 50%;
    animation:     loading 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color:  #ccc transparent transparent transparent;
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

  .file-browser.loading .card-header .loading-indicator {
    opacity: 1;
  }
</style>
