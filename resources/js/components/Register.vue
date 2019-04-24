<template>
<div>
    <div class="row">
        <div class="col-md-6 ml-auto mr-auto">
        <form class="form" novalidate @submit.prevent="">
            <h4 class="card-title text-center">Register</h4>
            <div style="height: 10px;"></div>

            <div class="form-group label-floating">
            <label class="control-label">Full name <small class="text-danger">*{{ errors.name }}</small></label>
                <input type="text" v-model="name" class="form-control" />
                
            </div>

            <div class="form-group label-floating">
            <label class="control-label">Phone <small class="text-danger">*{{ errors.phone }}</small></label>
                <input type="number" v-model="phone" class="form-control" />
                
            </div>

            <div class="form-group label-floating">
            <label class="control-label">Email <small class="text-danger">*{{ errors.email }}</small></label>
                <input type="email" v-model="email" class="form-control" />
                
            </div>

            <div class="form-group label-floating">
            <label class="control-label">Password <small class="text-danger">*{{ errors.password }}</small></label>
                <input type="password" v-model="password" class="form-control" />
            </div>
            <button type="submit" @click="create" class="btn btn-primary">Register</button>
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
                    toastr.warning('Please fill out correct info')
                    let errors = Object.entries(response.data.errors)
                    for (const [key, value] of errors) {
                        this.errors[key] = value[0]
                    }
 
                }else if(response.data.token) {
                    toastr.success('You have been registered successfully')
                    this.$cookies.set('token', response.data.token)
                    window.location.href = '/dashboard'
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

</style>

