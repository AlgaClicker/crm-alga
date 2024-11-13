<<<<<<< HEAD
<template>
    <div class="c-filter"> 
        <date-picker
            class='ml-1' 
            v-model="dateFilter"
            range
        ></date-picker> 
    </div>
</template>

<script>
    import store from '@/store'

    export default {
        name: 'FilterDate',
        data() {
            return {
                dateFilter: null,
            }
        },
        props: {
            setListActionsNameProps: { type: String, default: '' },
            setDateFilterActionsNameProps: { type: String, default: () => "" },
        },
        watch: {
            'dateFilter': {
                async handler() {
                    if(this.dateFilter[0] != null){
                        let created_at = {}
                        created_at.start = new Date(this.dateFilter[0]).toLocaleString().split(',')[0].split('.').reverse().join('-'),
                        created_at.end = new Date(this.dateFilter[1]).toLocaleString().split(',')[0].split('.').reverse().join('-')
                        store.dispatch(this.setDateFilterActionsNameProps, created_at)
                        store.dispatch(this.setListActionsNameProps)
                    }else{
                        store.dispatch(this.setDateFilterActionsNameProps, { start: '', end: '' })
                        store.dispatch(this.setListActionsNameProps)
                    }
                }
            }
        }, 
        methods: {

        },
    }
=======
<template>
    <div class="c-filter"> 
        <date-picker
            class='ml-1' 
            v-model="dateFilter"
            range
        ></date-picker> 
    </div>
</template>

<script>
    import store from '@/store'

    export default {
        name: 'FilterDate',
        data() {
            return {
                dateFilter: null,
            }
        },
        props: {
            setListActionsNameProps: { type: String, default: '' },
            setDateFilterActionsNameProps: { type: String, default: () => "" },
        },
        watch: {
            'dateFilter': {
                async handler() {
                    if(this.dateFilter[0] != null){
                        let created_at = {}

                        //YYYY-MM-DD

                        created_at.start = new Date(this.dateFilter[0]).toLocaleString().split(',')[0].split('.').reverse().join('-'),
                        created_at.end = new Date(this.dateFilter[1]).toLocaleString().split(',')[0].split('.').reverse().join('-')

                        // YYYY-MM-DDThh:mm:ss±hh


                        //created_at.start = new Date(this.dateFilter[0]).toLocaleString().toString("yyyy-MM-dd'T'HH:mm:ss");
                        //created_at.end = new Date(this.dateFilter[1]).toLocaleString().toString("yyyy-MM-dd'T'HH:mm:ss");

                        store.dispatch(this.setDateFilterActionsNameProps, created_at)
                        store.dispatch(this.setListActionsNameProps)
                    }else{
                        store.dispatch(this.setDateFilterActionsNameProps, { start: '', end: '' })
                        store.dispatch(this.setListActionsNameProps)
                    }
                }
            }
        }, 
        methods: {

        },
    }
>>>>>>> origin/feature/f_requisitions_master
</script>