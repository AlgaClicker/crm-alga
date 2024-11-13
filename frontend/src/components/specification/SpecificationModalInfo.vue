<template>
    <b-modal
        id="specification-modal-info"
        ref="specification-modal-info"
        title="Новая спецификация"
        content-class="c-modal-default c-specifications-modal-add"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Информация о спецификации</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-specification-modal-info-body">
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
                                Название объекта должно быть длиннее шести символов 
                            </b-form-invalid-feedback>
                        </b-col>
                        <b-col class="mb-4" cols="12">
                            <label class="label-custom" for="input-locnum">Расположение объекта</label>
                            <b-form-input
                                id="input-locnum"
                                class="input-custom"
                                aria-describedby="input-locnum-help input-locnum-feedback"
                                :class="{ 'input-custom--invalid': $v.form.locnum.$error }"
                                v-model="form.locnum"
                                v-model.trim="$v.form.locnum.$model"
                                :state="!$v.form.locnum.$error && null"
                                placeholder="Введите расположение объекта"
                                trim
                            ></b-form-input>
                            <b-form-invalid-feedback id="input-locnum-feedback">
                                Название объекта должно быть длиннее шести символов 
                            </b-form-invalid-feedback>
                        </b-col>
                        <b-col class="mb-4" cols="12">
                            <label class="label-custom" for="input-description">Описание спецификации</label>
                            <b-form-textarea
                                id="input-description"
                                class="input-custom"
                                :class="{ 'input-custom--invalid': $v.form.description.$error }"
                                aria-describedby="input-description-feedback"
                                v-model="form.description"
                                v-model.trim="$v.form.description.$model"
                                :state="!$v.form.description.$error && null"
                                placeholder="Введите описание..."
                                trim
                                rows="3"
                                max-rows="12"
                                no-resize
                            ></b-form-textarea>
                            <b-form-invalid-feedback id="input-description-feedback">
                                Описание объекта должно быть длиннее шести символов 
                            </b-form-invalid-feedback>
                        </b-col>
                    </b-row>
                </b-col>
                <b-col cols="6">
                    <label class="label-custom" for="input-responsible">Подписчики</label>       
                    <multiselect 
                        class="mb-4"
                        v-model="value" 
                        :options="accountsListComputed"
                        label="username"
                        placeholder="Выберите ответственного"
                        track-by="username"
                        :show-labels="false"
                        @input=addResponsible()
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
                    <div v-for="item in form.responsibles" :key="item.id" class="c-workpeople-list">
                        <base-account
                            @deleteAccountEmit="deleteResponsible(item.id)"
                            :accountProps="item"
                            :isEdit="false"
                        />
                    </div>
                </b-col>
            </b-row>
            <b-row>
                <b-col>
                    <label class="label-custom mt-3" for="input-responsible">Файлы</label>
                    <div class="c-specification-modal-info-body__files-wrapper">
                        <file
                            class="mt-1"
                            v-for="file in form.files" :key="file.hash"
                            :fileProps="file"
                            :deleteProps=true
                            @deleteFileEmit="deleteFile(file.hash)"
                        />
                    </div>
                    <file-upload 
                        @addFileEmit="addFile"
                    />
                </b-col>
                <b-col>
                    <label class="label-custom mt-3" for="input-responsible">Договоры</label>
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
                                {{ props.option.number_sys }} 
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
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="editSpecifications" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Редактировать
            </button>
        </template>
        <directory-contracts-work-modal-info
           :contractProps="selectContract" 
        />
    </b-modal>
</template>
<script>
    import { mapActions, mapGetters } from "vuex";
    
    import { required, minLength } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";

    import File from '@/components/elements/files/File'
    import BaseAccount from '@/components/elements/BaseAccount'
    import FileUpload from '@/components/elements/files/FileUpload'
    import DirectoryContractBlock from '@/components/directory/DirectoryContractBlock'
    import DirectoryContractsWorkModalInfo from '@/components/directory/DirectoryContractsWorkModalInfo'

    export default {
        name: 'SpecificationModalInfo',
        data(){
            return {
                form: {
                    id: '',
                    name: '',
                    description: '',
                    locnum: '',
                    files: [],
                    responsibles: [],
                    contracts: []
                },
                value: '',
                oldForm: {},
                contract: {},
                contracts: [],
                contractsList: [],
                listContracts: [],
                selectContract: {},
                contractsOptions: [],
            }
        },
        props: {
            specificationProps: { type: Object, default: () => {} }
        },
        components: {
            File,
            FileUpload,
            BaseAccount,
            DirectoryContractBlock,
            DirectoryContractsWorkModalInfo,
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
                specificationError: "specificationErrorGetter",
                accountsCompanyList: 'accountsCompanyListGetter',
                directoryContractsList: 'directoryContractsListGetter',
                directoryContractsLoading: 'directoryContractsLoadingGetter',
                specificationListContracts: 'specificationListContractsGetter',
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
                return this.accountsCompanyList
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
                specificationEdit: 'specificationEditActions',
                accountsCompanySet: 'accountsCompanySetActions',
                directoryContractsSearch: 'directoryContractsSearchActions',
                directoryContractsSetList: 'directoryContractsSetListActions',
                specificationSetListContracts: 'specificationSetListContractsActions'
            }),
            showContract(item){
                this.selectContract = item
                this.$bvModal.show('directory-contracts-work-modal-info')
            },
            hideModal(){
                this.$refs['specification-modal-info'].hide();
            },
            addResponsible(){
                if(this.form.responsibles.filter(item => item.id == this.value.id )?.length == 0){
                    this.form.responsibles.push(this.value)
                }
            },
            deleteResponsible(id){
                this.form.responsibles = this.form.responsibles.filter(item => item.id != id)
            },
            addFile(_, file){
                this.form.files.push(file)
                this.specificationEdit(this.form)   
            },
            async asyncFind(query){
                if( query.length >= 2 ){
                    await this.directoryContractsSearch(query)
                    this.contractsList = this.directoryContractsList
                } else {
                    this.directoryContractsSearch(null).then( () => this.contractsList = this.directoryContractsList)
                }
            },
            async addContract(){
                this.form.contracts.push( this.contract )
                this.contractsList = this.contractsList.filter( item => item.id != this.contract.id )
                this.contract = null
            },
            removeContract(contract){
                this.form.contracts = this.form.contracts.filter( item => item.id != contract.id )
         
            },
            async editSpecifications(){
                let newForm = { ...this.form }  

                newForm.responsibles = newForm.responsibles.map(item => item.id) 
                
                newForm.contracts = newForm.contracts.map( item => { 
                    const select = {}
                    select.id = item.id
                    return select
                })
                
                await this.specificationEdit(newForm)  
                
                if(!this.specificationError){
                    this.hideModal()
                }
            },
            async deleteFile(hash){
                var files = this.form.files.filter( item => item.hash != hash)
                this.form.files = files
                await this.specificationEdit(this.form) 
            },
            async mountedData(){
                
                await this.directoryContractsSetList()
                await this.specificationSetListContracts(this.specificationProps.id)

                this.form.id = this.specificationProps.id
                this.form.name = this.specificationProps.name
                this.form.description = this.specificationProps.description
                this.form.locnum = this.specificationProps.locnum
                this.form.files = this.specificationProps.files
                this.form.responsibles = this.specificationProps.responsibles
                this.form.contracts = this.specificationListContracts 
                
                this.contractsList = this.directoryContractsList
                await this.accountsCompanySet()
                this.$v.$reset()
            }
        }
    }
</script>