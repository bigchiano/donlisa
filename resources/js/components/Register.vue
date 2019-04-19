<template>
<div>
    <div class="row">
        <div class="col-md-6">
        <form class="form" @submit.prevent="create">
            <h4 class="card-title">Register</h4>
            <div style="height: 10px;"></div>

            <div class="form-group label-floating">
            <label class="control-label">Full name</label>
                <input type="text" v-model="name" class="form-control" required />
                
            </div>

            <div class="form-group label-floating">
            <label class="control-label">Phone</label>
                <input type="number" v-model="phone" value="Error Input" required class="form-control" />
                
            </div>

            <div class="form-group label-floating">
            <label class="control-label">Email</label>
                <input type="email" v-model="email" required class="form-control" />
                
            </div>

            <div class="form-group label-floating">
            <label class="control-label">Password</label>
                <input type="password" v-model="password" required min="6" class="form-control" />
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
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
            password: ''
        }
    },
    methods: {
        create() {
            axios.post(this.url + '/api/register', {
                name: this.name,
                email: this.email,
                phone: this.phone,
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

<style scoped>

</style>

