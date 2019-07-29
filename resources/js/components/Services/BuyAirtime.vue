<template>
    <div>
        <div class="page-header header-filter" style="background-image: url('/material-kit/img/scott-webb-57628-unsplash.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 ml-auto mr-auto">
                        <div class="card">
                            <div class="card-body">

                                <div class="col-md-8 ml-auto mr-auto">
                                <h4 class="card-title"> Airtime </h4>
                                <div style="height: 20px;"></div>
                                    <form @submit.prevent="send">
                                        <div class="form-group">
                                            <label for="network">Network</label>
                                            <select v-model="network" class="form-control selectpicker" data-style="btn btn-link" id="network">
                                                <option>MTN</option>
                                                <option>Glo</option>
                                                <option>Etisalat</option>
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
            network: 'Select network',
            amount: '',
            phone: '',
            hash: '',
            ref: this.genRef()
        }
    },
    methods: {
        send() {
            $('.transaction_loader').removeClass('hide')
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
                $('.transaction_loader').addClass('hide')
                if(response.data.success) {
                    this.payWithPaystack(this.amount, this.genRef(), 'airtime', this.ref)
                } else {
                    toastr.info('Something went wrong please try again')
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
                $('.transaction_loader').addClass('hide')
                if(response.data.order) {
                    // save transaction as delivered
                    axios.post('/api/buy_airtime/vend/'+this.ref)
                    .then(response => {
                        if(response.data.success) {  
                            // transaction has been completed
                            toastr.success('Transaction successful')
                            this.amount = ''
                            this.phone = ''
                        } else {
                            toastr.info('something went wrong')
                        }
                    })
                } else {
                    let msg = 'Something went wrong!!'
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
    created() {
        EventBus.$on('paid', (service) => {
            if(service == 'airtime') {   
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

