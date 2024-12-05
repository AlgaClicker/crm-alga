<template>
    <b-modal 
        id="base-delete-modal"
        ref="base-delete-modal"
        title="Delete"
        content-class="base-delete-modal"
        centered 
        hide-header
    >
        <div class="base-delete-modal__body">
            <h5>Вы уверены что хотите удалить {{ params.title }}</h5>
        </div>
        <template #modal-footer>
            <button @click="hideModal" class="button-cancel"> Отмена </button>
            <button @click="deleteItem"  class="button-delete"> Удалить </button>
        </template>
    </b-modal>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import store from '@/store'

    export default {
        name: 'BaseDeleteModal',
        computed: {
            ...mapGetters({
                params: 'deleteModalParamsGetter'
            })
        },
        methods: {
            ...mapActions({ 
                setParamsDeleteModal: 'setParamsDeleteModalActions',
            }),
            async deleteItem(){
                for(var i in this.params.data){
                    store.dispatch(this.params.actionsName, this.params.data[i])
                }
                
                if(typeof this.params.redirect != 'undefined' && this.params.redirect != '' && this.params.redirect != null){
                    this.$router.push(this.params.redirect)
                }
                if(this.params.redirect == 'reload'){
                    this.$router.go()
                }
                this.hideModal()
            },
            hideModal(){
                this.$refs['base-delete-modal'].hide();
            }
        }
    }
</script>