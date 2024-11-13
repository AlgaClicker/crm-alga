<template>
    <b-modal 
        id="directory-partners-modal-edit-groupe"
        ref="directory-partners-modal-edit-groupe"
        centered 
        content-class="c-modal-base c-modal-directory c-modal-partner"
        @shown="mountedData"
    >
        <template #modal-header>
            <h4>Редактировать группу</h4>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body-partner">
            <b-row>
                <b-col class="mt-2" cols="12">
                    <label class="label-custom" for="input-name">Название</label>
                    <b-form-input
                        id="input-name"
                        aria-describedby="input-live-help input-name-feedback"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.name.$error}"
                        v-model="form.name"
                        v-model.trim="$v.form.name.$model"
                        :state="!$v.form.name.$error && null"
                        placeholder="Введите название группы"
                        trim
                    ></b-form-input>
                        <!-- This will only be shown if the preceding input has an invalid state -->
                    <b-form-invalid-feedback id="input-name-feedback">
                        Название должно быть длиннее шести символов 
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="12">
                    <label class="label-custom" for="input-description">Название</label>
                    <b-form-input
                        id="input-description"
                        aria-describedby="input-live-help input-description-feedback"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.description.$error}"
                        v-model="form.description"
                        v-model.trim="$v.form.description.$model"
                        :state="!$v.form.description.$error && null"
                        placeholder="Введите название группы"
                        trim
                    ></b-form-input>
                        <!-- This will only be shown if the preceding input has an invalid state -->
                    <b-form-invalid-feedback id="input-description-feedback">
                        Название должно быть длиннее шести символов 
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="editPartnerGroupe" class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Добавить
            </button>
        </template>
    </b-modal>
</template>
<script>
    import { mapActions, mapGetters } from "vuex";
    import { required } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";

    export default {
        name: 'DirectoryPartnersModalEditGroupe',
        data(){
            return {
                form: {
                    name: '',
                    description: ''
                }
            }
        },
        validations: {
            form: {
                name: { required },
                description: { required }
            }
        },
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                directoryPartnersSelectedList: 'directoryPartnersSelectedListGetter'
            }),
            validationsComputed() {
                if( 
                    this.$v.form.name.$invalid == false && 
                    this.$v.form.fullname.$invalid == false
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
                directoryPartnersEdit: 'directoryPartnersEditActions'
            }),
            hideModal(){
                this.$refs['directory-partners-modal-edit-groupe'].hide();
            },
            async editPartnerGroupe(){
                if(this.validationsComputed){
                    console.log('edit')
                }
            },
            mountedData(){
                this.name = this.directoryPartnersSelectedList[0].name
                this.description = this.directoryPartnersSelectedList[0].description

                this.$v.$reset()
            }
        }
    }
</script>