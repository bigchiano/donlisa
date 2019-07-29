<template>
    <div>
        <div class="page-header header-filter" style="background-image: url('/material-kit/img/scott-webb-57628-unsplash.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                    <div class="card card-login">
                        <form class="form">
                        <div class="text-center">
                            <h4 class="card-title">Input your email </h4>
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
                                <input v-model="email" type="email" class="form-control" placeholder="Email...">
                            </div>
                        </div>
                            <div style="height: 20px;"></div>

                        <div class="footer text-center">
                            <a href="javascript:void(0)" @click="send" class="btn btn-primary btn-link btn-wd btn-lg">Send</a>
                        </div>
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
            error: ''
        }
    },
    methods: {
        send() {
            axios.post('/api/password-reset', {
                email: this.email
            })
            .then(response => {
                if(response.data.error) {
                    this.error = response.data.error
                } else if(response.data.success) {
                    toastr.success('Password reset link sent')

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
