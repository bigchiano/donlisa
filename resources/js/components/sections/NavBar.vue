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
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li v-if="auth" class="nav-item">
            <router-link to='/dashboard' class="nav-link">
            <i class="material-icons">apps</i> Dashboard
            </router-link>
          </li>
          
          <li v-if="auth" class="nav-item">
            <router-link to="/buy_power" class="nav-link">
                <i class="material-icons">wb_incandescent</i>  Power
              </router-link>
          </li>
          <li v-if="auth" class="nav-item">
            <router-link to="/buy_airtime" class="nav-link">
                <i class="material-icons">perm_phone_msg</i> Airtime
              </router-link>
          </li>
          <li v-if="auth" class="nav-item">
            <router-link to="/buy_data" class="nav-link">
                <i class="material-icons">data_usage</i> Data
              </router-link>
          </li>
          <li v-if="auth" class="nav-item">
            <router-link to="/subscribe_tv" class="nav-link">
                <i class="material-icons">tv</i> Tv
              </router-link>
          </li>                   
          
          
          
          <!-- <li class="nav-item">
            <a href="javascript:void(0)" @click="cs" class="nav-link">
              <i class="material-icons">history</i>History</a>
          </li> -->
          <li v-if="auth" class="nav-item">
            <a href="javascript:void(0)" @click="logout" class="nav-link">
              <i class="material-icons">toggle_off</i>Logout</a>
          </li>

      <li class="nav-item">
        <router-link to="/about" class="nav-link">
            About Us
          </router-link>
      </li>
      <li class="nav-item">
        <router-link to="/member" class="nav-link">
            Pay with Edpoints
          </router-link>
      </li>
      <li class="nav-item">
        <router-link to="/faq" class="nav-link">
            FAQ
          </router-link>
      </li>

      <!-- if user isn't logged in -->
          <li v-if="!auth" class="nav-item">
            <router-link to='/login' class="nav-link">Login</router-link>

          </li>
          <li v-if="!auth" class="nav-item">
            <router-link to='/register' class="nav-link">Register</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  mounted() {
    $('.nav-item').click(function(){
        $("html").removeClass('nav-open');
        $(".navbar-toggler").removeClass('toggled');
    });
  }
}
</script>

<style>

</style>