<template>
    <div>
        <div class="page-header header-filter" style="background-image: url('/material-kit/img/scott-webb-57628-unsplash.jpg')">
            <div class="container">
                <div class="row">
                <div class="col-md-8 ml-auto mr-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"> Tv </h4>
                            <div style="height: 20px;"></div>

                            <div class="row">
                                <div class="col-md-7">
                                    <form>
                                        <div class="form-group">
                                            <label for="network">Tv Network</label>
                                            <select @change="checkList" v-model="tv_network" class="form-control selectpicker" data-style="btn btn-link" id="network">
                                                <option>DSTV</option>
                                                <option>GOTV</option>
                                                <option>StarTimes</option>
                                            </select>
                                        </div>
                                        <div v-if="bundles != '' && tv_network != 'StarTimes'">
                                            <div class="form-group">
                                                <label for="network">Select package</label>
                                                <select v-model="bundle" @change="amount = bundle.price" class="form-control selectpicker" data-style="btn btn-link" id="network">
                                                    <option :value="bundle" v-for="bundle in bundles" :key="bundle.index"> {{bundle.title}} <span>&#8358;</span>{{ bundle.price }} </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div v-if="tv_network == 'StarTimes'">
                                            <label for="amount">Amount</label>
                                            <input v-model="amount" type="number" class="form-control" id="amount">
                                        </div>

                                        <div class="form-group">
                                            <label for="card_no">Smart Card Number</label>
                                            <input v-model="card_number" type="number" class="form-control" id="card_no">
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">Phone number</label>
                                            <input v-model="phone" type="number" class="form-control" id="phone">
                                        </div>

                                        <button type="button" @click="checkCard" class="btn btn-primary pull-left">Check card details</button>
                                    </form>
                                </div>
        

                                <div class="col-md-5" v-if="smart_card_info != ''">
                                    <div class="card card-nav-tabs" style="max-width: 20rem;">
                                        <h4 class="text-center"> Smart card info info </h4>
                                        <hr>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><b>Customer name:</b> {{smart_card_info.customer}}</li>
                                            <li class="list-group-item"><b>Customer number:</b> {{ smart_card_info.customer_number }}</li>
                                            <li class="list-group-item"><b>Type:</b>  {{ smart_card_info.type }}</li>


                                            <li class="list-group-item" v-if="tv_network == 'StartTimes'"> <b>Amount:</b> ₦{{ amount }}</li>
                                            <li class="list-group-item" v-else> <b>Amount:</b> ₦{{ bundle.price }}</li>
                                        </ul>
                                        <button type="button" @click="send" class="btn btn-primary btn-link">Proceed</button>
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
            tv_network: '',
            bundles: '',
            bundle: '',
            phone: '',
            amount: '',
            card_number: '',
            hash: '',
            smart_card_info: '',
            ref: this.genRef()
            
        }
    },
    methods: {
        checkList() {
            $('.transaction_loader').removeClass('hide')
            this.bundles = ''
            this.bundle = ''
            axios.get('api/check_tv_bundles_list/response_format=json&tv_network='+this.tv_network)
            .then(response => {
                $('.transaction_loader').addClass('hide')
                this.bundles = response.data.bundles
                if(response.data.bundles && response.data.bundles.length > 0) {
                    this.bundle = response.data.bundles[0]
                } else {
                    toastr.info('Service temporary unavailable')
                }
                
            })
        },
        checkCard() {
            if(this.card_number != '' && this.tv_network != '' && this.phone != '') {
                $('.transaction_loader').removeClass('hide')
                this.smart_card_info = ''
                let combined_string = this.vendor_code + "|" + this.ref + "|" + this.tv_network 
                combined_string += "|" + this.card_number + "|" + this.bundle.code + "|" + this.public_key

                this.hash = this.genHash(combined_string)
                //get meter info
                let param = 'vendor_code='+ this.vendor_code +'&smartcard_number='+ this.card_number 
                param += '&service_code='+ this.bundle.code  +'&reference_id='+ this.ref 
                param += '&tv_network=' + this.tv_network + '&tv_amount=' + this.amount +'&response_format=json&hash='+ this.hash

                axios.get('api/check_tv_card/'+param)
                .then(response => {
                    $('.transaction_loader').addClass('hide')
                    if(response.access_token != '') {
                        this.smart_card_info = response.data
                    } 
                })
            }
        },
        send() {
            $('.transaction_loader').removeClass('hide')
            // user confrimed smart card details, proceed to payment
            axios.post('/api/pay_tv', {
                amount: this.bundle.price,
                package: this.bundle.title,
                provider: this.tv_network,
                smart_card_no: this.card_number,
                transaction_id: this.ref
            })
            .then(response => {
                $('.transaction_loader').addClass('hide')
                if(response.data.success) {
                    this.payWithPaystack(this.bundle.price, this.ref, 'tv', this.ref)
                } else {
                    toastr.info('Something went wrong please try again')
                }
            })
        },
        vend() {
            let combined_string = this.vendor_code + "|" + this.ref + "|"+ this.card_number
            combined_string += "|"+ this.tv_network + "|" + this.bundle.code + "|" 
            combined_string += this.smart_card_info.access_token +"|"+ this.public_key

            // hash combined_string 
            let hash = this.genHash(combined_string)

            let param = 'vendor_code='+ this.vendor_code + '&smartcard_number=' + this.card_number 
            param += '&reference_id=' + this.ref + '&access_token='+ this.smart_card_info.access_token 
            param += '&tv_network=' + this.tv_network + '&service_code=' + this.bundle.code 
            param += '&phone='+ this.user.phone + '&email='+ this.user.email 
            param += '&hash='+ hash + '&response_format=json' + '&startimes_amount' + this.amount


            axios.get('api/vend_tv/' + param)
            .then(response => {
                $('.transaction_loader').addClass('hide')
                if(response.data.order) {
                    // save transaction as delivered
                    axios.post('/api/buy_data/vend/'+this.ref)
                    .then(response => {
                        if(response.data.success) {  
                            // transaction has been completed
                            toastr.success('Transaction successful')
                            this.smart_card_info = ''
                        } else {
                            toastr.info('something went wrong')
                        }
                        this.ref = this.genRef()
                    })
                } else {
                    let msg = 'Something went wrong, we sincerely apologize'
                    toastr.info(msg)
                }
                this.ref = this.genRef()
            })
            .catch(errors => {
                $('.transaction_loader').addClass('hide')
                toastr.info('Something went wrong')
                console.log(errors)
                this.ref = this.genRef()
            })
        }
    },
    watch: {
        tv_network: function() {
            this.bundle = ''
            this.amount = ''
        }
    },
    created() {
        EventBus.$on('paid', (service) => {
            if(service == 'tv') {   
                this.vend()
            }
        })
        EventBus.$on('canceled', (service) => {
            if(service == 'tv') {   
                this.ref = this.genRef()
            }
        })
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