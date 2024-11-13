<<<<<<< HEAD
<template>
    <b-modal
        id="upravleine-requisition-set-manage"
        ref="upravleine-requisition-set-manage"
        content-class="c-modal-default"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Назначить менаджера</h5>
            <button @click="hideModal()">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-col cols="12">
                <label class="label-custom mt-2" for="input-name">Менаджер</label>
                <multiselect 
                    v-model="value"  
                    placeholder="Выберите спецификацию" 
                    :options="manageListProps" 
                    label="text"
                    track-by="text"
                    :show-labels="false"
                >
                    <template slot="option" slot-scope="props">
                        <div class="option__desc">
                            <div class="c-avatar-list">
                                <span class="ml-1"> {{ props.option.text }} </span>
                            </div>
                        </div>
                    </template>
                </multiselect>
            </b-col>
        </div>
        <template #modal-footer>
            <button 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
                @click="setManage"
            >
                Назначить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    export default {
        name: 'UpravleineRequisitionSetManage',
        data(){
            return {
                value: null
            }
        },
        props: {
            requisitionProps:  { type: Object, default: () => {} },
            manageListProps: { type: Array, default: () => [] }
        },
        computed: {
            ...mapGetters({
                upravlenieRequisitionsError: 'upravlenieRequisitionsErrorGetter',
            }),
            validationsComputed(){
                return this.value != null
            }
        },
        methods: {
            ...mapActions({
                upravlenieRequisitionsSetManage: 'upravlenieRequisitionsSetManageActions'
            }),
            hideModal(){
                this.$refs['upravleine-requisition-set-manage'].hide();
            },
            async setManage(){
                let form = {
                    idRequisition: this.requisitionProps.id,
                    idMenager: this.value.value
                }

                await this.upravlenieRequisitionsSetManage(form)

                if(!this.upravlenieRequisitionsError){
                    this.hideModal()
                }
            },
            mountedData(){
                this.value = ''
                if( typeof this.requisitionProps.manager != 'undefined' ){
                    this.value = this.manageListProps.filter( item => item.value == this.requisitionProps.manager?.id )[0]
                    console.log(this.value)
                } 
            }
        }
    }
=======
<template>
    <b-modal
        id="upravleine-requisition-set-manage"
        ref="upravleine-requisition-set-manage"
        content-class="c-modal-default"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Назначить менеджера</h5>
            <button @click="hideModal()">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-col cols="12">
                <label class="label-custom mt-2" for="input-name">Менеджер</label>
                <multiselect 
                    v-model="value"  
                    placeholder="Выберите менеджера" 
                    :options="manageListProps" 
                    label="text"
                    track-by="text"
                    :show-labels="false"
                >
                    <template slot="option" slot-scope="props">
                        <div class="option__desc">
                            <div class="c-avatar-list">
                                <span class="ml-1"> {{ props.option.text }} </span>
                            </div>
                        </div>
                    </template>
                </multiselect>
            </b-col>
        </div>
        <template #modal-footer>
            <button 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
                @click="setManage"
            >
                Назначить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    export default {
        name: 'UpravleineRequisitionSetManage',
        data(){
            return {
                value: null
            }
        },
        props: {
            requisitionProps:  { type: Object, default: () => {} },
            manageListProps: { type: Array, default: () => [] }
        },
        computed: {
            ...mapGetters({
                upravlenieRequisitionsError: 'upravlenieRequisitionsErrorGetter',
            }),
            validationsComputed(){
                return this.value != null
            }
        },
        methods: {
            ...mapActions({
                upravlenieRequisitionsSetManage: 'upravlenieRequisitionsSetManageActions'
            }),
            hideModal(){
                this.$refs['upravleine-requisition-set-manage'].hide();
            },
            async setManage(){
                let form = {
                    idRequisition: this.requisitionProps.id,
                    idMenager: this.value.value
                }

                await this.upravlenieRequisitionsSetManage(form)

                if(!this.upravlenieRequisitionsError){
                    this.hideModal()
                }
            },
            mountedData(){
                this.value = ''
                if( typeof this.requisitionProps.manager != 'undefined' ){
                    this.value = this.manageListProps.filter( item => item.value == this.requisitionProps.manager?.id )[0]
                } 
            }
        }
    }
>>>>>>> origin/feature/f_requisitions_master
</script>