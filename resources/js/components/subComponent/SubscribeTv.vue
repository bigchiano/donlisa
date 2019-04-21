<template>
    <div>
        <div class="row">
        <div class="col-md-8">
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
                            <option :value="bundle" v-for="bundle in bundles" :key="bundle.index"> {{bundle.title}} </option>
                        </select>
                    </div>
                    <div v-if="amount != ''">
                        <label for="amount">Amount</label>
                        <input disabled v-model="amount" type="number" class="form-control" id="amount">
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

                <button type="button" @click="retrieve" class="btn btn-primary pull-left">Submit</button>
            </form>
        </div>
        </div>
    </div>
</template>

<script>
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
            ref: this.genRef()
            
        }
    },
    methods: {
        checkList() {
            axios.get('https://irecharge.com.ng/pwr_api_sandbox/v2/get_tv_bouquet.php?response_format=json&tv_network='+this.tv_network)
            .then(response => {
                this.bundles = response.data.bundles
            })
        },
        retrieve() {
            let public_key = '444c734e90cfc2448c9bc241b09a530c'
            let combined_string = this.vendor_code + this.ref + this.tv_network + this.card_number + this.bundle.code + public_key

            axios.post(this.url + '/api/getHash/' + combined_string)
            .then(response => {
                this.hash = response.data
            })
        }
    },
    watch: {
        hash: function() {
            let param = 'vendor_code='+ this.vendor_code +'&smartcard_number='+ this.card_number +'&reference_id='+ this.genRef() +'&service_code='+ this.bundle.code + '&tv_network=' + this.tv_network +'&response_format=json&hash='+ this.hash
            if(this.hash != '') {
                axios.get('https://irecharge.com.ng/pwr_api_sandbox/v2/get_smartcard_info.php/'+param)
                .then(response => {
                    console.log(response)
                    this.payWithPaystack(this.amount, this.ref)
                })
            }
        },
        tv_network: function() {
            this.bundle = ''
            this.amount = ''
        }
    }
}
</script>

<style>

</style>

