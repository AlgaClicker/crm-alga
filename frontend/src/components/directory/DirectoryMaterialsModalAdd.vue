<template>
    <b-modal 
        id="directory-materials-modal-add"
        ref="directory-materials-modal-add"
        centered 
        content-class="c-modal-default"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>{{ labelModalComputed }}</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-form-group>
                    <b-form-radio @click="clearForm" v-model="isGroup" name="some-radios" :value=true> Группа</b-form-radio>
                    <b-form-radio @click="clearForm" v-model="isGroup" name="some-radios" :value=false> Материал</b-form-radio>
                </b-form-group>
            </b-row>
            <div v-if="isGroup">
                <b-row>
                    <b-col class="mt-2" cols="12">
                        <label class="label-custom" for="input-live">Название</label>
                        <b-form-input
                            id="input-live"
                            aria-describedby="input-live-help input-live-feedback"
                            class="input-custom"
                            :class="{ 'input-custom--invalid': $v.formGroupe.name.$error}"
                            v-model="formGroupe.name"
                            v-model.trim="$v.formGroupe.name.$model"
                            :state="!$v.formGroupe.name.$error && null"
                            placeholder="Введите название группы"
                            trim
                        ></b-form-input>
                        <b-form-invalid-feedback id="input-live-feedback">
                            Название должно быть длиннее шести символов 
                        </b-form-invalid-feedback>
                    </b-col>
                    <b-col class="mt-2" cols="12">
                        <label class="label-custom" for="input-live">Описание</label>
                        <b-form-textarea
                            id="input-description"
                            class="input-custom"
                            :class="{ 'input-custom--invalid': $v.formGroupe.description.$error}"
                            aria-describedby="input-description-feedback"
                            v-model="formGroupe.description"
                            v-model.trim="$v.formGroupe.description.$model"
                            :state="!$v.formGroupe.description.$error && null"
                            placeholder="Введите описание..."
                            trim
                            rows="3"
                            max-rows="12"
                            no-resize
                        ></b-form-textarea>
                        <b-form-invalid-feedback id="input-live-feedback">
                            Описание должно быть длиннее шести символов 
                        </b-form-invalid-feedback>
                    </b-col>
                </b-row>
            </div>
            <div v-else>
                <b-row>
                    <b-col class="mt-2" cols="12">
                        <label class="label-custom" for="input-live">Название</label>
                        <b-form-input
                            id="input-live"
                            aria-describedby="input-live-help input-live-feedback"
                            class="input-custom"
                            :class="{ 'input-custom--invalid': $v.formMaterial.name.$error}"
                            v-model="formMaterial.name"
                            v-model.trim="$v.formMaterial.name.$model"
                            :state="!$v.formMaterial.name.$error && null"
                            placeholder="Введите название материала"
                            trim
                        ></b-form-input>
                        <b-form-invalid-feedback id="input-live-feedback">
                            Название должно быть длиннее шести символов 
                        </b-form-invalid-feedback>
                    </b-col>
                    <b-col class="mt-2" cols="12">
                        <label class="label-custom" for="input-live">Описание</label>
                        <b-form-textarea
                            id="input-description"
                            class="input-custom"
                            :class="{ 'input-custom--invalid': $v.formMaterial.description.$error}"
                            aria-describedby="input-description-feedback"
                            v-model="formMaterial.description"
                            v-model.trim="$v.formMaterial.description.$model"
                            :state="!$v.formMaterial.description.$error && null"
                            placeholder="Введите описание..."
                            trim
                            rows="3"
                            no-resize
                        ></b-form-textarea>
                        <b-form-invalid-feedback id="input-description">
                            Название должно быть длиннее шести символов 
                        </b-form-invalid-feedback>
                    </b-col>
                </b-row>
                <b-row class="mt-2">
                    <b-col cols="6">
                        <label class="label-custom" for="input-live">Код</label>
                        <b-form-input
                            id="input-live"
                            aria-describedby="input-live-help input-live-feedback"
                            class="input-custom"
                            v-model="formMaterial.code"
                            placeholder="Введите код"
                        ></b-form-input>
                    </b-col>
                    <b-col cols="6">
                        <label class="label-custom" for="input-live">Производитель</label>
                        <b-form-input
                            id="input-live"
                            aria-describedby="input-live-help input-live-feedback"
                            class="input-custom"
                            v-model="formMaterial.vendor"
                            placeholder="Введите название владельца"
                        ></b-form-input>
                    </b-col>
                </b-row>
                <b-row class="mt-2">
                    <b-col cols="6">
                        <label class="label-custom" for="input-live">Артикул</label>
                        <b-form-input
                            id="input-live"
                            aria-describedby="input-live-help input-live-feedback"
                            class="input-custom"
                            v-model="formMaterial.articul"
                            placeholder="Введите артикул"
                        ></b-form-input>
                    </b-col>
                    <b-col cols="6">
                        <label class="label-custom" for="input-live">Единица измерения</label>
                        <b-form-select 
                            style="width: 100%"
                            v-model="formMaterial.unit_code" 
                            :options="unitsProps"
                        >
                        </b-form-select>
                    </b-col>
                </b-row>
            </div>
        </div>
        <template #modal-footer>
            <button 
                @click="addMaterial" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
            >
                Сохранить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from "vuex"
    import { required, minLength } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate"

    export default {
        name: 'DirectoryMaterialsModalNew',
        data(){
            return {
                isGroup: false,
                formMaterial: {
                    parent: '',
                    name: '',
                    description: '',
                    code: '',
                    vendor: '',
                    articul: '',
                    isGroup: false,
                    unit_code: ''
                },
                formGroupe: {
                    parent: '',
                    name: '',
                    description: '',
                    isGroup: false,
                },
            } 
        },
        props: {
            unitsProps: { type: Array, default: function () {} },
            materialGroupeIdProps: { type: String, default: "" }
        },
        computed: {
            ...mapGetters({
                materialsList: 'materialsListGetter',
                materialsError: 'materialsErrorGetter'
            }),
            labelComputed(){
                return this.isGroup ? "Группа" : "Материал"
            },
            labelModalComputed(){
                return this.isGroup ? "Создание группы" : "Создание материала"
            },
            validationsComputed() {
                if( this.$v.formMaterial.name.$invalid  == false && 
                    this.$v.formMaterial.unit_code.$invalid == false && !this.isGroup
                ){
                    return true
                }
                else if( this.$v.formGroupe.name.$invalid == false && this.isGroup){
                    return true
                }
                else {
                    return false  
                }
            }
        },
        mixins: [validationMixin],
        validations: {
            formMaterial: {
                parent: { required },
                name: { 
                    required,
                    minLength: minLength(6)
                },
                description: {
                    required,
                },
                unit_code: { required }
            },
            formGroupe: {
                parent: { required },
                name: { 
                    required,
                    minLength: minLength(6)
                },
                description: { 
                    required,
                    minLength: minLength(6)
                },
            }
        },
        methods: {
            ...mapActions({
                materialsAdd: 'materialsAddActions', 
                materialsGet: 'materialsGetActions', 
            }),
            hideModal(){
                this.$refs['directory-materials-modal-add'].hide();
            },
            clearForm(){
                this.formMaterial = {
                    parent: '',
                    name: '',
                    description: '',
                    code: '',
                    vendor: '',
                    articul: '',
                    isGroup: false,
                    unit_code: ''
                }
                this.formGroupe = {
                    parent: '',
                    name: '',
                    description: '',
                    isGroup: false,
                }
            },
            async addMaterial(){
                this.$v.$touch(); 
                if(this.validationsComputed){
                    if(this.isGroup == true){
                        this.formGroupe = {
                            parent: this.materialGroupeIdProps,
                            name: this.formGroupe.name,
                            description: this.formGroupe.description,
                            isGroup: true,
                        }   
                        await this.materialsAdd(this.formGroupe)
                        if(!this.materialsError){
                            this.hideModal()
                        }
                    }else{
                        this.formMaterial.parent = this.materialGroupeIdProps
                        await this.materialsAdd(this.formMaterial)
                        if(!this.materialsError){
                            //this.materialsGet()
                            this.hideModal()
                        }
                    }
                }
                
            },
            mountedData(){  
                this.formGroupe.name = ''
                this.formGroupe.description = ''
                this.formGroupe.isGroup = false 

                this.formMaterial.parent = ''
                this.formMaterial.name = ''
                this.formMaterial.description = ''
                this.formMaterial.code = ''
                this.formMaterial.vendor = ''
                this.formMaterial.articul = ''
                this.formMaterial.isGroup = false
                this.formMaterial.unit_code = ''

                this.$v.$reset()
            }
        },
    }
</script>

<style>
</style>