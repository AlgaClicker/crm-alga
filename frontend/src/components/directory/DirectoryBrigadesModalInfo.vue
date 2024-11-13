<template>
    <b-modal
        id="directory-brigade-modal-info"
        ref="directory-brigade-modal-info"
        centered 
        content-class="c-modal-default c-modal-add-brigade"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Информация</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-body-add-brigade">
            <b-row> 
                <b-col sm="12" lg="6">
                    <b-row>
                        <b-col cols="12">
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
                        <b-col class="mt-4" cols="12">
                            <label class="label-custom" for="input-name">Мастер</label>
                            <multiselect 
                                v-model="form.master"  
                                placeholder="Выберите бригадира" 
                                :options="mastersListProps" 
                                label="username"
                                track-by="username"
                                :show-labels="false"
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
                        </b-col>
                    </b-row>
                </b-col>
                <b-col sm="12" lg="6">
                    <label class="label-custom" for="input-name">Сотрудники</label>
                    <multiselect 
                        v-model="value" 
                        :options="workpeopleOptions"
                        label="name"
                        placeholder="Выберите пользователя"
                        track-by="name"
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
                <b-col sm="12" lg="10">
                    <label class="label-custom mt-3" for="input-name">Прикрепленные спецификации</label>
                    <multiselect 
                        v-model="spec"  
                        placeholder="Выберите спецификацию" 
                        :options="specificationsOptions" 
                        label="name"
                        track-by="name"
                        :show-labels="false"
                        @input=addSpec()
                    >
                        <template slot="option" slot-scope="props">
                            <div class="option__desc">
                                <div class="c-avatar-list">
                                    <span class="ml-1"> {{ props.option.name }} </span>
                                </div>
                            </div>
                        </template>
                    </multiselect>
                    <specification-modal-tabel
                        class="mt-4"
                        v-show="form.specifications?.length != 0"
                        :isEditProps="true"
                        @deleteSpecEmit="deleteSpec"
                        :specificationListProps="form.specifications"
                    />
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="editBrigade"  class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Изменить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from "vuex";
    import { required } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";
    import _ from 'lodash'

    import BaseAccount from '@/components/elements/BaseAccount'
    import SpecificationModalTabel from '@/components/elements/tables/SpecificationModalTabel'

    export default {
        name: 'DirectoryBrigadesModalInfo',
        data(){
            return {
                form: {
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
            SpecificationModalTabel,
            BaseAccount
        },
        mixins: [validationMixin],
        watch: {
            'selectSpec': {
                async handler() {
                    if( this.form.specifications.filter( item => item.id == this.selectSpec.id)?.length == 0 ){
                        this.form.specifications.push(this.selectSpec)
                    } 
                }
            }
        },
        validations: {
            form: {
                name: { required },
                master: { required }
            }
        },
        computed: {
            ...mapGetters({
                directoryBrigadesError: 'directoryBrigadesErrorGetter',
                specificationList: 'specificationListGetter',
                directoryBrigadesWorkpeople: 'directoryBrigadesWorkpeopleGetter',
                directoryBrigadesFreeEmployeesList: 'directoryBrigadesFreeEmployeesListGetter',
                directoryBrigadesList: 'directoryBrigadesListGetter',
                directoryBrigade: 'directoryBrigadeGetter'
            }),
            validationsComputed() {
                if( 
                    this.$v.form.name.$invalid  == false && 
                    this.$v.form.master.$invalid == false 
                ){
                    return true
                }else {
                    return false
                }
            },  
        },
        props: {
            mastersListProps: { type: Array, default: () => [] },
            employeesListProps: { type: Array, default: () => [] },
            specificationAllListProps: { type: Array, default: () => [] },
            selectBrigadeProps: { type: Object, default: () => {} }
        },
        methods: {
            ...mapActions({
                directoryBrigadesEdit: 'directoryBrigadesEditActions',
                directoryBrigadesMoveEmployees: 'directoryBrigadesMoveEmployeesActions',
                directoryBrigadesDeleteEmployees: 'directoryBrigadesDeleteEmployeesActions',
                directoryBrigadesAddEmployees: 'directoryBrigadesAddEmployeesActions',
                directoryBrigadesSetFreeEmployees: 'directoryBrigadesSetFreeEmployeesActions',
                directoryBrigadesSetPeople: 'directoryBrigadesSetPeopleActions',
                directoryBrigadesSetBrigade: 'directoryBrigadesSetBrigadeActions'
            }),
            addWorkpeople(){
                this.workpeopleOptions = this.workpeopleOptions.filter( item => item.id != this.value.id)
                this.form.workpeoples.push(this.value)
            },
            deleteWorkpeople(id){
                this.workpeopleOptions.push(this.form.workpeoples.filter(item => item.id == id)[0])
                this.form.workpeoples = this.form.workpeoples.filter(item => item.id != id)
            },
            addSpec(){
                this.specificationsOptions = this.specificationsOptions.filter( item => item.id != this.spec.id )
                this.form.specifications.push(this.spec)
                this.spec = null
            },
            deleteSpec( _, item ){
                this.specificationsOptions.push(this.form.specifications.filter( element => element.id == item.id )[0])
                this.form.specifications = this.form.specifications.filter( element => element.id != item.id )
                this.spec = null
            },
            async editBrigade(){
                let form = {}
                form = this.form

                if( typeof form.specifications != 'undefined'){
                    form.specifications = this.form.specifications.map( item => item.id)
                }
                else{
                    form.specifications = []
                }
                
                form.master = this.form.master.id

                await this.directoryBrigadesEdit(form)
                if(!this.directoryBrigadesError){
                    this.hideModal()
                }
            },
            hideModal(){
                this.form.specifications = []
                this.$refs['directory-brigade-modal-info'].hide();
            },
            async mountedData(){
                this.$v.$reset()

                await this.directoryBrigadesSetBrigade(this.selectBrigadeProps.id)

                this.form.id = this.directoryBrigade.id
                this.form.name = this.directoryBrigade.name
                this.form.master = this.directoryBrigade.master

                if(typeof this.directoryBrigade.specifications == 'undefined'){
                    this.form.specifications = []
                }else{
                    this.form.specifications = this.directoryBrigade.specifications
                }

                await this.directoryBrigadesSetPeople(this.directoryBrigade.id)
                await this.directoryBrigadesSetFreeEmployees()
                this.workpeopleOptions = this.directoryBrigadesFreeEmployeesList

                this.form.workpeoples = this.directoryBrigadesList.filter(item => item.id == this.directoryBrigade.id)[0].workpeoples
                if(typeof this.form.workpeoples == 'undefined' || this.form.workpeoples == null)
                {
                    this.form.workpeoples = []
                }
                
                let uniqIdList = _.difference(this.specificationAllListProps.map(item => item.id), this.form.specifications.map(item => item.id))

                if(uniqIdList == []){
                    this.specificationsOptions = []
                }else{
                    this.specificationsOptions = this.specificationAllListProps.filter( item => uniqIdList.indexOf( item.id ) != -1)
                }
                
            }
        }
    }
</script>