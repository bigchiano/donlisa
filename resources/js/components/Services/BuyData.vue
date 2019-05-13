<template>
    <div>
        <div class="page-header header-filter" style="background-image: url('/material-kit/img/scott-webb-57628-unsplash.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 ml-auto mr-auto">
                        <div class="card">
                            <div class="card-body">

                                <div class="col-md-8 ml-auto mr-auto">
                                <h4 class="card-title"> Data </h4>
                                <div style="height: 20px;"></div>
                                    <form @submit.prevent="send">
                                        <div class="form-group">
                                            <label for="network">Network</label>
                                            <select @change="checkBundleList" v-model="network" class="form-control selectpicker" data-style="btn btn-link" id="network">
                                                <option> Select network </option>
                                                <option>MTN</option>
                                                <option>Airtel</option>
                                                <option>Etisalat</option>
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
            $('.transaction_loader').removeClass('hide')
            this.bundle = ''
            this.bundles = ''
            // check and return list of data bundles available for the selected network
            axios.get('api/check_data_bundles_list/response_format=json&data_network='+this.network)
            .then(response => {
                $('.transaction_loader').addClass('hide')
                this.bundles = response.data.bundles
                if(response.data.bundles && response.data.bundles.length > 0) {
                    this.bundle = response.data.bundles[0]
                } else {
                    toastr.info('Service temporary unavailable')
                }
            })
            .catch(errors => {
                $('.transaction_loader').addClass('hide')
                toastr.info('Something went wrong!')
            })
        },
        send() {
            $('.transaction_loader').removeClass('hide')
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
                $('.transaction_loader').addClass('hide')
                if(response.data.success) {
                    this.payWithPaystack(this.bundle.price, this.genRef(), 'data', this.ref)
                } else {
                    console.log(response)
                    toastr.info('Something went wrong!')
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
                $('.transaction_loader').addClass('hide')
                
                axios.post('/api/buy_data/vend/'+this.ref)
                .then(response => {
                    if(response.data.success) {
                        // transaction has been completed
                        toastr.success('Transaction successful')
                        this.amount = ''
                        this.phone = ''
                    } else {
                        toastr.info('something went wrong')
                    }
                    this.ref = this.genRef()
                })
            })
            .catch(errors => {
                $('.transaction_loader').addClass('hide')
                toastr.info('something went wrong')
                console.log(errors)
                this.ref = this.genRef()
            })
        }
    },
    created() {
        EventBus.$on('paid', (service) => {
            if(service == 'data') {   
                this.vend()
            }
        })
        EventBus.$on('canceled', (service) => {
            if(service == 'airtime') {
                this.ref = this.genRef()
            }
        })
    }
}
</script>

<style>

</style>

