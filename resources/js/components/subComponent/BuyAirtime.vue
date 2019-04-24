<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <form @submit.prevent="send">
                    <div class="form-group">
                        <label for="network">Network</label>
                        <select v-model="network" class="form-control selectpicker" data-style="btn btn-link" id="network">
                            <option>MTN</option>
                            <option>Glo</option>
                            <option>9mobile</option>
                            <option>Airtel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input v-model="amount" type="number" class="form-control" id="amount">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone number</label>
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
            network: 'Select network',
            amount: '',
            phone: '',
            hash: '',
            ref: this.genRef()
        }
    },
    methods: {
        send() {
            // // combine strings
            let combined_string = this.vendor_code+"|"+this.ref+"|"+this.phone
            combined_string += "|"+this.network+"|"+this.amount+"|"+this.public_key
           
            // hash the combined string
            this.hash = this.genHash(combined_string)

            // save details
            axios.post('/api/buy_airtime', {
                network: this.network,
                phone_to: this.phone,
                amount: this.amount,
                transaction_id: this.ref
            })
            .then(response => {
                if(response.data.success) {
                    this.payWithPaystack(this.amount, this.genRef(), 'airtime', this.ref)
                } else {
                    console.log(response.data)
                }
            })
        },
        vend() {
            // required parameters to vend airtime
            let param = 'vendor_code='+ this.vendor_code +'&vtu_network='+ this.network 
            param += '&vtu_amount='+ this.amount + '&vtu_number=' + this.phone 
            param += '&vtu_email=' + this.user.email + '&reference_id=' + this.ref
            param += '&response_format=json&hash='+ this.hash

            axios.get('api/vend_airtime/'+param)
            .then(response => {
                axios.post('/api/buy_airtime/vend/'+this.ref)
                .then(response => {
                    // transaction has been completed
                    if(response.data.success) {
                        console.log('transaction successful')
                    }
                    console.log(response)
                })
            })
            .catch(errors => {
                console.log(errors)
            })
            
        }
    },
    created() {
        EventBus.$on('paid', (payload) => {
            if(payload.service == 'airtime') {   
                this.vend()
            }
        })
    }
}
</script>

<style>

</style>

