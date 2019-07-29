<template>
<div>
    <div class="page-header header-filter" style="background-image: url('/material-kit/img/scott-webb-57628-unsplash.jpg'); background-size: cover; background-position: top center;">
        <!-- <div style="height: 40px"></div> -->
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h2 class="title">Best Recharge Platform</h2>
              <h4>
                You can now recharge like a PRO.                      
                Get the best deals for your Airtime and bills payments from the convenience of your mobile device.
                <br>-Make cashless payments with EDPOINTS  from your mobile phones,  ATM Cards, e-wallet etc.
                <br>-You can top up Airtime and pay bills for friends and family and corporate organizations.                                          
                <br>-You can also make more money as a Donlisa Recharge Agent ( DRA).                      
                <br>-Create your Donlisa account Now!
              </h4>
              <br>
               <div v-if="auth">
                <router-link class="btn btn-danger btn-raised btn-lg" to="/dashboard">Welcome</router-link>
              </div>
              <div v-else>
                <router-link class="btn btn-danger btn-raised btn-lg" to="/register">Create an account with us</router-link>
              </div>
            </div>

            <div class="col-md-6">
              <div class="row">
                    <div class="col-md-8 ml-auto" style="padding-bottom: 80px;">
                        <!-- <div class="card" style="max-width: 80%; ">
                        <div class="card-body">
                          <img src="/img/edpoint.jpg" alt="Card image cap"></div>
                        </div> -->

                        <div class="card">
                        <img class="card-img" src="/img/banner.jpg" alt="Card image">
                        <!-- <div class="card-img-overlay">
                            <h4 class="card-title">Card title</h4>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            <p class="card-text">Last updated 3 mins ago</p>
                        </div> -->
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
    <div class="main">
      <div class="container">
        <div class="section">

            <div class="row">
                <div class="col-md-8 ml-auto mr-auto">
                    <div class="card ">
                    <img class="card-img" src="/img/payment-option.jpg" alt="Card image">
                    </div>
                </div>
            </div>

             <div class="row">
                <div class="col-md-8 ml-auto mr-auto">
                    <div class="card ">
                    <img class="card-img" src="/img/service-list.jpg" alt="Card image">
                    </div>
                </div>
            </div>
        </div>

   
        <!-- <div class="section section-contacts">
          <div class="row">
            <div class="col-md-8 ml-auto mr-auto">
              <h2 class="text-center title">CONTACT US</h2>
              <h4 class="text-center description">
                HEAD OFFICE: SUIT SC 20 BANAX PLAZA, AMINU KANO CRESCENT WUSE 2. 
                PHONE: 08092876438, 07034790806, 08065332868.
              </h4>
              <form class="contact-form" @submit.prevent="mail">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group bmd-form-group">
                      <label class="bmd-label-floating">Your Name</label>
                      <input type="email" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group bmd-form-group">
                      <label class="bmd-label-floating">Your Email</label>
                      <input type="email" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="form-group bmd-form-group">
                  <label for="exampleMessage" class="bmd-label-floating">Your Message</label>
                  <textarea type="email" class="form-control" rows="4" id="exampleMessage"></textarea>
                </div>
                <div class="row">
                  <div class="col-md-4 ml-auto mr-auto text-center">
                    <button class="btn btn-primary btn-raised">
                      Send Message
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div> -->


      </div>
    </div>
</div>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      email: '',
      message: ''
    }
  },
  methods: {
    mail() {
      // (async() => {
      //   try {
      //     const response = await axios.get('api/mail')
      //     console.log(response.data.status)
      //   } catch (error) {
      //     console.log(error.response)
      //   }
      // })()
    }
  }
}
</script>

<style>
   
</style>
