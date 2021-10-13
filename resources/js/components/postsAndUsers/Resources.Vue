<template>
  <div class="container">
    <div class="row justify-content-center">
      <!--Previous page of posts created by this user, pagination-->
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
      <div class="col-8 col-md-6">
        <!--Information div-->
        <div class="alert alert-danger" role="alert" v-if="error">
          {{ error }}
        </div>

        <!--While processing request-->
        <div class="text-center" v-if="posts === null">
          <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
        <!--When request is loaded. Table of resources created by user-->
        <table class="table table-striped" v-else>
          <tr v-for="post in posts" :key="post.id">
            <td>
              <router-link :to="{ name: 'post', params: { id: post.id } }"
                ><h2 class="text-center">{{ post.title }}</h2></router-link
              >
            </td>
          </tr>
        </table>

        <!--Information about current page of pagination-->
        <div class="mb-3">
          <h5 class="text-center">Page {{ page }}/{{ lastPage }}</h5>
        </div>
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
  name: "posts",
  data() {
    return {
      //Information variable
      error: "",
      //Pagination variables
      page: 1,
      lastPage: 1,
      //Resources
      posts: null,
    };
  },
  methods: {
    //Method for getting another page of resources
    changePage(value) {
      this.page += value; //Update page
      this.posts = null; //Remove old resources
      this.$axios
        .get(`/api/user/${this.$route.params.id}/posts?page=${this.page}`)
        .then((response) => {
          this.posts = response.data.posts.data; //Save new page of resources
        })
        .catch((error) => {
          this.error = error.response.data.message;
        });
    },
  },
  created() {
    //Load first page of resources
    this.$axios
      .get(`/api/user/${this.$route.params.id}/posts?page=1`)
      .then((response) => {
        this.posts = response.data.posts.data; //Store resources
        this.lastPage = response.data.posts.last_page; //Store information which page is the last.
      })
      .catch((error) => {
        this.error = error.response.data.message;
      });
  },
};
</script>
