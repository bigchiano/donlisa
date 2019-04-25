<template>
    <div>
        <div class="row">
            <div class="col-md-12">
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
               <div class="card card-nav-tabs" style="max-width: 20rem;">
                    <div style="font-weight: bold;padding: 10px;">
                        Meter info
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><b>Name:</b> {{meterInfo.customer.name}}</li>
                        <li class="list-group-item"><b>Address:</b> {{ meterInfo.customer.address }}</li>
                        <li class="list-group-item"><b>Minimum Pay:</b>  ₦{{ meterInfo.customer.minimumAmount }}</li>
                        <li class="list-group-item"> <b>Amount:</b> ₦{{ amount }}</li>
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
            meterNumber: '',
            amount: '',
            bundle: '',
            bundles: '',
            hash: '',
            meterInfo: '',
            ref: this.genRef()
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

        EventBus.$on('paid', (payload) => {
            if(payload.service == 'power') {   
                this.vend()
            }
        })
    },
    methods: {
        getMeterInfo() {
            this.meterInfo = ''
            let combined_string = this.vendor_code +"|"+ this.ref
            combined_string += "|"+this.meterNumber +"|" + this.bundle.code +"|" + this.public_key
           
            this.hash = this.genHash(combined_string)
            // remove vue observer and get plain object
            let disco = this.bundle
            let param = 'vendor_code='+this.vendor_code+'&meter='+this.meterNumber
            param += '&reference_id='+this.ref+'&disco='+disco.code+'&hash='+this.hash+'&response_format=json'

            axios.get('api/get_meter_info/'+param)
            .then(response => {
                console.log(response)
                if(response.data.access_token) {
                    this.meterInfo = response.data
                } else {
                    console.log(response)
                }
            })
            .catch(errors => {
                console.log(errors)
            })
        },
        send() {
            // confirm meter details and proceed to payment
            this.payWithPaystack(this.amount, this.genRef())
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

           axios.get('api/vend_power/'+ param)
            .then(response => {
                console.log(response)
            })
            .catch(errors => {
                console.log(errors)
            })
        }
    }
}
</script>

<style>

</style>

