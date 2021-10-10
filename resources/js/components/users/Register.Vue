<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-8 col-md-6">
        <div class="alert alert-danger" role="alert" v-if="error">
          {{ error }}
        </div>
        <div class="alert alert-success" role="alert" v-if="message">
          {{ message }}
        </div>
        <form @submit.prevent="submit">
          <div class="form-group">
            <label for="name">Nickname</label>
            <input
              type="text"
              class="form-control"
              id="name"
              name="name"
              placeholder="Example nickname"
              required
              minlength="3"
              maxlength="32"
            />
          </div>
          <div class="form-group">
            <label for="email">Email address</label>
            <input
              type="email"
              class="form-control"
              id="email"
              name="email"
              placeholder="name@example.com"
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
              aria-describedby="passwordHelp"
              required
              minlength="6"
              maxlength="16"
            />
            <small id="passwordHelp" class="form-text text-muted"
              >Password should be 6 do 16 character long. Password requires at
              least one uppercase character, lowercase character, digit, special
              character.</small
            >
          </div>
          <div class="form-group">
            <label for="password_confirmation">Password Confirmation</label>
            <input
              type="password"
              class="form-control"
              id="password_confirmation"
              name="password_confirmation"
              aria-describedby="passwordConfirmationHelp"
              required
              minlength="6"
              maxlength="16"
            />
            <small id="passwordConfirmationHelp" class="form-text text-muted"
              >Repeat password.</small
            >
          </div>
          <button type="submit" class="btn btn-primary">Register</button>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "Register",
  data() {
    return {
      error: "",
      message: "",
    };
  },
  methods: {
    submit(event) {
      const { name, email, password, password_confirmation } =
        Object.fromEntries(new FormData(event.target));

      this.$axios
        .post("/api/register", {
          name,
          email,
          password,
          password_confirmation,
        })
        .then((response) => {
          this.error = "";
          this.message = response.data.message;
          this.$router.push({
            name: "login",
          });
        })
        .catch((error) => {
          this.message = "";
          this.error = error.response.data.message;
        });
    },
  },
};
</script>