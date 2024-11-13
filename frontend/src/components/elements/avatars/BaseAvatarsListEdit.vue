<template>
    <div class="c-avatars-list-edit">
        <div>
            <multiselect 
                v-model="value" 
                :options="accountsProps"
                label="username"
                placeholder="Выберите пользователя"
                track-by="username"
                :show-labels="false"
                @select="addResponsibles()"
            >
                <template slot="option" slot-scope="props">
                    <div class="option__desc">
                        <div class="c-avatar-list">
                            <span class="option__title c-avatar">{{ props.option.username.split('')[0].toUpperCase() }}</span>
                            <span class="ml-1"> {{ props.option.username }} </span>
                        </div>
                    </div>
                </template>
            </multiselect>
        </div>
    </div>  
</template>
<script>
    import { mapActions } from 'vuex'

    export default {
        name: 'BaseAvatarsListEdit',
        data(){
            return {
                name: '',
                responsibles: [],
                value: {},
            }
        },
        props: {
            accountsProps: { type: Array, default: () => [] },
            viewAccountsListProps: { type: Boolean, default: true }
        },
        emits: ['addResponsiblesEmit', 'deleteResponsibleEmit'],
        watch: {
            name: {
                handler(){
                    this.listAccount = this.accountsProps.filter( item => item.username.includes(this.name) == true )
                    if(this.name == ''){
                        this.listAccount = this.accountsProps
                    }
                }
            }
        },
        methods: {
            ...mapActions({
                accountsCompanySet: 'accountsCompanySetActions'
            }),
            addResponsibles(){
                if( typeof this.value != 'undefined'){
                    if(this.responsibles.filter(item => item.id ==this.value.id )?.length == 0){
                        this.responsibles.push(this.value)
                    }
                    this.listAccount = []
                    this.$emit( 'addResponsibleEmit', this.value)
                }
            },
            deleteResponsible(item){
                this.responsibles = this.responsibles.filter(element => element.id != item.id)
                this.$emit( 'deleteResponsibleEmit', item.id)
            },
            initListResponsibles(){
                this.listAccount = this.accountsProps
            },
        },
        async mounted(){
            await this.accountsCompanySet()
        }
    }
</script>

