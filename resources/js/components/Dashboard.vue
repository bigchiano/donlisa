<template>
    <div>

        <!-- <div class="row">
            <div class="col-md-4">
                <ul class="nav nav-pills nav-pills-primary flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tab1" data-toggle="tab"> 
                        <i class="material-icons">wb_incandescent</i> 
                        Power 
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab2" data-toggle="tab"><i class="material-icons">perm_phone_msg</i>
                            Airtime 
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab3" data-toggle="tab"><i class="material-icons">data_usage</i>
                            Data 
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab4" data-toggle="tab"><i class="material-icons">tv</i>
                            Tv 
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-8">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1">
                    <buy-power></buy-power>
                    </div>
                    <div class="tab-pane" id="tab2">
                    <buy-airtime></buy-airtime>
                    </div>
                    <div class="tab-pane" id="tab3">
                        <buy-data></buy-data>
                    </div>
                    <div class="tab-pane" id="tab4">
                        <subscribe-tv></subscribe-tv>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="row">
            <div class="col-md-6 ml-auto mr-auto">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header card-header-primary" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h5 class="mb-0">
                            <button class="btn btn-white btn-link">
                                <i class="material-icons">wb_incandescent</i> 
                                Power
                            </button>
                        </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <!-- buy power component -->
                            <buy-power></buy-power>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header card-header-primary" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-white btn-link collapsed">
                            <i class="material-icons">perm_phone_msg</i>
                            Airtime
                            </button>
                        </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <buy-airtime></buy-airtime>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header card-header-primary" id="headingThree"  data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <h5 class="mb-0">
                            <button class="btn btn-white btn-link collapsed">
                                <i class="material-icons">data_usage</i>
                                Data
                            </button>
                        </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <buy-data></buy-data>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header card-header-primary" id="headingFour"  data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <h5 class="mb-0">
                            <button class="btn btn-white btn-link collapsed">
                                <i class="material-icons">tv</i>
                                Tv
                            </button>
                        </h5>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="card-body">
                            <subscribe-tv></subscribe-tv>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    //
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
</style>