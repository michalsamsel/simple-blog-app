<template>
  <div class="container">
    <div class="row justify-content-center">
      <!--While processing request-->
      <div class="col-8 col-md-6" v-if="post === null">
        <h1 class="text-center">Loading comment...</h1>
      </div>
      <!--When request is loaded.-->
      <div class="col-8 col-md-6" v-else>
        <!--Information divs-->
        <div class="alert alert-danger" role="alert" v-if="error">
          {{ error }}
        </div>
        <div class="alert alert-success" role="alert" v-if="message">
          {{ message }}
        </div>
        <!--Form for updating a comment resource-->
        <form @submit.prevent="submit">
          <div class="form-group mt-5">
            <label for="commentContent">Comment:</label>
            <textarea
              class="form-control"
              id="commentContent"
              name="commentContent"
              rows="4"
              aria-describedby="commentContentHelp"
              required
              minlength="1"
              maxlength="200"
              v-model="commentContent"
            ></textarea>
            <small id="commentContentHelp" class="form-text text-muted"
              >{{ 200 - commentContent.length }}/200</small
            >
          </div>
          <button type="submit" class="btn btn-success mr-3">Save Changes</button>
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
  name: "commentEdit",
  data() {
    return {
      //Information variable
      message: "",
      error: "",
      //Updated data of resource
      comment: null,
      commentContent: "",
    };
  },
  methods: {
    //Submiting form of updating a comment resource
    submit(event) {
      const { commentContent } = Object.fromEntries(new FormData(event.target));

      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .put(`/api/comment/${this.$route.params.id}`, {
            postId: this.$route.params.id,
            content: this.commentContent,
          })
          .then((response) => {
            this.error = "";
            this.message = response.data.message;
            this.$router.push({
              name: "post",
              params: { id: this.comment.post_id },
            }); //Show updated resource
          })
          .catch((error) => {
            this.message = "";
            this.error = error.response.data.message;
          });
      });
    },
  },
  created() {
    //Load comment resource
    this.$axios
      .get(`/api/comment/${this.$route.params.id}`)
      .then((response) => {
        this.error = "";
        this.comment = response.data.comment;
        this.commentContent = this.comment.content; //Get value for form input
      })
      .catch((error) => {
        this.error = error.response.data.message;
      });
  },
};
</script>
