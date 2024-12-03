<template>
     <b-modal 
        id="sepcification-add-izm"
        ref="sepcification-add-izm"
        title="Добавить материал"
        content-class="c-modal-default sepcification-modal-add-izm"
        centered    
        @shown="mountedData"
    >   
        <template #modal-header>
            <h5>Создать ИЗМ для: {{ specification.name }}</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body sepcification-modal-add-izm_body">
            <b-row>
                <b-col class="mb-4" cols="12">
                    <label class="label-custom" for="input-name">Название изма</label>
                    <b-form-input
                        id="input-name"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.name.$error }"
                        v-model="form.name"
                        v-model.trim="$v.form.name.$model"
                        :state="!$v.form.name.$error && null"
                        aria-describedby="input-name-help input-name-feedback"
                        placeholder="Введите название изма"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-name-feedback">
                        Название не должно быть короче шести символов
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mb-4" cols="12">
                    <label class="label-custom"  for="input-description">Описание</label>
                    <b-form-input
                        id="input-description"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.description.$error }"
                        v-model="form.description"
                        v-model.trim="$v.form.description.$model"
                        :state="!$v.form.description.$error && null"
                        aria-describedby="input-description-help input-description-feedback"
                        placeholder="Введите описание"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-description-feedback">
                        Название не должно быть короче шести символов
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="addIZM" 
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
    
    import { required, minLength } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";

    export default {
        name: 'SpecificationAddIZM',
        data(){
            return {
                form: {
                    objectId: '',
                    name: '',
                    description: '',
                    locnum: '',
                },
            }
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
            }
        },
        computed: {
            ...mapGetters({
                specificationError: "specificationErrorGetter",
                specification: "specificationGetter"
            }),
            validationsComputed() {
                if( this.$v.form.name.$invalid  == false && 
                    this.$v.form.description.$invalid == false 
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
                specificationAddIZM: "specificationAddIZMActions",
            }),
            hideModal(){
                this.$refs['sepcification-add-izm'].hide();
            },
            async addIZM(){
                var params = {
                    parent: this.parentSpecificationProps,
                    form: this.form
                }
                await this.specificationAddIZM(params)
                
                this.$router.push(`crm/specification/${this.specification.id}`)
            },
            mountedData(){
                this.form.name = ''
                this.form.description = ''
                this.$v.$reset()
            }
        }
    }
</script>
