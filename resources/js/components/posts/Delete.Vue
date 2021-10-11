<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-8 col-md-6" v-if="post === null">
        <h1 class="text-center">Loading article...</h1>
      </div>
      <div class="col-8 col-md-6" v-else>
        <div class="alert alert-danger" role="alert" v-if="error">
          {{ error }}
        </div>
        <div class="alert alert-success" role="alert" v-if="message">
          {{ message }}
        </div>
        <h3 class="text-center">{{ post.title }}</h3>
        <h5 class="text-center text-break">{{ post.content }}</h5>

        <br />
        <button type="button" class="btn btn-success mr-3" @click="submit">Delete</button>
        <button type="button" class="btn btn-danger" @click="$router.go(-1)">
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "postDelete",
  data() {
    return {
      message: "",
      error: "",
      post: null,
    };
  },
  methods: {
    submit(event)
    {
      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .delete(`/api/post/${this.$route.params.id}`)
          .then((response) => {
            this.error = "";
            this.message = response.data.message;
            this.$router.push({
              name: "posts",
              params: { id: this.$route.params.id },
            });
          })
          .catch((error) => {
            this.message = "";
            this.error = error.response.data.message;
          });
      });
    }
  },
  created() {
    this.$axios
      .get(`/api/post/${this.$route.params.id}`)
      .then((response) => {
        this.error = "";
        this.post = response.data.post;
      })
      .catch((error) => {
        this.error = error.response.data.message;
      });
  },
};
</script>