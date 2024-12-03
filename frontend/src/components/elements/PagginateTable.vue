<template>
    <div class="c-default-table__footer"> 
        <div class="c-pagginate-limit">
            <div class="c-pagginate-limit">
                <span>
                    Отображать по   
                    <b-form-select 
                        @change="updateLimit" 
                        v-model="limit" 
                        :options="limits"
                    >
                    </b-form-select>
                </span>
            </div>
        </div>
        <div v-show="optionsProps.pagginate.pages" class="c-pagginate-select">
            <button @click="backPage()">
                <svg width="5" height="8" ><use xlink:href="@/assets/icons/sprite.svg#chevron-left-outline"></use></svg>
                Назад
            </button>
            <b-form-select 
                v-model="page" 
                :options="pagesOptionsProps"
                @change="updatePageSelection"
            >
            </b-form-select>
            <span class="px-2 mt-2"> {{ optionsProps.pagginate.pages }}</span>
            <button @click="nextPage()">
                Вперед
                <svg width="5" height="8" ><use xlink:href="@/assets/icons/sprite.svg#chevron-right-outline"></use></svg>
            </button>
        </div> 
    </div>
</template>

<script>
    import store from '@/store'

    export default {
        name: 'PagginateTable',
        data() {
            return {
                page: 1,
                limit: 10,
                limits: [
                    { value: 5, text: '5' },
                    { value: 10, text: '10' },
                    { value: 25, text: '25' },
                    { value: 50, text: '50' },
                    { value: 100, text: '100' },
                ],
            }
        },
        props: {
            optionsProps: { type: Object, default: () => {} },
            pagesOptionsProps: { type: Array, default: () => []},
            setListActionsNameProps: { type: String, default: '' },
            setOptionsActionsNameProps: { type: String, default: '' },
        },
        mounted() {
          this.limit = this.optionsProps.pagginate.limit
        },
        methods: {
            async updateLimit(){
                let options = this.optionsProps

                options.pagginate.limit = this.limit
                
                await store.dispatch(this.setOptionsActionsNameProps, options)

                await store.dispatch(this.setListActionsNameProps, null)

                this.pages = this.optionsProps.pagginate.pages 
                this.page = this.optionsProps.pagginate.page  
            },
            async updatePageSelection(){
                let options = this.optionsProps
                options.pagginate.page = this.page
                options.pagginate.pages = this.pages

                await store.dispatch(this.setOptionsActionsNameProps, options)

                this.page = this.optionsProps.pagginate.page
                this.pages = this.optionsProps.pagginate.pages 

                await store.dispatch(this.setListActionsNameProps, null)
            },
            async backPage(){
                let options = this.optionsProps
                if(options.pagginate.page > 1){
                    options.pagginate.page = this.page - 1
                    store.dispatch(this.setOptionsActionsNameProps, options)

                    await store.dispatch(this.setListActionsNameProps, null)
                    this.pages = this.optionsProps.pagginate.pages 
                    this.page = this.optionsProps.pagginate.page
                }
            },
            async nextPage(){
                let options = this.optionsProps
                if(options.pagginate.page < options.pagginate.pages){
                    options.pagginate.page = this.page + 1
                    store.dispatch(this.setOptionsActionsNameProps, options)

                    await store.dispatch(this.setListActionsNameProps, null)
                    this.pages = this.optionsProps.pagginate.pages
                    this.page = this.optionsProps.pagginate.page
                }
            },
        }
    }
</script>