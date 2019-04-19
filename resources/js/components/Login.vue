<template>
    <div>
        <div class="row">
            <div class="col-md-6 text-align">
            <form @submit.prevent="login" class="form">
                <h4 class="card-title">Login</h4>


                <div class="form-group label-floating">
                <label class="control-label">Email/Phone</label>
                    <input v-model="email" type="text" class="form-control" />
                </div>

                <div class="form-group label-floating">
                <label class="control-label">Password</label>
                    <input v-model="password" type="password" class="form-control" />
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
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
            password: ''
        }
    },
    methods: {
        login() {
            axios.post(this.url + '/api/login', {
                email: this.email,
                password: this.password
            })
            .then(response => {
                if(response.data.token) {
                    this.$cookies.set('token', response.data.token) 
                    window.location.href = '/dashboard'

                }
            })
        }
    }
}
</script>

<style>

</style>
