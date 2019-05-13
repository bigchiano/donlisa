<template>
    <div>
        
        <div class="page-header header-filter" data-parallax="true" style="background-image: url('/material-kit/img/scott-webb-57628-unsplash.jpg'); background-size: cover; background-position: top center;">
            <div class="container">
                <div class="row">

                    <!-- Power -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card card-dashboard" @click="service = 'power'">
                            <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Power</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">N{{remit.power}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="material-icons text-grey-300">wb_incandescent</i>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>

                    <!-- Airtime -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card card-dashboard" @click="service = 'airtime'">
                            <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Airtime</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">N{{remit.airtime}}</div>
                                </div>
                                <div class="col-auto">
                                <i class="material-icons text-gray-300">perm_phone_msg</i>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>

                     <!-- Data -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card card-dashboard" @click="service = 'data'">
                            <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Data</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">N{{remit.data}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="material-icons">data_usage</i>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tv -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card card-dashboard" @click="service = 'tv'">
                            <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tv</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">N{{remit.tv}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="material-icons">tv</i>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                
            </div>
        </div>

        <div class="main" v-if="service != ''">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 col-md-12 mb-4">
                        <div class="card">
                            <div v-if="service == 'power'"> <power /> </div>
                            <div v-if="service == 'data'"> <data-trans /> </div>
                            <div v-if="service == 'airtime'"> <airtime /> </div>
                            <div v-if="service == 'tv'"> <tv /> </div>
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
            service: '',
            remit: {}
        }
    },
    created() {
        axios.post('/api/remit')
        .then(response => {
            this.remit = response.data
        })
        .catch(errors => {
            console.log(errors)
        })
    }
}
</script>

<style scoped>
    .nav-pills .nav-link {
        border-radius: 4px;
    }
    .nav-link .material-icons {
        display: inline-block;
        margin-right: 10px;
    }
    .page-header {
        min-height: 100vh;
        max-height: 1000px;
        height: 100%;
    }
    .page-header>.container {
        padding-top: 20vh;
        padding-bottom: 20px;
    }
    .card-dashboard {
        margin: 0px;
        height: 100px;
        border-left: 3px solid blueviolet;
        cursor: pointer;
    }
</style>