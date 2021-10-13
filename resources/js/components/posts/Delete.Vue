<template>
  <div class="container">
    <div class="row justify-content-center">
      <!--While processing request-->
      <div class="col-8 col-md-6" v-if="post === null">
        <h1 class="text-center">Loading article...</h1>
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
        <h3 class="text-center">{{ post.title }}</h3>
        <h5 class="text-center text-break">{{ post.content }}</h5>

        <br />
        <button type="button" class="btn btn-success mr-3" @click="deletePost">
          Delete
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
  name: "postDelete",
  data() {
    return {
      //Information variable
      message: "",
      error: "",
      //Resource
      post: null,
    };
  },
  methods: {
    //Method which deletes a post resource
    deletePost() {
      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .delete(`/api/post/${this.$route.params.id}`)
          .then((response) => {
            this.error = "";
            this.message = response.data.message;
            this.$router.push({
              name: "posts",
            }); //Show list of resources
          })
          .catch((error) => {
            this.message = "";
            this.error = error.response.data.message;
          });
      });
    },
  },
  created() {
    //Load post resource
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
