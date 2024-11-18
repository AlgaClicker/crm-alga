<template>
     <b-modal 
        id="directory-posts-modal-edit"
        ref="directory-posts-modal-edit"
        title="Редактировать должность"
        content-class="c-modal-default"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Редактировать должность</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col class="mt-2" cols="12">
                    <label class="label-custom" for="input-name">Имя</label>
                    <b-form-input
                        id="input-name"
                        class="input-custom"
                        aria-describedby="input-name-feedback"
                        v-model="form.name"
                        v-model.trim="$v.form.name.$model"
                        :state="!$v.form.name.$error && null"
                        placeholder="Введите имя"
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
                @click="editPost" variant="dark" class="mt-4"
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Сохранить
            </button>
            <button 
                @click="deletePost()"   
                class="c-button-delete--success mt-4"
                v-b-modal.base-delete-modal
            >
                Удалить
            </button>
        </template>
     </b-modal>
</template>
<script>
    import { mapActions, mapGetters } from "vuex";
    import { required } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";

    export default {
        name: 'DirectoryPostsModalEdit',
        data(){
            return {
                form: {
                    name: '',
                    fullname: '',
                    id: ''
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
        props: {
            editPostProps: { type: Object, default: () => {} },
        },
        computed: {
            ...mapGetters({
                directoryPostsEditLoading: 'directoryPostsEditLoadingGetter',
                directoryPostsError: 'directoryPostsErrorGetter',
                directoryPostsSelectedList: 'directoryPostsSelectedListGetter'
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
                directoryPostsEdit: 'directoryPostsEditActions',
                setParamsDeleteModal: 'setParamsDeleteModalActions',   
            }),
            async editPost(){
                this.$v.$touch(); 
                await this.directoryPostsEdit(this.form)
                if(!this.directoryPostsEditLoading){
                    this.hideModal()
                }
            },
            hideModal(){
                this.$refs['directory-posts-modal-edit'].hide();
            },
            deletePost(){
                this.setParamsDeleteModal(
                    {
                        title: 'должность',
                        actionsName: 'directoryPostsDeleteActions',
                        data: [ this.form ]
                    }
                )
                this.hideModal()
            },
            mountedData(){
                this.form = this.editPostProps
                this.$v.$reset()
            }
        }
    }
</script>