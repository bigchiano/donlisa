<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <form @submit.prevent="vend">
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
        vend() {
            let public_key = '444c734e90cfc2448c9bc241b09a530c'
            let combined_string = this.vendor_code + this.ref + this.network + this.amount + this.phone + this.user.email + public_key
           
            axios.post(this.url + '/api/getHash/' + combined_string)
            .then(response => {
                this.hash = response.data
            })
        },
        save() {
            axios.post(this.url + '/buy_airtime', {
                //
            })
        }
    },
    watch: {
        hash: function() {
            let param = 'vendor_code='+ this.vendor_code +'&vtu_network='+ this.network + '&vtu_amount='+ this.amount + '&vtu_number=' + this.phone + '&vtu_email=' + this.user.email + '&reference_id='+ this.genRef() +'&response_format=json&hash='+ this.hash

            if(this.hash != '') {
                axios.get('https://irecharge.com.ng/pwr_api_sandbox/v2/vend_airtime.php/'+param)
                .then(response => {
                    console.log(response)
                    this.hash = ''
                    this.payWithPaystack(this.amount, this.ref)
                })
            }
        }
    }
}
</script>

<style>

</style>

