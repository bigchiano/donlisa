<template>
  <nav class="navbar navbar-transparent navbar-color-on-scroll fixed-top navbar-expand-lg" color-on-scroll="100" id="sectionsNav">
    <div class="container">
      <div class="navbar-translate">
          <router-link to='/' class="navbar-brand"> Donlisa Recharge Pro (DRP) </router-link>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="sr-only">Toggle navigation</span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>

      <!-- if user is logged in -->
      <div v-if="auth" class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <router-link to='/dashboard' class="nav-link">Dashboard</router-link>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" @click="logout" class="nav-link">Logout</a>
          </li>
        </ul>
      </div>

      <!-- if user isn't logged in -->
      <div v-else class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <router-link to='/login' class="nav-link">Login</router-link>

          </li>
          <li class="nav-item">
            <router-link to='/register' class="nav-link">Register</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
$(document).ready(function() {
  $('.nav-item').click(function(){
      $("html").removeClass('nav-open');
      $(".navbar-toggler").removeClass('toggled');
  });
})

export default {

}
</script>

<style>

</style>