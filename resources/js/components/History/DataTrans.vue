<template>
    <div>
        <div class="card-body">
            <h4 class="card-title">Data Transactions</h4>
            
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                        <th scope="col">Network</th>
                        <th scope="col">Recipient </th>
                        <th scope="col">Amount</th>
                        <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody v-if="transactions != ''">
                        <tr v-for="trans in transactions" :key="trans.id">
                            <td> {{ trans.network }} </td>
                            <td> {{ trans.phone_to }} </td>
                            <td> N{{ trans.amount }} </td>
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
        axios.post('/api/data_transactions')
        .then(response => {
            this.transactions = response.data.transactions.data
            // console.log(response)
        })
    }
}
</script>

<style>

</style>
