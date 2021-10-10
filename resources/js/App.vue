<template>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-3">
    <router-link class="navbar-brand" to="/">Blog App</router-link>
    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <!--Options for everyone-->
        <li class="nav-item">
          <router-link class="nav-link" to="/posts">Articles list</router-link>
        </li>
        <!--End options for everyone-->
        <!--Options for guests-->
        <li class="nav-item" v-if="!isAuthenticated">
          <router-link class="nav-link" to="/register"
            >Create account</router-link
          >
        </li>
        <li class="nav-item" v-if="!isAuthenticated">
          <router-link class="nav-link" to="/login">Login</router-link>
        </li>
        <!--End options for guests-->
        <!--Options for users-->
        <li class="nav-item" v-if="isAuthenticated">
          <router-link class="nav-link" to="/logout" @click="logout"
            >Logout</router-link
          >
        </li>
        <!--End options for users-->
      </ul>
    </div>
  </nav>
  <router-view />
</template>

<script>
export default {
  name: "App",
  data() {
    return {
      isAuthenticated: false,
    };
  },
  methods: {
    logout() {
      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .post("/api/logout")
          .then((response) => {
            this.isAuthenticated=false;
            window.location.href = "/"
          })
          .catch((error) => {
            console.error(error.response.data.message);
          });
      });
    },
  },
  created() {
    this.isAuthenticated = window.Laravel.isAuthenticated;
  },
};
</script>