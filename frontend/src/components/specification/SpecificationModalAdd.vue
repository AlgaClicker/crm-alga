<template>
    <b-modal 
        id="specification-modal-add"
        ref="specification-modal-add"
        title="Новая спецификация"
        content-class="c-modal-default c-specifications-modal-add"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Создать спецификацию</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-body-specification">
            <b-row>
                <b-col cols="6">
                    <b-row>
                        <b-col class="mb-4" cols="12">
                            <label class="label-custom" for="input-name">Название спецификации</label>
                            <b-form-input
                                id="input-name"
                                class="input-custom"
                                aria-describedby="input-name-help input-name-feedback"
                                :class="{ 'input-custom--invalid': $v.form.name.$error }"
                                v-model="form.name"
                                v-model.trim="$v.form.name.$model"
                                :state="!$v.form.name.$error && null"
                                placeholder="Введите название спецификации"
                                trim
                            ></b-form-input>
                            <b-form-invalid-feedback id="input-name-feedback">
                                Название спецификации должно быть длиннее шести символов 
                            </b-form-invalid-feedback>
                        </b-col>
                        <b-col class="mb-4" cols="12">
                            <label class="label-custom"  for="input-descriptions">Описание</label>
                            <b-form-textarea
                                id="input-description"
                                class="input-custom"
                                :class="{ 'input-custom--invalid': $v.form.description.$error }"
                                aria-describedby="input-descriptions-help input-descriptions-feedback"
                                v-model="form.description"
                                v-model.trim="$v.form.description.$model"
                                :state="!$v.form.description.$error && null"
                                placeholder="Введите описание..."
                                trim
                                rows="3"
                                max-rows="12"
                                no-resize
                            ></b-form-textarea>
                            <b-form-invalid-feedback id="input-descriptions-feedback">
                                Описание объекта должно быть длинее шести сиволов 
                            </b-form-invalid-feedback>
                        </b-col>
                        <b-col class="mb-4" cols="12">
                            <label class="label-custom"  for="input-address">Место положение</label>
                            <b-form-input
                                id="input-address"
                                class="input-custom"
                                :class="{ 'input-custom--invalid': $v.form.locnum.$error }"
                                aria-describedby="input-live-help input-address-feedback"
                                v-model="form.locnum"
                                v-model.trim="$v.form.locnum.$model"
                                :state="!$v.form.locnum.$error && null"
                                placeholder="Введите место положение"
                                trim
                            ></b-form-input>
                            <b-form-invalid-feedback id="input-address-feedback">
                                Адресс объекта должно быть длинее шести сиволов 
                            </b-form-invalid-feedback>
                        </b-col>  
                    </b-row>
                </b-col>
                <b-col cols="6">
                    <b-row>
                        <b-col class=" mb-4" cols="12">
                            <label class="label-custom">Назначить</label>
                            <multiselect 
                                class="mb-4"
                                v-model="value" 
                                :options="responsiblesOptions"
                                label="username"
                                placeholder="Выберите пользователя"
                                track-by="username"
                                :show-labels="false"
                                @input=addResponsible()
                            >
                                <template slot="option" slot-scope="props">
                                    <div class="option__desc">
                                        <div class="c-avatar-list">
                                            <span class="option__title c-avatar">{{ props.option.username.split('')[0].toUpperCase() }}</span>
                                            <span class="ml-1"> {{ props.option.username }} </span>
                                            <span class="option__profession ml-1"> {{ props.option.roles.name}}</span>
                                        </div>
                                    </div>
                                </template>
                            </multiselect>
                            <div v-for="item in form.responsibles" :key="item.id" class="c-workpeople-list">
                                <base-account
                                    @deleteAccountEmit="deleteResponsible(item.id)"
                                    :accountProps="item"
                                    :isEdit="false"
                                />
                            </div>
                            <label class="label-custom" for="input-responsible">Договоры</label>
                            <multiselect 
                                v-model="contract" 
                                :options="contractsListComputed"
                                label="number"
                                placeholder="Введите номер договора" 
                                :searchable="true"
                                :loading="directoryContractsLoading"
                                :internal-search="false"
                                :clear-on-select="false" 
                                :options-limit="300" 
                                :limit="3" 
                                :max-height="600" 
                                @input=addContract()
                                :show-no-results="true" 
                                :show-labels="false" 
                                @search-change="asyncFind" 
                                class='mb-2' 
                            >
                                <template slot="option" slot-scope="props">
                                    <div class="option__desc">
                                        {{ props.option.number_sys  }} 
                                        <span class="c-list-spec__object"> ( {{ props.option.number }} )</span>
                                    </div>
                                </template>
                            </multiselect>

                            <directory-contract-block 
                                v-for="item in form.contracts" :key="item.id"
                                :ContractProps="item"
                                @showContractEmit="showContract(item)"
                                @removeContractEmit="removeContract(item)"
                            />

                            <label class="label-custom mt-3">Файлы</label>
                            <file-upload-modal 
                                @addFileEmit="addFile" 
                                @removeFileEmit="removeFile" 
                            />
                        </b-col>
                    </b-row>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="addSpecifications" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Создать
            </button>
        </template>
    </b-modal>  
