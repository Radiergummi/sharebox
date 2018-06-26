<template>
  <li :class="[ 'nav-item', 'dropdown', { show: open } ]">
    <span
      class="nav-link dropdown-toggle"
      role="button"
      aria-haspopup="true"
      aria-expanded="false"
      v-on:click="toggle"
    >
      <slot name="toggle">User</slot>
      <span class="caret"></span>
    </span>

    <div :class="[ 'dropdown-menu', 'dropdown-menu-right', { show: open } ]" aria-labelledby="navbarDropdown">
      <slot name="items"></slot>
      <span class="dropdown-item" v-on:click="logout">
        <span class="nav-link">
          <slot name="logout"></slot>
        </span>
      </span>
    </div>
  </li>
</template>

<script>
  export default {
    name: 'UserMenu',

    props: {
      logoutRoute: {
        type:     String,
        required: true
      }
    },

    data () {
      return {
        open: false
      };
    },

    mounted () {
    },

    methods: {
      toggle () {
        this.open = !this.open;
      },

      async logout () {
        let response;

        try {
          response = await  window.axios.post( this.logoutRoute, {} );
        } catch ( error ) {
          if ( error.hasOwnProperty( 'response' ) && error.response.status === 401 ) {
            window.location = window.location.origin;
          }
        }

        window.location = response.headers.location;
      }
    }
  };
</script>

<style scoped>
  .dropdown-toggle {
    cursor: pointer;
  }

  .nav-link {
    padding: 0;
    color:   inherit;
  }
</style>
