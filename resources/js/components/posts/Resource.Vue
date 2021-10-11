<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-8 col-md-6">
        <div class="alert alert-danger" role="alert" v-if="error">
          {{ error }}
        </div>
        <h3 class="text-center">{{ post.title }}</h3>
        <p class="text-center">{{ post.content }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "post",
  data() {
    return {
      error: "",
      post: null,
    };
  },
  methods: {},
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