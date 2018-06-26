'use strict';

/*
 global window,
 document,
 axios,
 __CONFIG__
 */

import vue            from 'vue';
import VueHighlightJS from 'vue-highlightjs';
import AceEditor      from 'vue2-ace-editor';
import './bootstrap';
import ColorField     from './components/ColorField';
import DateField      from './components/DateField';
import FileBrowser    from './components/FileBrowser';
import FileUpload     from './components/FileUpload';
import TicketsChart   from './components/TicketsChart';
import UserMenu       from './components/UserMenu';

window.Vue = vue;

Vue.use( VueHighlightJS );

const app = new Vue(
  {
    el: '#app',

    components: {
      AceEditor,
      FileBrowser,
      UserMenu,
      FileUpload,
      DateField,
      ColorField,
      TicketsChart
    },

    mounted () {
      this.logoUrl = this.$el.querySelector( '.logo' ).dataset.src;
    },

    data () {
      return {
        confirmDeleteId:    null,
        confirmDeleteReset: null,
        logoUrl:            ''
      };
    },

    methods: {
      editorInit: function () {
        require( 'brace/ext/language_tools' );
        require( 'brace/mode/html' );
        require( 'brace/mode/javascript' );
        require( 'brace/mode/css' );
        require( 'brace/theme/github' );
      },

      async deleteItem ( id, route, successRoute = null ) {
        if ( this.confirmDeleteId === id ) {
          this.confirmDeleteReset = null;

          try {
            // delete the item
            await window.axios.delete( route );
          } catch ( error ) {
            window.location.reload();
          }

          if ( successRoute ) {
            return this.navigate( successRoute );
          }

          window.location.reload();
        } else {
          this.confirmDeleteId = id;

          this.confirmDeleteReset = setTimeout( () => {
            this.confirmDeleteId = null;
          }, 3000 );
        }
      },

      async updateItem ( data, route, successRoute ) {
        try {
          await window.axios.put( route, data );
        } catch ( error ) {
          window.location.reload();
        }

        this.navigate( successRoute );
      },

      navigate ( route ) {
        window.location = route;
      },

      handleTabInput ( event ) {
        const currentText = event.target.value;
        const start       = event.target.selectionStart;
        const end         = event.target.selectionEnd;

        // set textarea value to: text before caret + tab + text after caret
        event.target.value = currentText.substring( 0, start ) + '\t' + currentText.substring( end );

        // put caret at right position again
        event.target.selectionStart = event.target.selectionEnd = start + 1;
      },

      updateAppLogo ( dataUrl ) {
        this.logoUrl = dataUrl;
      }
    }
  }
);
