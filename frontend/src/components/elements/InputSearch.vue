<template>
    <div class="input-search">
        <base-icon iconProps="search" sizeProps="md" />
        <input
            v-model="valSearch"
            placeholder="поиск по названию"
        >
    </div>
</template>

<script>
    import store from '@/store'
    import BaseIcon from "@/components/elements/BaseIcon"

    export default {
        name: 'InputSearch',
        data(){
            return {
                valSearch: null
            }
        },
        props: {
            searchActionsNameProps: { type: String, default: '' },
            setListActionsNameProps: { type: String, default: '' },
        },
        components: {
            BaseIcon,
        },
        watch: {
            'valSearch': {
                async handler() {
                    if(this.valSearch == ''){
                        await store.dispatch(this.setListActionsNameProps)
                    }else{
                        if(this.valSearch.split('').length > 2){
                            await store.dispatch(this.searchActionsNameProps, this.valSearch)
                        }
                    }

                }
            }
        }
    }
</script>

<style lang="scss">

    .input-search{
        display: flex;
        align-items: center;   
        input{
            padding: 0.4rem;
            width: 280px;
            border: 1px solid #e5e4e6;
            border-radius: 4px;
            padding-left: 2rem;
            font-size: 0.9rem;
            color: #343243;
        }
        input:focus {
            outline: none;
            border: 1px solid #ecebed;
        }
        svg{
            position: relative;
            margin-top: -2px;
            margin-right: -26px;
            margin-left: 4px;
            fill: #9ca1ac;
        }
    }
</style>