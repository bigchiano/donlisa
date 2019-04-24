<template>
    <div>
        <div class="row">
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
                    window.location.href = '/dashboard'

                }
            })
            .catch(error => {
                console.log(error)
            })
        }
    }
}
</script>

<style>

</style>
