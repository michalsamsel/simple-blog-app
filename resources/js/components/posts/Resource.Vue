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
        <h3 class="text-center">{{ post.title }}</h3>
        <h5 class="text-center text-break">{{ post.content }}</h5>
        <small
          >Written by:<b>{{ author.name }}</b
          >, Last updated at:{{ post.updated_at }}</small
        >

        <br />
        <button
          type="button"
          class="btn btn-primary mr-3"
          v-if="author.id === user.id"
          @click="editPost"
        >
          Edit
        </button>
        <button
          type="button"
          class="btn btn-danger mr-3"
          v-if="author.id === user.id"
          @click="deletePost"
        >
          Delete
        </button>
        <button
          type="button"
          class="btn btn-secondary float-right"          
          @click="$router.go(-1)"
        >
          Go back
        </button>
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
      author: null,
      user: null,
    };
  },
  methods: {
    editPost() {
      this.$router.push({
        name: "postEdit",
        params: { id: this.$route.params.id },
      });
    },
    deletePost() {
      this.$router.push({
        name: "postDelete",
        params: { id: this.$route.params.id },
      });
    },
  },
  created() {
    this.$axios
      .get(`/api/post/${this.$route.params.id}`)
      .then((response) => {
        this.error = "";
        this.post = response.data.post;
        this.author = response.data.user;
      })
      .catch((error) => {
        this.error = error.response.data.message;
      });
    this.user = window.Laravel.isAuthenticated ? window.Laravel.user : 0;
  },
};
</script>
