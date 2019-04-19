<script>
    export default {
        data() {
            return {
                auth: false,
                token: $cookies.get('token'),
                user: $cookies.get('user'),
                url: 'http://donlisa.ly'
            }
        },
        methods: {
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
            }
        },
        created() {
            this.checkuser()
        }
    }
</script>