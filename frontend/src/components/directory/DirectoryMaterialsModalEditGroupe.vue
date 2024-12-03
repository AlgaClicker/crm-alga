<template>
    <b-modal 
        id="directory-materials-modal-edit-groupe"
        ref="directory-materials-modal-edit-groupe"
        centered 
        content-class="c-modal-default"
        @shown="mountedData"
    >
        <template #modal-header>
            <h4>Редактировать группу</h4>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col class="mt-2" cols="12">
                    <label class="label-custom" for="input-live">Название</label>
                    <b-form-input
                        id="input-name"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.formGroupe.name.$error }"
                        aria-describedby="input-name-help input-name-feedback"
                        v-model="formGroupe.name"
                        v-model.trim="$v.formGroupe.name.$model"
                        :state="!$v.formGroupe.name.$error && null"
                        placeholder="Введите название группы"
                        trim
                        ref="focusInput"
                    ></b-form-input>
                        <!-- This will only be shown if the preceding input has an invalid state -->
                        <b-form-invalid-feedback id="input-name">
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
                        no-resize
                    ></b-form-textarea>
                    <b-form-invalid-feedback id="input-description">
                        Описание должно быть длиннее шести символов 
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <small>{{ materialsError }}</small>
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
        name: 'DirectoryMaterialsModalEditGroupe',
        data(){
            return {
                formGroupe: {
                    id: '',
                    parent: '',
                    name: '',
                    description: ''
                },
            }
        },
        mixins: [validationMixin],
        validations: {
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
        computed: {
            ...mapGetters({
                materialsError: 'materialsErrorGetter',
                directoryMaterialsSelectedList: 'directoryMaterialsSelectedListGetter',
            }),
            validationsComputed() {
                if( this.$v.formGroupe.name.$invalid == false && 
                    this.$v.formGroupe.description.$invalid == false
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
                materialsEdit: 'materialsEditActions'
            }),
            mountedData(){
                this.formGroupe = {
                    id: this.directoryMaterialsSelectedList[0].id,
                    name: this.directoryMaterialsSelectedList[0].name,
                    description: this.directoryMaterialsSelectedList[0].description,
                    isGroup: this.directoryMaterialsSelectedList[0].isGroup,
                }       

                if( typeof this.directoryMaterialsSelectedList[0].parent !== 'undefined' ){
                    this.formGroupe.parent = this.directoryMaterialsSelectedList[0].parent.id
                } 

                this.$refs.focusInput.focus()
            },
            async editForm(){
                await this.materialsEdit(this.formGroupe)
                if(!this.materialsError){
                    this.hideModal()
                }
            },
            hideModal(){
                this.$refs['directory-materials-modal-edit-groupe'].hide();
            },
        },
    }
</script>