</template>

<script>
    import { mapActions, mapGetters } from "vuex";
    import { required, minLength } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";

    import BaseAccount from '@/components/elements/BaseAccount'
    import FileUploadModal from '@/components/elements/files/FileUploadModal'
    import DirectoryContractBlock from '@/components/directory/DirectoryContractBlock'

    export default {
        name: 'SpecificationModalAdd',
        data(){
            return {
                value: null,
                contract: null,
                contractsList: [],
                specificationsList: [],
                responsiblesOptions: [],
                form: {
                    name: '',
                    files: [],
                    locnum: '',
                    objectId: '',
                    contracts: [],
                    description: '',
                    responsibles: [],
                },
            }
        },
        components: {
            BaseAccount,
            FileUploadModal,
            DirectoryContractBlock,
        },
        props: {
            objectIdProps: { type: String, default: "" },
            accountsProps: { type: Array, default: () => [] },
        },
        mixins: [validationMixin],
        validations: {
            form: {
                name: { 
                    required,
                    minLength: minLength(6)
                },
                description: { 
                    required,
                    minLength: minLength(6)
                },
                locnum: { 
                    required,
                    minLength: minLength(6)
                },
            }
        },
        computed: {
            ...mapGetters({
                specification: 'specificationGetter',
                specificationError: "specificationErrorGetter",
                directoryContractsList: 'directoryContractsListGetter',
                directoryContractsLoading: 'directoryContractsLoadingGetter',
            }),
            validationsComputed() {
                if( this.$v.form.name.$invalid  == false && 
                    this.$v.form.locnum.$invalid == false &&
                    this.$v.form.description.$invalid == false
                ){
                    return true
                }
                else {
                    return false  
                }
            },
            accountsListComputed(){
                return this.responsiblesOptions
                .filter( item => this.form?.responsibles.filter( element => element.id == item.id).length == 0 )
            },
            contractsListComputed(){

                let list = []

                for(let i = 0; i < this.contractsList.length; i++){
                    if( this.form.contracts.findIndex( item => item.id == this.contractsList[i].id) == -1){
                        list.push(this.contractsList[i])
                    }
                }
                
                return list
            }
        },
        methods: {
            ...mapActions({ 
                specificationAdd: "specificationAddActions",
                directoryContractsSearch: 'directoryContractsSearchActions',
                directoryContractsSetList: 'directoryContractsSetListActions',
                objectSpecificationSetList: "objectSpecificationSetListActions",
            }),
            async addContract(){
                this.form.contracts.push( this.contract )
                this.contractsList = this.contractsList.filter( item => item.id != this.contract.id )
                this.contract = null
            },
            removeContract(contract){
                this.form.contracts  = this.form.contracts.filter( item => item.id != contract.id)
                this.contractsList.push(contract)
            },
            hideModal(){
                this.$refs['specification-modal-add'].hide();
            },
            addFile( _, file){
                this.form.files.push(file)
            },
            async asyncFind(query){
                if( query.length >= 2 ){
                    await this.directoryContractsSearch(query)
                    this.contractsList = this.directoryContractsList
                } else {
                    this.directoryContractsSearch(null).then( () => this.contractsList = this.directoryContractsList)
                }
            },
            removeFile(hash){
                this.form.files = this.form.files.filter(item => item.hash != hash)
            },
            addResponsible(){
                this.responsiblesOptions = this.responsiblesOptions.filter( item => item.id != this.value.id)
                this.form.responsibles.push(this.value)
            },
            deleteResponsible(id){
                this.value = null
                var responsible = this.form.responsibles.filter(item => item.id == id)[0]
                this.form.responsibles = this.form.responsibles.filter(item => item.id != id)
                this.responsiblesOptions.push(responsible)
            },
            async addSpecifications(){
                
                this.form.files = this.form.files.map(item => item.hash)

                this.form.responsibles = this.form.responsibles.map( item => { 
                    const select = {}
                    select.id = item.id
                    return select
                } )

                this.form.objectId = this.objectIdProps

                this.form.contracts = this.form.contracts.map( item => { 
                    const select = {}
                    select.id = item.id
                    return select
                })
                
                await this.specificationAdd(this.form)  
                
                if(!this.specificationError){
                    this.objectSpecificationSetList(this.objectIdProps)
                }
                this.hideModal()
            },
            mountedData(){
                this.directoryContractsSetList().then( () => this.contractsList = this.directoryContractsList)
                
                this.responsiblesOptions = this.accountsProps

                this.form.name = ''
                this.form.description = ''
                this.form.contracts = []
                this.form.files = []
                this.form.responsibles = []
                this.form.locnum = ''
                this.$v.$reset()
            }
            
        },
    }
</script>

<style>
</style>