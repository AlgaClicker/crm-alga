<template>
    <b-modal
        id="directory-brigade-modal-add"
        ref="directory-brigade-modal-add"
        centered 
        content-class="c-modal-default c-modal-add-brigade"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Новая бригада</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-body-add-brigade">
            <b-row>
                <b-col sm="12" lg="6">
                    <label class="label-custom" for="input-name">Название бригады</label>
                    <b-form-input
                        id="input-name"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.name.$error }"
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
                <b-col>
                    <label class="label-custom" for="input-name">Мастер</label>
                    <template v-if="!isMaster">
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
                    </template>
                    <template v-else>
                        <author-title 
                            :authorProps="profile"
                        />
                    </template>
                </b-col>
            </b-row>
            <b-row>
                <b-col class="mt-4" sm="12" lg="8">
                    <label class="label-custom" for="input-name">Добавить сотрудников</label>
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
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="addBrigade"  class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Сохранить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from "vuex";
    import { required } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";

    import BaseAccount from '@/components/elements/BaseAccount'
    import AuthorTitle from '@/components/elements/AuthorTitle'

    export default {
        name: 'DirectoryBrigadesModalAdd',
        data() {
            return {
                form: {
                    name: '',
                    master: '',
                    workpeoples: []
                },
                isMaster: false,
                workpeopleOptions: [],
                value: null,
            }
        },
        components: {
            BaseAccount,
            AuthorTitle
        },
        props: {        
            mastersListProps: { type: Array, default: () => [] },
            employeesListProps: { type: Array, default: () => [] },
            specificationAllListProps: { type: Array, default: () => [] },
        },
        watch: {
            'selectSpec': {
                async handler() {
                    if( this.form.specifications.filter( item => item.id == this.selectSpec.id).length == 0 ){
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
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                directoryBrigadesError: 'directoryBrigadesErrorGetter',
                directoryBrigadesFreeEmployeesList: 'directoryBrigadesFreeEmployeesListGetter',
                profile: "profileGetter"
            }),
            validationsComputed() {
                if( 
                    this.$v.form.name.$invalid == false && 
                    this.$v.form.master.$invalid == false 
                ){
                    return true
                }else {
                    return false
                }
            },  
        },
        methods: {
            ...mapActions({ 
                directoryBrigadesAdd: 'directoryBrigadesAddActions',
                specificationGetList: 'specificationGetListActions',
                directoryBrigadesSetFreeEmployees: 'directoryBrigadesSetFreeEmployeesActions',
                directoryBrigadesSet: 'directoryBrigadesSetActions',
                masterBrigadesSetList: 'masterBrigadesSetListActions'
            }),
            hideModal(){
                this.$refs['directory-brigade-modal-add'].hide();
            },
            addWorkpeople(){
                this.workpeopleOptions = this.workpeopleOptions.filter( item => item.id != this.value.id)
                this.form.workpeoples.push(this.value)
            },
            deleteWorkpeople(id){
                var people = this.form.workpeoples.filter(item => item.id == id)[0]
                this.form.workpeoples = this.form.workpeoples.filter(item => item.id != id)
                this.workpeopleOptions.push(people)
            },

            async addBrigade(){

                let form = this.form
                form.workpeoples = this.form.workpeoples.map( item => item.id)
                form.master = this.form.master.id

                await this.directoryBrigadesAdd(form)
                if(!this.directoryBrigadesError){
                    this.directoryBrigadesSet()

                    if(this.isMaster ){
                       await this.masterBrigadesSetList()
                       
                    }
                    this.hideModal()
                }
            },
            async mountedData(){
                this.$v.$reset()
                this.form.name = ''
                this.form.master = ''
                this.form.workpeoples = []

                if(this.profile.roles.service == 'master'){
                    this.form.master = this.profile
                    this.isMaster = true
                }
                
                await this.directoryBrigadesSetFreeEmployees()
                this.workpeopleOptions = this.directoryBrigadesFreeEmployeesList
            }
        }
    }
</script>