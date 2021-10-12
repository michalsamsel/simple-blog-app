<template>
  <div class="container">
    <div class="row justify-content-center">
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
        <div class="alert alert-danger" role="alert" v-if="error">
          {{ error }}
        </div>

        <div class="text-center" v-if="posts === null">
          <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>

        <table class="table table-striped" v-else>
          <tr v-for="post in posts" :key="post.id">
            <td>
              <router-link :to="{ name: 'post', params: { id: post.id } }"
                ><h2 class="text-center">{{ post.title }}</h2></router-link
              >
            </td>
          </tr>
        </table>

        <div class="mb-3">
          <h5 class="text-center">Page {{ page }}</h5>
        </div>
      </div>
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
      page: 1,
      lastPage: 1,
      posts: null,
      error: "",
    };
  },
  methods: {
    changePage(value) {
      this.page += value;
      this.posts = null;
      this.$axios
        .get(`/api/user/${this.$route.params.id}/posts?page=${this.page}`)
        .then((response) => {
          this.posts = response.data.posts.data;
        })
        .catch((error) => {
          this.error = error.response.data.message;
        });
    },
  },
  created() {
    this.$axios
      .get(`/api/user/${this.$route.params.id}/posts?page=1`)
      .then((response) => {
        this.posts = response.data.posts.data;
        this.lastPage = response.data.posts.last_page;
      })
      .catch((error) => {
        this.error = error.response.data.message;
      });
  },
};
</script>
