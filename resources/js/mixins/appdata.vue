<script>
import { EventBus } from '../Event';

    export default {
        data() {
            return {
                auth: false,
                token: $cookies.get('token'),
                user: $cookies.get('user'),
                vendor_code: '1904431C11',
                private_key: '9d63c547ed81e661a70ebea31b896ed0ed0b48d93360684ba26f8b1bd9eeb8480a05addd88359eb9421ed97ca66b9cc9cafe58dc3d05b4946bb2f657c3d2d1f9',
                public_key: 'ffadf646a1a68312cda497c373bbbbc9',
                // test details
                // private_key: '5a52a78624f90ea38c277a7164458d91b15dcd494cc3fdd5508f4523e818ab325e91ba15b0ef485bb7fa62d699a7b4f409cf963574ecac8632149826277d8960',
                // public_key: '444c734e90cfc2448c9bc241b09a530c'
            }
        },
        methods: {
            genHash(combined_string) {
                let encrypted = CryptoJS.HmacSHA1(combined_string, this.private_key)
                return encrypted.toString()
            },
            genRef() {
                return Math.random().toString().slice(2,14);
            },
            checkuser() {
                if(this.token != null) {
                    axios.get('/api/user', {
                        headers: {'Authorization': `Bearer ${this.token}`}
                    })
                    .then(response => {
                        $cookies.set('user', response.data)
                        this.user = response.data
                        this.auth = true
                    })
                } else {
                    this.user = ''
                    this.token = ''
                    $cookies.remove('user')
                    $cookies.remove('token')
                }
            },
            logout() {
                toastr.success('Logged out successful')
                this.auth = ''
                this.user = '' 
                this.token = ''
                $cookies.remove('user')
                $cookies.remove('token')
                setTimeout(() => {
                    window.location.href = '/'
                }, 1000);
            },
            payWithPaystack(amount, newref, service, transaction_id){
                var handler = PaystackPop.setup({
                key: 'pk_live_7c2833fa3f51bd7d396717f03a93a0e5c48c9e4b',
                // test key
                // key: 'pk_test_f99e840ef703e7318471e9b8af9a0a2102ee25fc',
                email: this.user.email,
                amount: amount * 100,
                currency: "NGN",
                ref: newref, // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                metadata: {
                    custom_fields: [
                        {
                            display_name: "Mobile Number",
                            variable_name: "mobile_number",
                            value: this.user.phone,
                        }
                    ]
                },
                callback: function(response){
                    // display loading screen
                    $('.transaction_loader').removeClass('hide')

                    // user is buying airtime
                    if(service == 'airtime') {
                        axios.post('api/buy_airtime/paid/'+transaction_id)
                        .then(response => {
                            if(response.data.success) {
                                EventBus.$emit('paid', service)
                            }
                        })
                        .catch(errors => {
                            console.log(errors)
                        })
                    }

                    // user is buying data
                    if(service == 'data') {
                        axios.post('api/buy_data/paid/'+transaction_id)
                        .then(response => {
                            if(response.data.success) {
                                EventBus.$emit('paid', service)
                            }
                        })
                        .catch(errors => {
                            console.log(errors)
                        })
                    }

                    // user is buying electricity
                    if(service == 'power') {
                        axios.post('api/buy_power/paid/'+transaction_id)
                        .then(response => {
                            if(response.data.success) {
                                EventBus.$emit('paid', service)
                            }
                        })
                        .catch(errors => {
                            console.log(errors.response)
                        })
                    }

                    // user is paying for tv subscriptions
                    if(service == 'tv') {
                        axios.post('api/buy_tv/paid/'+transaction_id)
                        .then(response => {
                            if(response.data.success) {
                                EventBus.$emit('paid', service)
                            }
                        })
                        .catch(errors => {
                            console.log(errors)
                        })
                    }
                },
                onClose: function(){
                    $('.transaction_loader').addClass('hide')
                    toastr.info('Transaction canceled')
                    EventBus.$emit('canceled', service)
                }
                });
                handler.openIframe();
            }
        },
        created() {
            this.checkuser()
        }
    }
</script>