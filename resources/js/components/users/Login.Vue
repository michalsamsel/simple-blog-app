<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-8 col-md-6">
        <!--Information divs-->
        <div class="alert alert-danger" role="alert" v-if="error">
          {{ error }}
        </div>
        <div class="alert alert-success" role="alert" v-if="message">
          {{ message }}
        </div>
        <!--Form for user login-->
        <form @submit.prevent="submit">
          <div class="form-group">
            <label for="email">Email address</label>
            <input
              type="email"
              class="form-control"
              id="email"
              name="email"
              placeholder="example@email.com"
              required
              maxlength="255"
            />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input
              type="password"
              class="form-control"
              id="password"
              name="password"
              required
              minlength="6"
              maxlength="16"
            />
          </div>
          <button type="submit" class="btn btn-primary">Login</button>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "login",
  data() {
    return {
      //Information variables
      error: "",
      message: "",
    };
  },
  methods: {
    //Sending login data to UserAuthenticationController
    submit(event) {
      const { email, password } = Object.fromEntries(new FormData(event.target));

      this.$axios
        .post("/api/login", {
          email,
          password,
        })
        .then((response) => {
          this.error = "";
          this.message = response.data.message;
          window.location.href = "/posts"; //Reloading page to update navbar
        })
        .catch((error) => {
          this.message = "";
          this.error = error.response.data.message;
        });
    },
  },
};
</script>
