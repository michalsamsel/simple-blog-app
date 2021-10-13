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
        <!--Form for creating a post resource-->
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
            <label for="content">Text:</label>
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
          <button type="submit" class="btn btn-primary">Post it</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "postCreate",
  data() {
    return {
      //Information variable
      message: "",
      error: "",
      //Data of resource
      title: "",
      content: "",
    };
  },
  methods: {
    //Submiting form of a new post resource
    submit(event) {
      const { title, content } = Object.fromEntries(new FormData(event.target));

      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .post("/api/post/create", {
            title,
            content,
          })
          .then((response) => {
            this.error = "";
            this.message = response.data.message;
            this.$router.push({
              name: "post",
              params: { id: response.data.postId },
            }); //Show newly created resource
          })
          .catch((error) => {
            this.message = "";
            this.error = error.response.data.message;
          });
      });
    },
  },
};
</script>
