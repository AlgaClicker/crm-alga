<template>
    <b-modal 
        id="directory-partners-modal-add"
        ref="directory-partners-modal-add"
        :title="labelModalComputed"
        centered 
        content-class="c-modal-default c-modal-partner"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>{{ labelModalComputed }}</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-body-partner">
            <b-row> 
                <b-form-group>
                    <b-form-radio v-model="isGroup" name="some-radios" :value=true>Новая группа</b-form-radio>
                    <b-form-radio v-model="isGroup" name="some-radios" :value=false>Новый контрагент</b-form-radio>
                </b-form-group>
            </b-row>
            <b-row v-if="isGroup">     
               <b-col class="mt-2" cols="12">
                    <label class="label-custom" for="input-groupe-name">Название</label>
                    <b-form-input
                        id="input-groupe-name"
                        aria-describedby="input-live-help input-groupe-name-feedback"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.formGroupe.name.$error}"
                        v-model="formGroupe.name"
                        v-model.trim="$v.formGroupe.name.$model"
                        :state="!$v.formGroupe.name.$error && null"
                        placeholder="Введите название группы"
                        trim
                    ></b-form-input>
                        <!-- This will only be shown if the preceding input has an invalid state -->
                    <b-form-invalid-feedback id="input-groupe-name-feedback">
                        Название должно быть длиннее шести символов 
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="12">
                    <label class="label-custom" for="input-groupe-description">Описание</label>
                    <b-form-input
                        id="input-groupe-description"
                        aria-describedby="input-live-help input-groupe-description-feedback"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.formGroupe.description.$error}"
                        v-model="formGroupe.description"
                        v-model.trim="$v.formGroupe.description.$model"
                        :state="!$v.formGroupe.description.$error && null"
                        placeholder="Введите описание"
                        trim
                    ></b-form-input>
                        <!-- This will only be shown if the preceding input has an invalid state -->
                    <b-form-invalid-feedback id="input-groupe-description-feedback">
                        Описание должно быть длиннее шести символов 
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <template v-else>
                <b-row>
                    <b-col class="d-flex" cols="12">
                        <b-form-input
                            id="input-search"
                            class="input-custom"
                            v-model="inn"
                            placeholder="Поиск контрагента по ИНН"
                            trim
                        ></b-form-input>
                        <button @click="addInn" size="sm" variant="dark" class="px-3 ml-1 c-button-add ">
                            Поиск
                        </button>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="mt-2" cols="6">
                        <label class="label-custom" for="input-name">Название</label>
                        <b-form-input
                            id="input-name"
                            class="input-custom"
                            :class="{ 'input-custom--invalid': $v.formPartner.name.$error }"
                            aria-describedby="input-name-feedback"
                            v-model="formPartner.name"
                            v-model.trim="$v.formPartner.name.$model"
                            :state="!$v.formPartner.name.$error && null"
                            placeholder="Введите описание"
                            trim
                        ></b-form-input>
                        <b-form-invalid-feedback id="input-name-feedback">
                           Имя должно быть длиннее шести символов 
                        </b-form-invalid-feedback>
                    </b-col>
                    <b-col class="mt-2" cols="6">
                        <label class="label-custom" for="input-fullname">Полное название</label>
                        <b-form-input
                            id="input-fullname"
                            class="input-custom"
                            :class="{ 'input-custom--invalid': $v.formPartner.fullname.$error }"
                            aria-describedby="input-fullname-feedback"
                            v-model="formPartner.fullname"
                            v-model.trim="$v.formPartner.fullname.$model"
                            :state="!$v.formPartner.fullname.$error && null"
                            placeholder="Введите описание"
                            trim
                        ></b-form-input>
                        <b-form-invalid-feedback id="input-fullname-feedback">
                            Полное имя должно быть длиннее шести символов 
                        </b-form-invalid-feedback>
                    </b-col>
                    <b-col class="mt-2" cols="12">
                        <label class="label-custom" for="input-adress">Адресс</label>
                        <b-form-input
                            id="input-adress"
                            class="input-custom"
                            :class="{ 'input-custom--invalid': $v.formPartner.address.$error }"
                            aria-describedby="input-adress-feedback"
                            v-model="formPartner.address"
                            v-model.trim="$v.formPartner.address.$model"
                            :state="!$v.formPartner.address.$error && null"
                            placeholder="Введите описание"
                            trim
                        ></b-form-input>
                        <b-form-invalid-feedback id="input-adress-feedback">
                            Поле не должно быть пустым  
                        </b-form-invalid-feedback>
                    </b-col>
                    <b-col class="mt-2" cols="6">
                        <label class="label-custom" for="input-type">Тип</label>
                        <b-form-input
                            id="input-type"
                            class="input-custom"
                            :class="{ 'input-custom--invalid': $v.formPartner.type.$error }"
                            aria-describedby="input-type-feedback"
                            v-model="formPartner.type"
                            v-model.trim="$v.formPartner.type.$model"
                            :state="!$v.formPartner.type.$error && null"
                            placeholder="Введите описание"
                            trim
                        ></b-form-input>
                        <b-form-invalid-feedback id="input-type-feedback">
                            Поле не должно быть пустым 
                        </b-form-invalid-feedback>
                    </b-col>
                    
                    <b-col class="mt-2" cols="6">
                        <label class="label-custom" for="input-ogrn">ОГРН</label>
                        <b-form-input
                            id="input-ogrn"
                            class="input-custom"
                            :class="{ 'input-custom--invalid': $v.formPartner.ogrn.$error }"
                            aria-describedby="input-ogrn-feedback"
                            v-model="formPartner.ogrn"
                            v-model.trim="$v.formPartner.ogrn.$model"
                            :state="!$v.formPartner.ogrn.$error && null"
                            placeholder="Введите описание"
                            trim
                        ></b-form-input>
                        <b-form-invalid-feedback id="input-ogrn-feedback">
                            Поле не должно быть пустым 
                        </b-form-invalid-feedback>
                    </b-col>
                    <b-col class="mt-2" cols="6">
                        <label class="label-custom" for="input-ogrn-date">Дата ОГРН</label>
                        <b-form-input
                            id="input-ogrn-date"
                            class="input-custom"
                            :class="{ 'input-custom--invalid': $v.formPartner.ogrn_date.$error }"
                            aria-describedby="input-ogrn-date-feedback"
                            v-model="formPartner.ogrn_date"
                            v-model.trim="$v.formPartner.ogrn_date.$model"
                            :state="!$v.formPartner.ogrn_date.$error && null"
                            placeholder="Введите описание"
                            trim
                        ></b-form-input>
                        <b-form-invalid-feedback id="input-ogrn-date-feedback">
                            Поле не должно быть пустым 
                        </b-form-invalid-feedback>
                    </b-col>
                    <b-col class="mt-2" cols="6">
                        <label class="label-custom" for="input-inn">ИНН</label>
                        <b-form-input
                            id="input-inn"
                            class="input-custom"
                            :class="{ 'input-custom--invalid': $v.formPartner.inn.$error }"
                            aria-describedby="input-inn-feedback"
                            v-model="formPartner.inn"
                            v-model.trim="$v.formPartner.inn.$model"
                            :state="!$v.formPartner.inn.$error && null"
                            placeholder="Введите описание"
                            trim
                        ></b-form-input>
                        <b-form-invalid-feedback id="input-inn-feedback">
                            Поле не должно быть пустым 
                        </b-form-invalid-feedback>
                    </b-col>
                </b-row>
            </template>
        </div>
        <template #modal-footer>
            <button 
                @click="addPartner"  class="mt-4" 
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

    export default {
        name: 'DirectoryPartnersModalAdd',
        data(){
            return {
                formGroupe: {
                    name: '',
                    description: '',
                    parent: '',
                    isGroup: true,
                },
                formPartner: {
                    name: '',
                    fullname: '',
                    address: '',
                    type: '',
                    ogrn: '',
                    ogrn_date: '',
                    inn: '',
                    parent: '',
                    isGroup: false,
                },
                inn: '',
                valSearch: '',
                isGroup: false
            }
        },
        validations: {
            formGroupe: {
                name: { required },
                description: { required }
            },
            formPartner: {
                name: { required },
                fullname: { required },
                address: { required },
                type: { required },
                ogrn: {
                    required,
                },
                ogrn_date: {
                    required
                },
                inn: {
                    required
                },
            }
        },
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                directoryPartnersError: 'directoryPartnersErrorGetter',
                editCurrentUidGetter: 'editCurrentUidGetter',
                directoryPartnersForm: 'directoryPartnersFormGetter',
                directoryBanksList: 'directoryBanksListGetter',
            }),
            labelComputed(){
                return this.isGroup ? "Группа" : "Контрагент"
            },
            labelModalComputed(){
                return this.isGroup ? "Создание группы" : "Создание контрагента"
            },
            validationsComputed() {
                if( 
                    this.$v.formPartner.name.$invalid == false && 
                    this.$v.formPartner.fullname.$invalid  == false  && 
                    this.$v.formPartner.address.$invalid  == false &&
                    this.isGroup == false
                ){
                    return true
                }
                else if( 
                    this.$v.formGroupe.name.$invalid == false && 
                    this.$v.formGroupe.description.$invalid == false && 
                    this.isGroup == true
                ){
                    return true
                }
                else {
                    return false  
                }
            }
        },
        methods: {
            ...mapActions({
                directoryPartnersAdd: 'directoryPartnersAddActions',  
                directoryPartnersAddByInn: 'directoryPartnersAddByInnActions',
            }),
            hideModal(){
                this.$refs['directory-partners-modal-add'].hide();
            },
            async addInn(){
                await this.directoryPartnersAddByInn(this.inn)
                
                this.formPartner.name = this.directoryPartnersForm.name
                this.formPartner.fullname = this.directoryPartnersForm.fullname
                this.formPartner.address = this.directoryPartnersForm.address
                this.formPartner.type = this.directoryPartnersForm.type
                this.formPartner.ogrn = this.directoryPartnersForm.ogrn
                this.formPartner.ogrn_date = this.directoryPartnersForm.ogrn_date
                this.formPartner.inn = this.directoryPartnersForm.inn
            },
            async addPartner(){
                this.$v.$touch(); 
                if(this.validationsComputed){

                    if(this.isGroup == true){
                        await this.directoryPartnersAdd(this.formGroupe)
                    }else {
                        await this.directoryPartnersAdd(this.formPartner)
                    }
                    
                    if(!this.directoryPartnersError){
                        this.hideModal()
                    }
                }
            },
            mountedData(){  

                this.inn = null
                this.formGroupe.name = null
                this.formGroupe.description = null
                this.formGroupe.isGroup = this.isGroup

                this.formPartner.name = null
                this.formPartner.fullname = null
                this.formPartner.address = null
                this.formPartner.type = null
                this.formPartner.ogrn = null
                this.formPartner.ogrn_date = null
                this.formPartner.inn = null
                this.formPartner.isGroup = this.isGroup

               
            }
        }
    }
</script>