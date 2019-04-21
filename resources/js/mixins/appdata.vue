<script>
    export default {
        data() {
            return {
                auth: false,
                token: $cookies.get('token'),
                user: $cookies.get('user'),
                url: 'http://donlisa.ly',
                vendor_code: '1904431C11'
            }
        },
        methods: {
            genHash() {
                return randomstring.generate()
            },
            genRef() {
                return randomstring.generate(12)
            },
            checkuser() {
                if(this.token != null && this.user == null) {
                    axios.get(this.url + '/api/user', {
                        headers: {'Authorization': `Bearer ${this.token}`}
                    })
                    .then(response => {
                        $cookies.set('user', response.data)
                        this.user = response.data
                        this.auth = true
                    })
                } else if (this.user != null) {
                    this.user = this.user
                    this.auth = true
                } else {
                    this.user = ''
                    this.token = ''
                    $cookies.remove('user')
                    $cookies.remove('token')
                }
            },
            logout() {
                this.auth = ''
                this.user = '' 
                this.token = ''
                $cookies.remove('user')
                $cookies.remove('token')
                window.location.href = '/'
            },
            payWithPaystack(amount, newref){
                var handler = PaystackPop.setup({
                key: 'pk_live_7c2833fa3f51bd7d396717f03a93a0e5c48c9e4b',
                email: this.user.email,
                amount: amount * 100,
                currency: "NGN",
                ref: newref, // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                metadata: {
                    custom_fields: [
                        {
                            display_name: "Mobile Number",
                            variable_name: "mobile_number",
                            value: "+2348012345678"
                        }
                    ]
                },
                callback: function(response){
                    this.save()
                },
                onClose: function(){
                    alert('window closed');
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