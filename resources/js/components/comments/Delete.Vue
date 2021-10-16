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
        <!--Displayin resource values-->
        <h2>{{ comment.content }}</h2>
        <button type="button" class="btn btn-success mr-3" @click="deleteComment">
          Delete comment
        </button>
        <button type="button" class="btn btn-danger" @click="$router.go(-1)">
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "commentDelete",
  data() {
    return {
      //Information variable
      message: "",
      error: "",
      //Resource
      comment: null,
    };
  },
  methods: {
    //Method which deletes a comment resource
    deleteComment() {
      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .delete(`/api/comment/${this.$route.params.id}`, {})
          .then((response) => {
            this.error = "";
            this.message = response.data.message;
            this.$router.push({
              name: "post",
              params: { id: this.comment.post_id },
            }); //Return to post resource
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
      })
      .catch((error) => {
        this.error = error.response.data.message;
      });
  },
};
</script>
