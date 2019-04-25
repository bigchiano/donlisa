<template>
    <div class="page-header header-filter" style="background-image: url('/material-kit/img/scott-webb-57628-unsplash.jpg'); background-size: cover; background-position: top center;">
        <!-- <div style="height: 40px"></div> -->
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h1 class="title">Best Recharge Plartform</h1>
              <h4>Get best deals for all bils payment from <br>
                -Airtime payment and bill payments from the convenience of your mobile device.<br>  
                -Make payments from your card or e-wallet. Whichever works for you!<br>                               
                -You can top up and pay bills for friends and family as well.
              </h4>
              <br>
               <div v-if="auth">
                <router-link class="btn btn-danger btn-raised btn-lg" to="/dashboard">Our Services</router-link>
              </div>
              <div v-else>
                <router-link class="btn btn-danger btn-raised btn-lg" to="/register">Create an account with us</router-link>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
export default {
  //
}
</script>

<style>

</style>
