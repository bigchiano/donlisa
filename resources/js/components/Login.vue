<template>
    <div>
        <!-- <div class="row">
            <div class="col-md-6 ml-auto mr-auto">
            <form @submit.prevent="" class="form">
                <h4 class="card-title text-center">Login</h4>

                <p class="text-danger" v-if="error != ''">*{{ error }}</p>
                <div class="form-group label-floating">
                <label class="control-label">Email/Phone</label>
                    <input v-model="email" type="text" class="form-control" />
                </div>

                <div class="form-group label-floating">
                <label class="control-label">Password</label>
                    <input v-model="password" type="password" class="form-control" />
                </div>
                <button @click="login" type="submit" class="btn btn-primary">Login</button>
            </form>
            </div>
        </div> -->
        <div class="page-header header-filter" style="background-image: url('/material-kit/img/scott-webb-57628-unsplash.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                    <div class="card card-login">
                        <form class="form">
                        <div class="text-center">
                            <h4 class="card-title">Login</h4>
                            <div style="height: 20px;"></div>
                            <div style="height: 20px;"></div>
                        </div>
                        
                        <p class="text-danger text-center" v-if="error != ''">*{{ error }}</p>
                        
                        <div class="card-body">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                    <i class="material-icons">mail</i>
                                    </span>
                                </div>
                                <input v-model="email" type="email" class="form-control" placeholder="Email Or Phone...">
                            </div>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="material-icons">lock_outline</i>
                                </span>
                            </div>
                            <input v-model="password" type="password" class="form-control" placeholder="Password...">
                            </div>
                        </div>
                            <div style="height: 20px;"></div>

                        <div class="footer text-center">
                            <a href="javascript:void(0)" @click="login" class="btn btn-primary btn-link btn-wd btn-lg">Login</a>
                        </div>
                        <p style="cursor: pointer" @click="$router.push('register')" class="description text-center">New user? Register</p>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: '',
            password: '',
            error: ''
        }
    },
    methods: {
        login() {
            axios.post('/api/login', {
                email: this.email,
                password: this.password
            })
            .then(response => {
                if(response.data.error) {
                    this.error = response.data.error
                } else if(response.data.token) {
                    toastr.success('Log in successful')
                    this.$cookies.set('token', response.data.token) 
                    
                    setTimeout(() => {
                        window.location.href = '/dashboard'
                    }, 1000);

                }
            })
            .catch(error => {
                console.log(error)
            })
        }
    }
}
</script>

<style scoped>
  .page-header>.container {
    padding-top: 14vh;
    padding-bottom: 20px;
  }
</style>
