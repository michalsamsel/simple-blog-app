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
        <form @submit.prevent="submit">
          <div class="form-group">
            <label for="name">Title:</label>
            <input
              type="text"
              class="form-control"
              id="title"
              name="title"
              placeholder="Example title"
              aria-describedby="titleHelp"
              required
              minlength="1"
              maxlength="50"
              v-model="title"
            />
            <small id="titleHelp" class="form-text text-muted"
              >{{ 50 - title.length }}/50</small
            >
          </div>
          <div class="form-group">
            <label for="exampleFormControlTextarea1">Text:</label>
            <textarea
              class="form-control"
              id="content"
              name="content"
              rows="6"
              aria-describedby="contentHelp"
              required
              minlength="1"
              maxlength="500"
              v-model="content"
            ></textarea>
            <small id="contentHelp" class="form-text text-muted"
              >{{ 500 - content.length }}/500</small
            >
          </div>
          <button type="submit" class="btn btn-success mr-3">
            Save Changes
          </button>
          <button type="button" class="btn btn-danger" @click="$router.go(-1)">
            Cancel
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "postEdit",
  data() {
    return {
      message: "",
      error: "",
      title: "",
      content: "",
    };
  },
  methods: {
    submit(event) {
      const { title, content } = Object.fromEntries(new FormData(event.target));

      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .put(`/api/post/${this.$route.params.id}`, {
            title,
            content,
          })
          .then((response) => {
            this.error = "";
            this.message = response.data.message;
            this.$router.push({
              name: "post",
              params: { id: this.$route.params.id },
            });
          })
          .catch((error) => {
            this.message = "";
            this.error = error.response.data.message;
          });
      });
    },
  },
  created() {
    this.$axios
      .get(`/api/post/${this.$route.params.id}`)
      .then((response) => {
        this.error = "";
        const post = response.data.post;
        this.title = post.title;
        this.content = post.content;
      })
      .catch((error) => {
        this.error = error.response.data.message;
      });
  },
};
</script>