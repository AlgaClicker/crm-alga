<template>
    <b-modal 
        id="directory-materials-modal-edit"
        ref="directory-materials-modal-edit"
        centered 
        content-class="c-modal-default"
        @shown="mountedData"
        @hide="beforeHideModal"
    >
        <template #modal-header>
            <h4>Редактирование материала</h4>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col class="mt-2" cols="12">
                    <label class="label-custom" for="input-live">Название</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        aria-describedby="input-live-help input-live-feedback"
                        v-model="formMaterial.name"
                        v-model.trim="$v.formMaterial.name.$model"
                        :state="!$v.formMaterial.name.$error && null"
                        placeholder="Введите название группы"
                        trim
                        ref="focusInput"
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
                        max-rows="12"
                        no-resize
                    ></b-form-textarea>
                </b-col>
            </b-row>
            <b-row class="mt-2">
                <b-col cols="6">
                    <label class="label-custom" for="input-live">Код</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        aria-describedby="input-live-help input-live-feedback"
                        v-model="formMaterial.code"
                        placeholder="Введите код"
                    ></b-form-input>
                </b-col>
                <b-col cols="6">
                    <label class="label-custom" for="input-live">Производитель</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        aria-describedby="input-live-help input-live-feedback"
                        v-model="formMaterial.vendor"
                        placeholder="Введите название владельца"
                    ></b-form-input>
                </b-col>
            </b-row>
            <b-row class="mt-2">
                <b-col cols="6">
                    <label class="label-custom" for="input-live" >Артикул</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        aria-describedby="input-live-help input-live-feedback"
                        v-model="formMaterial.articul"
                        placeholder="Введите код"
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
        <template #modal-footer>
            <button 
                @click="editForm"  
                :disabled="!validationsComputed"
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
            >
                Сохранить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from "vuex";
    import { required, minLength } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";

    export default {
        name: 'DirectoryMaterialsModalEdit',
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
            }
        },
        props: {    
            unitsProps: { type: Array, default: () => [] },
        },
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                materialsError: 'materialsErrorGetter',
                directoryMaterialsSelectedList: 'directoryMaterialsSelectedListGetter'
            }),
            validationsComputed() {
                if( this.$v.formMaterial.name.$invalid  == false && 
                    this.$v.formMaterial.unit_code.$invalid == false && !this.isGroup
                ){
                    return true
                }
                else {
                    return false  
                }
            }
        },
        validations: {
            formMaterial: {
                parent: { required },
                description: {required },
                name: { 
                    required,
                    minLength: minLength(6)
                },
                unit_code: { required }
            },
        },
        methods: {
            ...mapActions({
                materialsEdit: 'materialsEditActions',
                directoryMaterialsClearSelectedList: 'directoryMaterialsClearSelectedListActions'
            }),
            mountedData(){
                this.formMaterial = {
                    id: this.directoryMaterialsSelectedList[0].id,
                    name: this.directoryMaterialsSelectedList[0].name,
                    description: this.directoryMaterialsSelectedList[0].description,
                    isGroup: this.directoryMaterialsSelectedList[0].isGroup,
                    code: this.directoryMaterialsSelectedList[0]?.code,
                    vendor: this.directoryMaterialsSelectedList[0].vendor,
                    articul: this.directoryMaterialsSelectedList[0].articul,
                    unit_code: this.directoryMaterialsSelectedList[0].unit?.code,
                }
                
                if( typeof this.directoryMaterialsSelectedList[0].parent !== 'undefined' ){
                    this.formMaterial.parent = this.directoryMaterialsSelectedList[0].parent.id
                }

                this.$refs.focusInput.focus()
            },
            hideModal(){
                this.error = null;
                this.$refs['directory-materials-modal-edit'].hide();
            },
            beforeHideModal(){
                this.directoryMaterialsClearSelectedList()
            },
            async editForm(){
                await this.materialsEdit(this.formMaterial)
                if(!this.materialsError){
                    this.hideModal()
                }
            }
        }
    }
</script>

<style>
</style>