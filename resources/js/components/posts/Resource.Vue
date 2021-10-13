<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-2 col-md-3 align-self-end">
        <button
          type="button"
          class="btn btn-primary mx-auto mb-3"
          :disabled="commentsPage === 1"
          @click="changePage(-1)"
        >
          Previous Page
        </button>
      </div>
      <div class="col-8 col-md-6" v-if="post === null">
        <h1 class="text-center">Loading article...</h1>
      </div>
      <div class="col-8 col-md-6" v-else>
        <div class="alert alert-danger" role="alert" v-if="error">
          {{ error }}
        </div>
        <h3 class="text-center">{{ post.title }}</h3>
        <h5 class="text-center text-break">{{ post.content }}</h5>
        <small>
          <router-link :to="{ name: 'userPosts', params: { id: author.id } }">
            Written by:<b>{{ author.name }}</b>
          </router-link>
          , Last updated at:{{ post.updated_at }}</small
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

        <div class="alert alert-success mt-3" role="alert" v-if="message">
          {{ message }}
        </div>

        <form @submit.prevent="submit" v-if="isAuthenticated">
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
          <button type="submit" class="btn btn-primary">Comment it</button>
        </form>

        <table class="table table-striped mt-3">
          <tr v-for="comment in comments" :key="comment.id">
            <td>
              {{ comment.content }}
            </td>
            <td v-if="comment.user_id == user.id">
              <button
                type="button"
                class="btn btn-primary mr-3"
                v-if="author.id === user.id"
                @click="editComment(comment.id)"
              >
                Edit
              </button>
            </td>
            <td v-if="comment.user_id == user.id">
              <button
                type="button"
                class="btn btn-danger mr-3"
                v-if="author.id === user.id"
                @click="deleteComment(comment.id)"
              >
                Delete
              </button>
            </td>
          </tr>
        </table>
      </div>
      <div class="col-2 col-md-3 align-self-end">
        <button
          type="button"
          class="btn btn-primary mx-auto mb-3"
          :disabled="commentsPage === commentsLastPage"
          @click="changePage(1)"
        >
          Next Page
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
      message: "",
      post: null,
      author: null,
      user: null,
      comments: null,
      commentsPage: 1,
      commentsLastPage: 1,
      commentContent: "",
      isAuthenticated: false,
    };
  },
  methods: {
    submit(event) {
      const { commentContent } = Object.fromEntries(new FormData(event.target));

      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .post("/api/comment/create", {
            postId: this.$route.params.id,
            content: commentContent,
          })
          .then((response) => {
            this.error = "";
            this.message = response.data.message;
            this.loadComments();
          })
          .catch((error) => {
            this.message = "";
            this.error = error.response.data.message;
          });
      });
    },
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
    editComment(commentId) {
      this.$router.push({
        name: "commentEdit",
        params: { id: commentId },
      });
    },
    deleteComment(commentId) {
      this.$router.push({
        name: "commentDelete",
        params: { id: commentId },
      });
    },
    changePage(value) {
      this.commentsPage += value;
      this.comments = null;
      this.$axios
        .get(`/api/post/${this.$route.params.id}/comments?page=${this.commentsPage}`)
        .then((response) => {
          this.comments = response.data.comments.data;
        })
        .catch((error) => {
          this.error = error.response.data.message;
        });
    },
    loadComments() {
      this.comments = null;
      this.commentsPage = 1;
      this.$axios
        .get(`/api/post/${this.$route.params.id}/comments?page=1`)
        .then((response) => {
          this.comments = response.data.comments.data;
          this.commentsLastPage = response.data.comments.last_page;
        })
        .catch((error) => {
          this.error = error.response.data.message;
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

    this.$axios
      .get(`/api/post/${this.$route.params.id}/comments?page=1`)
      .then((response) => {
        this.error += "";
        this.comments = response.data.comments.data;
        this.commentsLastPage = response.data.comments.last_page;
      })
      .catch((error) => {
        this.error += error.response.data.message;
      });

    this.user = window.Laravel.isAuthenticated ? window.Laravel.user : 0;
    this.isAuthenticated = window.Laravel.isAuthenticated;
  },
};
</script>
