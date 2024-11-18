<template>
    <b-modal 
        id="object-edit-modal"
        ref="object-edit-modal"
        centered 
        content-class="c-modal-default c-modal-object"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Редактировать объект</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class='c-body c-modal-object-body'>
            <b-row class="mt-3">
                <b-col col lg="12" sm="12">
                    <label class="label-custom" for="input-surname">Название</label>
                    <b-form-input
                        aria-describedby="input-name-feedback"
                        id="name-surname"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.formEdit.name.$error }"
                        v-model="formEdit.name"
                        v-model.trim="$v.formEdit.name.$model"
                        :state="!$v.formEdit.name.$error && null"
                        placeholder="Введите название"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-name-feedback">
                        Название объекта должно быть длиннее шести символов 
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <b-row class="mt-3 mb-4">
                <b-col lg="12" sm="12">
                    <label class="label-custom" for="input-adress">Адрес</label>
                    <b-form-input
                        aria-describedby="input-adress-feedback"
                        id="input-adress"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.formEdit.address.$error }"
                        v-model="formEdit.address"
                        v-model.trim="$v.formEdit.address.$model"
                        :state="!$v.formEdit.address.$error && null"
                        placeholder="Введите название"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-adress-feedback">
                        Адрес объекта должен быть длиннее шести символов 
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="edit" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Изменить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    import { required, minLength } from 'vuelidate/lib/validators'

    export default {
        name: 'ObjectsEditModal',
        data(){
            return {
                formEdit: {
                    id: '',
                    name: '',
                    address: '',
                    responsibles: [],
                },
                value: '',
                accountsList: ''
            }
        },
        props: {
            objectProps: { type: Object, default: () => {} },
        },
        validations: {
            formEdit: {
                name: { 
                    required,
                    minLength: minLength(6)
                },
                address: { 
                    required,
                    minLength: minLength(6)
                },
            }
        },
        computed: {
            ...mapGetters({
                objectGet: 'objectGetter',
                objectsError: 'objectsErrorGetter',
                accountsCompanyList: 'accountsCompanyListGetter',
            }),
            validationsComputed(){
                if( this.$v.formEdit.name.$invalid  == false && 
                    this.$v.formEdit.address.$invalid  == false 
                ){
                    return true
                }
                else {
                    return false  
                }
            },
            accountsListComputed(){
                return this.accountsCompanyList
                .filter( item => this.formEdit.responsibles.filter( element => element.id == item.id).length == 0 )
            }
        },
        methods: {
            ...mapActions({
                objectEdit: 'objectEditActions',
                accountsCompanySet: 'accountsCompanySetActions'
            }),
            addResponsible(){
                if(this.formEdit.responsibles.filter(item => item.id == this.value.id )?.length == 0){
                    this.formEdit.responsibles.push(this.value) 
                }
            },
            deleteResponsible(id){
                this.formEdit.responsibles = this.formEdit.responsibles.filter(item => item.id != id)
            },
            async edit(){
                await this.objectEdit({
                    ...this.formEdit,
                    responsibles: this.formEdit.responsibles.map(item => item.id)
                })

                if(!this.objectsError){
                    this.hideModal();
                }
            },
            hideModal(){
                this.$refs['object-edit-modal'].hide();
            },
            mountedData(){
                this.accountsList = this.accountsCompanyList
                    .filter( item => this.formEdit.responsibles.filter( element => element.id == item.id).length == 0 )

                this.formEdit.id = this.objectProps.id
                this.formEdit.name = this.objectProps.name
                this.formEdit.address = this.objectProps.address
                //this.formEdit.files = this.objectProps.files

                this.accountsCompanySet()
                this.$v.$reset()
            }
        },
    }
</script>