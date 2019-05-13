<template>
    <div>
        <div class="card-body">
            <h4 class="card-title"> Power Transactions</h4>
            
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                        <th scope="col">Disco</th>
                        <th scope="col">Recipient </th>
                        <th scope="col">Name</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Token</th>
                        <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody v-if="transactions != ''">
                        <tr v-for="trans in transactions" :key="trans.id">
                            <td> {{trans.disco}} </td>
                            <td> {{ trans.meter_number }} </td>
                            <td> {{ trans.name }} </td>
                            <td> N{{ trans.amount }} </td>
                            <td>{{trans.meter_token}}</td>
                            <td>{{ trans.created_at }}</td>
                        </tr>
                    </tbody>

                    
                    <!-- <div class="pull-right">
                        <a href="#0" class="card-link">Prev</a>
                        <a href="#0" class="card-link">Next</a>
                    </div> -->
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            transactions: []
        }
    },
    mounted() {
        axios.post('/api/power_transactions')
        .then(response => {
            this.transactions = response.data.transactions.data
            // console.log(response)
        })
    },
    methods: {
        load() {
            //
        }
    }
}
</script>

<style>

</style>
