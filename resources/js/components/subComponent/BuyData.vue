<template>
    <div>
        <div class="row">
        <div class="col-md-12">
            <form @submit.prevent="send">
                <div class="form-group">
                    <label for="network">Network</label>
                    <select @change="checkBundleList" v-model="network" class="form-control selectpicker" data-style="btn btn-link" id="network">
                        <option> Select network </option>
                        <option>MTN</option>
                        <option>Airtel</option>
                        <option>9Mobile</option>
                        <option>Smile</option>
                        <option>Spectranet</option>
                    </select>
                </div>
                <div v-if="bundles != ''" class="form-group">
                    <label for="network">Select bundle</label>
                    <select v-model="bundle" class="form-control selectpicker" data-style="btn btn-link" id="network">
                        <option selected="selected"> Selected </option>
                        <option :value="bundle" v-for="bundle in bundles" :key="bundle.index"> {{bundle.title}} </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phone">Device number</label>
                    <input v-model="phone" type="number" class="form-control" id="phone">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>

            </form>
        </div>
        </div>
    </div>
</template>

<script>
import { EventBus } from '../../Event.js'

export default {
    data() {
        return {
            network: '',
            bundle: '',
            phone: '',
            bundles: '',
            amount: '',
            hash: '',
            ref: this.genRef()
        }
    },
    methods: {
        checkBundleList() {
            this.bundle = ''
            this.bundles = ''
            axios.get('api/check_data_bundles_list/response_format=json&data_network='+this.network)
            .then(response => {
                this.bundles = response.data.bundles
                this.bundle = this.bundles[0]
            })
        },
        send() {
            let combined_string = this.vendor_code + "|"+this.ref+"|"+ this.phone+"|"+this.network 
            combined_string += "|" +this.bundle.code + "|" +this.public_key
           
            this.hash = this.genHash(combined_string)
            
            // save details
            axios.post('/api/buy_data', {
                network: this.network,
                phone_to: this.phone,
                description: this.bundle.title,
                amount: this.bundle.price,
                transaction_id: this.ref
            })
            .then(response => {
                if(response.data.success) {
                    this.payWithPaystack(this.bundle.price, this.genRef(), 'data', this.ref)
                } else {
                    console.log(response.data)
                }
            })
        },
        vend() {
            let param = 'vendor_code='+ this.vendor_code +'&vtu_network=' + this.network
            param += '&vtu_data='+ this.bundle.code + '&vtu_number=' + this.phone 
            param += '&vtu_email=' + this.user.email + '&reference_id='+ this.ref 
            param += '&response_format=json&hash=' + this.hash
            axios.get('api/vend_data/'+ param)
            .then(response => {
                console.log(response)
            })
            .catch(errors => {
                console.log(errors)
            })
        }
    },
    created() {
        EventBus.$on('paid', (payload) => {
            if(payload.service == 'data') {   
                this.vend()
            }
        })
    }
}
</script>

<style>

</style>

