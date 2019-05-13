<template>
    <div>
        <div class="page-header header-filter" style="background-image: url('/material-kit/img/scott-webb-57628-unsplash.jpg')">
            <div class="container">
                <div class="row">
                <div class="col-md-8 ml-auto mr-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"> Power </h4>
                            <div style="height: 20px;"></div>
                            
                            <div id="meter_token" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" style="display: none;">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLiveLabel">Meter Token</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Here is your meter token: {{ meter_token }} <br> Copy and load it into your meter, you can also see this code on your dashboard</p>
                                    </div>
                                    <!-- <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                    </div> -->
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-7">
                                    <form @submit.prevent="">
                                        <div class="form-group">
                                            <label for="bundle">Select Utility</label>
                                                <select v-model="bundle" class="form-control selectpicker" data-style="btn btn-link" id="bundle">
                                                <option :value="bundle" v-for="bundle in bundles" :key="bundle.id">{{ bundle.code }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="meterNumber">Meter Number</label>
                                            <input type="text" v-model="meterNumber" class="form-control" id="meterNumber">
                                        </div>
                                        <div class="form-group">
                                            <label for="amount">Amount</label>
                                            <input type="text" v-model="amount" class="form-control" id="amount">
                                        </div>
                                        <button type="button" @click="getMeterInfo" class="btn btn-primary pull-left">Verify meter number</button>
                                    </form>
                                </div>
        
                                <div class="col-md-5" v-if="meterInfo != ''">
                                    <div class="card card-nav-tabs">
                                        <h4 class="text-center">Meter Info</h4>
                                        <hr>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><b>Name:</b> {{meterInfo.customer.name}}</li>
                                            <li class="list-group-item"><b>Address:</b> {{ meterInfo.customer.address }}</li>
                                            <li class="list-group-item"><b>Minimum Pay:</b>  ₦{{ meterInfo.customer.minimumAmount }}</li>
                                            
                                            <li class="list-group-item" v-if="amount < meterInfo.customer.minimumAmount"> <b>Amount:</b> ₦{{ amount }} 
                                            <span @click="amount = meterInfo.customer.minimumAmount" style="color: red; display: block; font-weight: bold">×</span> </li>
                                            <li class="list-group-item" v-else> <b>Amount:</b> ₦{{ amount }}</li>
                                        </ul>
                                        <a href="javascript:void(0)" @click="send" class="btn btn-primary btn-link">Proceed</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { EventBus } from '../../Event.js'

export default {
    data() {
        return {
            meterNumber: '',
            amount: '',
            bundle: '',
            bundles: '',
            hash: '',
            meterInfo: '',
            ref: this.genRef(),
            meter_token: ''
        }
    },
    mounted() {
        axios.get('api/list_utilities')
        .then(response => {
            this.bundles = response.data.bundles
            if(response.data.bundles && response.data.bundles.length > 0) {
                this.bundle = response.data.bundles[0]
            } else {
                toastr.info('Service temporary unavailable')
            }
        })
        .catch(errors => {
            toastr.info('Something went wrong please try again')
        })

        EventBus.$on('paid', (service) => {
            if(service == 'power') {
                this.vend()
            }
        })
        EventBus.$on('canceled', (service) => {
            if(service == 'power') {
                this.ref = this.genRef()
            }
        })
    },
    methods: {
        getMeterInfo() {
            if(this.meterNumber != '') {
                $('.transaction_loader').removeClass('hide')
                this.meterInfo = ''
                let combined_string = this.vendor_code +"|"+ this.ref
                combined_string += "|"+this.meterNumber +"|" + this.bundle.code +"|" + this.public_key
            
                this.hash = this.genHash(combined_string)
                let disco = this.bundle
                let param = 'vendor_code='+this.vendor_code+'&meter='+this.meterNumber
                param += '&reference_id='+this.ref+'&disco='+disco.code+'&hash='+this.hash+'&response_format=json'
                
                // load meter info from irecharge apis
                axios.get('api/get_meter_info/'+param)
                .then(response => {
                    $('.transaction_loader').addClass('hide')
                    if(response.data.access_token) {
                        this.meterInfo = response.data
                        if(this.amount == '') {
                            this.amount = response.data.customer.minimumAmount
                        }

                    // when meter info wasn't loaded successfully
                    } else {
                        console.log(response)
                        toastr.info('Something went wrong, please input correct details')
                    }
                })
                .catch(errors => {
                    this.ref = this.genRef()
                    $('.transaction_loader').addClass('hide')
                    toastr.info('Something went wrong, please try again')
                    console.log(errors)
                })
            } else if(this.meterNumber == '') {
                toastr.info('Please input your meter number')
            }
        },
        send() {
            $('.transaction_loader').removeClass('hide')
            // user confirmed meter details so proceed to payment
            // save ongoing transaction data to database 
            axios.post('/api/buy_power', {
                meter_number: this.meterNumber,
                amount: this.amount,
                name: this.meterInfo.customer.name,
                address: this.meterInfo.customer.address,
                transaction_id: this.ref,
                disco: this.bundle.code
            })
            .then(response => {
                $('.transaction_loader').addClass('hide')
                // proceed to payment
                if(response.data.success) {
                    this.payWithPaystack(this.amount, this.ref, 'power', this.ref)
                } else {
                    toastr.info('Something went wrong please try again')
                }
            })
            .catch(errors => {
                $('.transaction_loader').addClass('hide')
                toastr.info('Something went wrong please try again')
                console.log(errors.response)
            })
        },
        vend() {
            // create new hash
            let combined_string = this.vendor_code +"|"+ this.ref
            combined_string += "|"+this.meterNumber +"|" + this.bundle.code +"|" +this.amount
            combined_string += "|"+ this.meterInfo.access_token + "|" + this.public_key

            let hash = this.genHash(combined_string)

            let param = 'vendor_code='+ this.vendor_code + '&meter=' + this.meterNumber 
            param += '&reference_id=' + this.ref + '&access_token='+ this.meterInfo.access_token 
            param += '&disco=' + this.bundle.code + '&amount=' + this.amount 
            param += '&phone=' + this.user.phone + '&email=' + this.user.email 
            param += '&hash=' + hash + '&response_format=json'

            // call irecharge api to buy electricity
            axios.get('api/vend_power/'+ param)
            .then(response => {
                $('.transaction_loader').addClass('hide')
                if(response.data.meter_token) {
                    this.meter_token = response.data.meter_token
                    // payment has been made successfully save details
                    axios.post('/api/buy_power/vend/'+this.ref, {
                        meter_token: this.meter_token,
                        units: response.data.units
                    })
                    .then(response => {
                        toastr.success('Transaction successful')
                        $('#meter_token').modal({
                            backdrop: false
                        })
                        this.meterInfo = ''
                    })
                } else if(response.data.message) {
                    this.meterInfo = ''
                    toastr.info(response.data.message)
                }
                Console.log(response)
                this.ref = this.genRef()
            })
            .catch(errors => {
                $('.transaction_loader').addClass('hide')
                toastr.info('Something went wrong!!')
                this.ref = this.genRef()
            })
        }
    }
}
</script>

<style>
    .page-header {
        min-height: 100vh;
        max-height: 1000px;
        height: 100%;
    }
    .page-header>.container {
        padding-top: 20vh;
        padding-bottom: 20px;
    }
</style>

