<template>
    <b-modal 
        id="directory-posts-modal-add"
        ref="directory-posts-modal-add"
        title="Новая должность"
        content-class="c-modal-default"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Новая должность</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col class="mt-2" cols="12">
                    <label class="label-custom" for="input-name">Название</label>
                    <b-form-input
                        id="input-name"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.name.$error}"
                        aria-describedby="input-name-feedback"
                        v-model="form.name"
                        v-model.trim="$v.form.name.$model"
                        :state="!$v.form.name.$error && null"
                        placeholder="Введите название должности"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-name-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="12">
                    <label class="label-custom" for="input-fullname">Полное название</label>
                    <b-form-input
                        id="input-fullname"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.fullname.$error}"
                        aria-describedby="input-fullname-feedback"
                        v-model="form.fullname"
                        v-model.trim="$v.form.fullname.$model"
                        :state="!$v.form.fullname.$error && null"
                        placeholder="Введите имя"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-fullname-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="addPost" class="mt-4" 
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
        name: 'DirectoryPostsModalAdd',
        data(){
            return {
                form: {
                    name: '',
                    fullname: ''
                }
            }
        },
        validations: {
            form: {
                name: { required }, 
                fullname: { required }
            }
        },
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                directoryPostsEditLoading: 'directoryPostsEditLoadingGetter',
                directoryPostsError: 'directoryPostsErrorGetter'
            }),
            validationsComputed() {
                if( 
                    this.$v.form.name.$invalid  == false && 
                    this.$v.form.fullname.$invalid  == false 
                ){
                    return true
                }else {
                    return false
                }
            }
        },
        methods: {
            ...mapActions({
                directoryPostsAdd: 'directoryPostsAddActions',
                directoryPostsGetList: 'directoryPostsGetListActions',   
            }),
            async addPost(){
                this.$v.$touch(); 
                if(!this.$v.$invalid){
                    await this.directoryPostsAdd(this.form)
                    if(!this.directoryPostsError){
                        this.directoryPostsGetList()
                        this.hideModal()
                    }
                }
            },
            hideModal(){
                this.$refs['directory-posts-modal-add'].hide();
            },
            mountedData(){
                this.form.name = ''
                this.form.fullname = ''
                this.$v.$reset()
            }
        }
    }
</script>