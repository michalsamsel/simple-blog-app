<template>
  <div class="container">
    <div class="row justify-content-center">
      <!--Previous page of comments, pagination-->
      <div class="col-2 col-md-3 align-self-end">
        <button
          type="button"
          class="btn btn-primary mx-auto mb-3"
          :disabled="page === 1"
          @click="changePage(-1)"
        >
          Previous Page
        </button>
      </div>

      <!--While processing request-->
      <div class="col-8 col-md-6" v-if="post === null">
        <h1 class="text-center">Loading article...</h1>
      </div>
      <!--When request is loaded. Table of resources created by all users-->
      <div class="col-8 col-md-6" v-else>
        <!--Display resource information-->
        <h3 class="text-center">{{ post.title }}</h3>
        <h5 class="text-center text-break">{{ post.content }}</h5>
        <small>
          <router-link :to="{ name: 'userPosts', params: { id: author.id } }">
            Written by:<b>{{ author.name }}</b>
          </router-link>
          , Last updated at:{{ post.updated_at }}</small
        >

        <br />
        <!--Buttons seen only by creator of post resource-->
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
        <!--Button for returning to previous page-->
        <button
          type="button"
          class="btn btn-secondary float-right"
          @click="$router.go(-1)"
        >
          Go back
        </button>

        <!--Information divs-->
        <div class="alert alert-danger" role="alert" v-if="error">
          {{ error }}
        </div>
        <div class="alert alert-success mt-3" role="alert" v-if="message">
          {{ message }}
        </div>

        <!--Form for creating a comment resource-->
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

        <!--Displaying comment written under this post-->
        <table class="table table-striped mt-3">
          <tr v-for="comment in comments" :key="comment.id">
            <td>
              {{ comment.content }}
            </td>
            <!--Buttons seen only by creator of comment resource-->
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
      <!--Next page of posts created by this user, pagination-->
      <div class="col-2 col-md-3 align-self-end">
        <button
          type="button"
          class="btn btn-primary mx-auto mb-3"
          :disabled="page === lastPage"
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
      //Information variable
      error: "",
      message: "",
      //Pagination variables
      page: 1,
      lastPage: 1,
      //Post resource
      post: null,
      author: null,
      //Comment resource
      comments: null,
      commentContent: "",
      //Variables from app.blade
      user: null,
      isAuthenticated: false,
    };
  },
  methods: {
    //Submiting form of creating a new comment resource
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
            this.loadComments(); //Reload comments when new resource is stored
          })
          .catch((error) => {
            this.message = "";
            this.error = error.response.data.message;
          });
      });
    },
    //Method for updating list of comment resources
    loadComments() {
      this.comments = null; //Remove old resources
      this.page = 1; //Get back to first page to show newly created comment resource
      this.$axios
        .get(`/api/post/${this.$route.params.id}/comments?page=${this.page}`)
        .then((response) => {
          this.comments = response.data.comments.data; //Save new page of resources
          this.lastPage = response.data.comments.last_page; //Update total number of pages
        })
        .catch((error) => {
          this.error = error.response.data.message;
        });
    },

    //Open view with post updating
    editPost() {
      this.$router.push({
        name: "postEdit",
        params: { id: this.$route.params.id },
      });
    },
    //Open view with post deleting
    deletePost() {
      this.$router.push({
        name: "postDelete",
        params: { id: this.$route.params.id },
      });
    },
    //Open view with comment updating
    editComment(commentId) {
      this.$router.push({
        name: "commentEdit",
        params: { id: commentId },
      });
    },
    //Open view with comment deleting
    deleteComment(commentId) {
      this.$router.push({
        name: "commentDelete",
        params: { id: commentId },
      });
    },

    //Method for getting another page of resources
    changePage(value) {
      this.page += value; //Update page
      this.comments = null; //Remove old resources
      this.$axios
        .get(`/api/post/${this.$route.params.id}/comments?page=${this.page}`)
        .then((response) => {
          this.comments = response.data.comments.data; //Save new page of resources
        })
        .catch((error) => {
          this.error = error.response.data.message;
        });
    },
  },
  created() {
    //Load post resource
    this.$axios
      .get(`/api/post/${this.$route.params.id}`)
      .then((response) => {
        this.error = "";
        this.post = response.data.post; //Resource data used to displaying info
        this.author = response.data.user; //Resource about author of post
      })
      .catch((error) => {
        this.error = error.response.data.message;
      });

    //Load list of comments
    this.$axios
      .get(`/api/post/${this.$route.params.id}/comments?page=1`)
      .then((response) => {
        this.error += "";
        this.comments = response.data.comments.data; //Store resources
        this.lastPage = response.data.comments.last_page; //Store information which page is the last.
      })
      .catch((error) => {
        this.error += error.response.data.message;
      });

    //Getting var values from app.blade
    this.user = window.Laravel.isAuthenticated ? window.Laravel.user : 0;
    this.isAuthenticated = window.Laravel.isAuthenticated;
  },
};
</script>
