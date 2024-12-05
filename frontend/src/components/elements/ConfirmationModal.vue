<template>
    <b-modal
        id="confirmation-modal"
        ref="confirmation-modal"
        content-class="c-confirmation-modal"
        centered 
        hide-footer
        hide-header
        @shown="mountedData"
    >
        <div class="c-body">
            <div class="c-body__text">
                {{ textProps }}
            </div>
            <b-row>
                <b-col class="mt-2" cols="12">
                    <label class="label-custom" for="input-live">Комментарий</label>
                    <b-form-textarea
                        id="input-description"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.description.$error}"
                        aria-describedby="input-description-feedback"
                        v-model="description"
                        v-model.trim="$v.description.$model"
                        :state="!$v.description.$error && null"
                        placeholder="Введите описание..."
                        trim
                        rows="3"
                        no-resize
                    ></b-form-textarea>
                    <b-form-invalid-feedback id="input-description">
                        Укажите причину
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <div class="c-body__wrapper">
                <button @click="confirm" :class="{ 'button--danger': colorProps == 'danger', 'button--primary': colorProps == 'primary' }">
                    <base-icon iconProps="done_light" sizeProps="md" />
                    Да
                </button>
                <button @click="hideModal">
                    Нет
                </button>
            </div>
        </div>
    </b-modal>
</template>

<script>
    import BaseIcon from "@/components/elements/BaseIcon"
    import { required } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";

    export default {
        name: 'ConfirmationModal',
        data(){
            return {
                description: ''
            }
        },
        emits: ['confirmationEmit'],
        mixins: [validationMixin],
        validations: {
            description: {
                required,
            } 
        },
        props: {
            textProps: { type: String, default: ""},
            colorProps: { type: String, default: "danger" | "primary"},
            isDescription: { type: Boolean, default: false},
        },
        components: {
            BaseIcon
        },
        methods: {
            confirm(){
                if(this.isDescription){
                      this.$v.$touch()
                }
                if(this.isDescription & this.description != ''){
                    this.$emit('confirmationEmit', this.description)
                    this.$refs['confirmation-modal'].hide();
                }
                if(this.isDescription == false){
                    this.$emit('confirmationEmit', '')
                    this.$refs['confirmation-modal'].hide();
                }
            },
            hideModal(){
                this.$refs['confirmation-modal'].hide();
            },
            mountedData(){
                this.$v.$reset()
            }
        }
    }
</script>