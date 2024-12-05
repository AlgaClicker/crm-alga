<template>
    <b-modal
        id="finance-payments-modal-add-spec"
        ref="finance-payments-modal-add-spec"
        centered 
        content-class="c-modal-default c-finance-payments-modal-add-spec"
    >
        <template #modal-header>
            <label class="c-header-label c-link-label">
                <base-icon iconProps="link" sizeProps="md" />
                Прикрепить спецификацию
            </label>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-body-payments-add-spec">
            <b-row>
                <b-col class="mt-2" cols="12">
                    <label class="label-custom" for="input-surname">Выберите спецификацию</label>
                    <multiselect 
                        v-model="specification" 
                        :options="specificationsListProps"
                        placeholder="Выберите спецификацию" 
                        label="name"
                        track-by="name"
                        :show-labels="false"
                    >
                        <template slot="option" slot-scope="props">
                            {{ props.option.name }}
                        </template>
                    </multiselect>   
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="addSpec"  
                class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Прикрепить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from "vuex";
    import { required } from 'vuelidate/lib/validators';
    import { validationMixin } from "vuelidate";
    import BaseIcon from "@/components/elements/BaseIcon"

    export default {
        name: 'FinancePaymentsModalAddSpec',
        data(){
            return {
                specification: null,
            }
        },
        mixins: [validationMixin],
        validations: {
            specification: { required },
        },
        components: {
            BaseIcon
        },
        computed: {
            ...mapGetters({
                paymentsError: 'paymentsErrorGetter'
            }),
            validationsComputed(){
                if( 
                    this.$v.specification.$invalid == false
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
                paymentsEdit: 'paymentsEditActions',
            }),
            async addSpec(){
                var form = {}
         
                form.id = this.paymentsProps.id 
                form.date = this.paymentsProps.date
                form.description = this.paymentsProps.description
                form.type = this.paymentsProps.type

                form.specification = this.specification.id
                await this.paymentsEdit(form)

                if(!this.paymentsError){
                    this.hideModal()
                }
            },
            hideModal(){
                this.$refs['finance-payments-modal-add-spec'].hide();
            },
        },
        props: {
            paymentsProps: { type: Object, default: () => {} },
            specificationsListProps: { type: Array, default: function () {} },
        },
    }
</script>

<style lang="scss">
    .c-header-label{
        margin-left: 0px;
    }
</style>