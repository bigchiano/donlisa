<template>
<div>
    <div class="page-header header-filter" style="background-image: url('/material-kit/img/scott-webb-57628-unsplash.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                    <div class="card card-login">
                        <form class="form" method="" action="">
                        <div class="text-center">
                            <h4 class="card-title">Register</h4>
                            <div style="height: 20px;"></div>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="material-icons">face</i>
                                </span>
                            </div>
                            <input v-model="name" type="text" class="form-control" placeholder="Full Name...">
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                    <i class="material-icons">mail</i>
                                    </span>
                                </div>
                                <input v-model="email" type="email" class="form-control" placeholder="Email...">
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                    <i class="material-icons">phone</i>
                                    </span>
                                </div>
                                <input v-model="phone" type="number" class="form-control" placeholder="Phone Number...">
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
                        <div class="footer text-center">
                            <a href="javascript:void(0)" @click="create" class="btn btn-primary btn-link btn-wd btn-lg">Register</a>
                        </div>
                        <p style="cursor: pointer" @click="$router.push('login')" class="description text-center">Have an account? Login</p>
                        
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
            name: '',
            email: '',
            phone: '',
            password: '',
            errors: {}
        }
    },
    methods: {
        create() {
            axios.post('/api/register', {
                name: this.name,
                email: this.email,
                phone: this.phone,
                password: this.password
            })
            .then(response => {
                if(response.data.errors) {
                    this.errors = {}
                    // toastr.warning('Please fill out correct info')
                    let errors = Object.entries(response.data.errors)
                    for (const [key, value] of errors) {
                        // this.errors[key] = value[0]
                        toastr.error(value)
                    }
 
                } else if(response.data.token) {
                    toastr.success('You have been registered successfully')
                    this.$cookies.set('token', response.data.token)
                    
                    setTimeout(() => {
                        window.location.href = '/dashboard'
                    }, 3000);
                } else {
                    toastr.error('something went wrong, please try again')
                    console.log(response)
                }

            })
            .catch(errors => {
                console.log(errors)
            })
        }
    }
}
</script>

<style scoped>
  /* .page-header>.container {
    padding-top: 14vh;
    padding-bottom: 20px;
  } */
</style>

