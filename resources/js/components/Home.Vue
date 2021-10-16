<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-8 col-md-6">
      <!--List of features in this app-->
        <div class="card" style="width: 30rem">
          <div class="card-header">Simple blog app</div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              This app lets guests to read posts and comments
            </li>
            <li class="list-group-item">
              If user create an account he can write his own posts and comments
            </li>
            <li class="list-group-item">You can see list of all posts</li>
            <li class="list-group-item">
              You can see list of all posts created by specified user
            </li>
            <li class="list-group-item">Posts can be created, updated, deleted</li>
            <li class="list-group-item">Posts can be created, updated, deleted</li>
            <li class="list-group-item">Posts and comments are paginated before displaying</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "home",
};
</script>
