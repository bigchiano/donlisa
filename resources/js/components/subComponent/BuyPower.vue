<template>
    <div>
        <div class="row">
            <div class="col-md-7">
                <form @submit.prevent="">
                    <div class="form-group">
                        <label for="bundle">Select Utility</label>
                            <select v-model="utility" class="form-control selectpicker" data-style="btn btn-link" id="bundle">
                            <option v-for="bundle in bundles" :key="bundle.id">{{ bundle.code }}</option>
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
                    <button type="button" @click="retrieve" class="btn btn-primary pull-left">Verify meter number</button>
                </form>
            </div>
            
            <div class="col-md-5" v-if="meterInfo != ''">
               <div class="card card-nav-tabs" style="width: 20rem;">
                    <div style="font-weight: bold;padding: 10px;">
                        Meter info
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Cras justo odio</li>
                        <li class="list-group-item">Dapibus ac facilisis in</li>
                        <li class="list-group-item">Vestibulum at eros</li>
                    </ul>
                    <button type="button" class="btn btn-primary pull-left">Make payment</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            utility: '',
            meterNumber: '',
            amount: '',
            bundles: '',
            hash: '',
            meterInfo: '',
            ref: this.genRef()
        }
    },
    mounted() {
        axios.get('https://irecharge.com.ng/pwr_api_sandbox/v2/get_electric_disco.php?response_format=json')
        .then(response => {
            this.bundles = response.data.bundles
        })
    },
    methods: {
        retrieve() {
            let public_key = '444c734e90cfc2448c9bc241b09a530c'
            let combined_string = this.vendor_code + this.ref + this.meterNumber + this.utility + public_key
           
            axios.post(this.url + '/api/getHash/' + combined_string)
            .then(response => {
                this.hash = response.data
            })
        }
    },
    watch: {
        hash: function() {
            let param = 'vendor_code='+ this.vendor_code +'&meter='+ this.meterNumber +'&reference_id='+ this.genRef() +'&disco='+ this.utility +'&response_format=json&hash='+ this.hash
            if(this.hash != '') {
                axios.get('https://irecharge.com.ng/pwr_api_sandbox/v2/get_meter_info.php/'+param)
                .then(response => {
                    console.log(response)
                    this.payWithPaystack(this.amount, this.ref)
                })
            }
        }
    }
}
</script>

<style>

</style>

