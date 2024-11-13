<template>
    <b-modal
        id="master-brigade-modal-edit"
        ref="master-brigade-modal-edit"
        centered 
        content-class="c-modal-default c-modal-add-brigade"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Редактировать бригаду</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-body-add-brigade">
            <b-row>
                <b-col sm="12" lg="12">
                    <label class="label-custom" for="input-name">Название бригады</label>
                    <b-form-input
                        id="input-name"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.name.$error}"
                        aria-describedby="input-name-feedback"
                        v-model="form.name"
                        v-model.trim="$v.form.name.$model"
                        :state="!$v.form.name.$error && null"
                        placeholder="Название бригады"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-name-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
                <b-col sm="12" lg="12"> 
                    <label class="label-custom mt-4" for="input-name">Сотрудники</label>
                    <multiselect 
                        v-model="value" 
                        :options="workpeopleOptions"
                        label="name"
                        placeholder="Выберите сотрудника"
                        track-by="name"
                        class="mb-4"
                        :show-labels="false"
                        @input=addWorkpeople()
                    >
                        <template slot="option" slot-scope="props">
                            <div class="option__desc">
                                <div class="c-avatar-list">
                                    <span class="option__title c-avatar">{{ props.option.name.split('')[0].toUpperCase() }}</span>
                                    <span class="ml-1"> {{ props.option.name }} </span>
                                    <span class="option__profession ml-1"> {{ props.option.profession.fullname }}</span>
                                </div>
                            </div>
                        </template>
                    </multiselect> 
                    <div v-for="item in form.workpeoples" :key="item.id" class="c-workpeople-list">
                        <base-account
                            @deleteAccountEmit="deleteWorkpeople(item.id)"
                            :accountProps="item"
                            :isEdit="false"
                        />
                    </div>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="editBrigade" class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Изменить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from "vuex"
    import { required } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate"
    import BaseAccount from '@/components/elements/BaseAccount'

    export default {
        name: 'MasterBrigadesModaEdit',
        data(){
            return {
                form: {
                    id: '',
                    name: '',
                    master: '',
                    specifications: [],
                    workpeoples: []
                },
                value: null,
                spec: null,
                addEmployeesLits: [],
                deleteEmployeesLits: [],
                workpeopleOptions: [],
                specificationsOptions: []
            }
        },
        components: {
            BaseAccount
        },
        validations: {
            form: {
                name: { required }
            }
        },
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                masterError: 'masterErrorGetter',
                masterBrigade: 'masterBrigadeGetter',
                directoryBrigadesFreeEmployeesList: 'directoryBrigadesFreeEmployeesListGetter',
            }),
            validationsComputed() {
                if( 
                    this.$v.form.name.$invalid  == false 
                ){
                    return true
                }else {
                    return false
                }
            },  
        },
        methods: {
            ...mapActions({
                masterBrigadeSet: 'masterBrigadeSetActions',
                masterBrigadeEdit: "masterBrigadeEditActions",
                directoryBrigadesSetFreeEmployees: 'directoryBrigadesSetFreeEmployeesActions',
            }),
            async editBrigade(){

                let form = { ...this.form }

                form.master = this.form.master.id

                await this.masterBrigadeEdit(form)

                this.form.id = this.masterBrigade.id
                this.form.name = this.masterBrigade.name
                this.form.workpeoples = this.masterBrigade.workpeoples
                this.form.master = this.masterBrigade.master
                this.form.specifications = this.masterBrigade.specifications

                if(this.masterError == ""){
                    this.hideModal()
                }
            },
            deleteWorkpeople(id){
                this.workpeopleOptions.push(this.form.workpeoples.filter(item => item.id == id)[0])
                this.form.workpeoples = this.form.workpeoples.filter(item => item.id != id)
            },
            addWorkpeople(){
                this.workpeopleOptions = this.workpeopleOptions.filter( item => item.id != this.value.id)
                this.form.workpeoples.push(this.value)
            },
            hideModal(){
                this.$refs['master-brigade-modal-edit'].hide();
            },
            async mountedData(){

                await this.directoryBrigadesSetFreeEmployees()

                this.form.id = this.masterBrigade.id
                this.form.name = this.masterBrigade.name
                this.form.workpeoples = this.masterBrigade.workpeoples
                this.form.master = this.masterBrigade.master
                this.form.specifications = this.masterBrigade.specifications

                this.workpeopleOptions = this.directoryBrigadesFreeEmployeesList
            }
        },
    }
</script>