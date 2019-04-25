<template>
    <div>
        <div class="row">
            <div class="col-md-12">
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

                    <button type="button" @click="checkCard" class="btn btn-primary pull-left">Submit</button>
                </form>
            </div>

             <div class="col-md-5" v-if="smart_card_info != ''">
               <div class="card card-nav-tabs" style="max-width: 20rem;">
                    <div style="font-weight: bold;padding: 10px;">
                        Smart card info info
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><b>Customer name:</b> {{smart_card_info.customer}}</li>
                        <li class="list-group-item"><b>Customer number:</b> {{ smart_card_info.customer_number }}</li>
                        <li class="list-group-item"><b>Type:</b>  {{ smart_card_info.type }}</li>


                        <li class="list-group-item" v-if="tv_network == 'StartTimes'"> <b>Amount:</b> ₦{{ amount }}</li>
                        <li class="list-group-item" v-else> <b>Amount:</b> ₦{{ bundle.price }}</li>
                    </ul>
                    <button type="button" @click="send" class="btn btn-primary pull-left">Proceed</button>
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
            this.bundles = ''
            this.bundle = ''
            axios.get('api/check_tv_bundles_list/response_format=json&tv_network='+this.tv_network)
            .then(response => {
                this.bundles = response.data.bundles
                if(response.data.bundles && response.data.bundles.length > 0) {
                    this.bundle = response.data.bundles[0]
                } else {
                    toastr.info('Service temporary unavailable')
                }
                
            })
        },
        checkCard() {
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
                if(response.access_token != '') {
                    this.smart_card_info = response.data
                } 
            })
        },
        send() {
            this.payWithPaystack(this.bundle.price, this.genRef())
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
                console.log(response)
            })
            .catch(errors => {
                console.log(errors)
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
        EventBus.$on('paid', (payload) => {
            if(payload.service == 'tv') {   
                this.vend()
            }
        })
    }
}
</script>

<style>

</style>