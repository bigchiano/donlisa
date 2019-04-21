<template>
    <div>
        <div class="row">
        <div class="col-md-8">
            <form @submit.prevent="vend">
                <div class="form-group">
                    <label for="network">Network</label>
                    <select @change="checkBundleList" v-model="network" class="form-control selectpicker" data-style="btn btn-link" id="network">
                        <option> Select network </option>
                        <option>Mtn</option>
                        <option>Airtel</option>
                        <option>9Mobile</option>
                        <option>Smile</option>
                        <option>Spectranet</option>
                    </select>
                </div>
                <div v-if="bundles != ''" class="form-group">
                    <label for="network">Select bundle</label>
                    <select v-model="bundle" class="form-control selectpicker" data-style="btn btn-link" id="network">
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
            axios.get('https://irecharge.com.ng/pwr_api_sandbox/v2/get_data_bundles.php?response_format=json&data_network='+this.network)
            .then(response => {
                this.bundles = response.data.bundles
            })
        },
        vend() {
            let public_key = '444c734e90cfc2448c9bc241b09a530c'
            let combined_string = this.vendor_code + this.ref + this.phone + this.network + this.code + public_key
           
            axios.post(this.url + '/api/getHash/' + combined_string)
            .then(response => {
                this.hash = response.data
            })
        }
    },
    watch: {
        hash: function() {
            let param = 'vendor_code='+ this.vendor_code +'&vtu_network='+ this.network + '&vtu_data='+ this.bundle.code + '&vtu_number=' + this.phone + '&vtu_email=' + this.user.email + '&reference_id='+ this.genRef() +'&response_format=json&hash='+ this.hash
            if(this.hash != '') {
                axios.get('https://irecharge.com.ng/pwr_api_sandbox/v2/vend_data.php/'+ param)
                .then(response => {
                    this.amount = this.bundle.price
                    this.payWithPaystack(this.amount, this.ref)
                })
            }
        }
    }
}
</script>

<style>

</style>

