<template>
    <b-modal 
        id="object-add-modal"
        ref="object-add-modal"
        centered 
        content-class="c-modal-default c-modal-object"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Создать объект</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-modal-object-body">
            <b-row>
                <b-col col lg="12" sm="12">
                    <label class="label-custom" for="input-surname">Название</label>
                    <b-form-input
                        aria-describedby="input-name-feedback"
                        id="name-surname"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.name.$error }"
                        v-model="form.name"
                        v-model.trim="$v.form.name.$model"
                        :state="!$v.form.name.$error && null"
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
                        :class="{ 'input-custom--invalid': $v.form.address.$error }"
                        v-model="form.address"
                        v-model.trim="$v.form.address.$model"
                        :state="!$v.form.address.$error && null"
                        placeholder="Введитите название"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-adress-feedback">
                        Адрес объекта должен быть длиннее шести символов 
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <hr>
            <b-row class="mt-4">
                <b-col cols="12">
                    <FileUploadModal @addFileEmit="addFile" />
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="addObject" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Добавить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex' 
    import { required, minLength } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";
    
    import FileUploadModal from '@/components/elements/files/FileUploadModal'

    export default {
        name: 'ObjectsAddModal',
        data(){
            return {
                value: null,
                responsiblesOptions: [],
                form: {
                    name: '',
                    address: '',
                    responsibles: [],
                    files: []
                }
            }
        },
        components: {
            FileUploadModal,
        },
        mixins: [validationMixin],
        validations: {
            form: {
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
                profile: 'profileGetter',
                accountsCompanyList: 'accountsCompanyListGetter',
                objectsError: 'objectsErrorGetter',
                objectGet: 'objectGetter'
            }),
            validationsComputed() {
                if( this.$v.form.name.$invalid  == false && 
                    this.$v.form.address.$invalid  == false 
                ){
                    return true
                }
                else {
                    return false  
                }
            },
        },
        methods: {
            ...mapActions({
                objectsAdd: 'objectsAddActions',
                accountsCompanySet: 'accountsCompanySetActions',
            }),
            addResponsible(){
                this.responsiblesOptions = this.responsiblesOptions.filter( item => item.id != this.value.id)
                this.form.responsibles.push(this.value)
            },
            deleteResponsible(id){
                var responsible = this.form.responsibles.filter(item => item.id == id)[0]
                this.form.responsibles = this.form.responsibles.filter(item => item.id != id)
                this.responsiblesOptions.push(responsible)
            },
            addFile( _, file){
                this.form.files.push(file.hash)
            },
            async addObject(){
                await this.objectsAdd({
                    ...this.form,
                    responsibles: this.form.responsibles.map(item => item.id)
                })
                if(!this.objectsError){
                    this.hideModal()
                }
            },
            hideModal(){
                this.$refs['object-add-modal'].hide();
            },
            async mountedData(){  
                await this.accountsCompanySet()
                this.responsiblesOptions = this.accountsCompanyList

                this.form.name = ''
                this.form.address = ''
                this.form.responsibles = []
                this.form.files = ''
                this.$v.$reset()
            }
        }
    }
</script>