<template>
    <div class="row">
      <div class="col-md-6 ml-auto mr-auto">
      <h1 class="title">Your Story Starts With Us.</h1>
          <h4>
           *Airtime payment and bill payments from the convenience of your mobile device.  
            *Make payments from your card or e-wallet. Whichever works for you!                               
            *You can top up and pay bills for friends and family as well.
          </h4>
          <br>
          <div v-if="auth">
          <router-link class="btn btn-danger btn-raised btn-lg" to="/dashboard">Our Services</router-link>
          </div>
          <div v-else>
            <router-link class="btn btn-danger btn-raised btn-lg" to="/register">Create an account</router-link>
          </div>
      </div>
    </div>
</template>

<script>
export default {

}
</script>

<style>

</style>
