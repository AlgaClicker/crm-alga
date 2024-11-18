<template>
    <b-modal
        id="master-add-specifications-for-brigade"
        ref="master-add-specifications-for-brigade"
        centered 
        content-class="c-modal-default c-master-add-specifications-for-brigade"
    >
        <template #modal-header>
            <h5> Назначить спецификацию </h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-modal-master-add-employess-body"> 
            <b-row>
                <label class="label-custom  mt-2" for="input-name">Дата</label>
                <date-picker
                    style="width: 100%" 
                    input-class='mx-input-modal'
                    v-model="dateEnd"
                >
                </date-picker>
            </b-row>
            <b-row>
                <b-col class="mt-2" sm="12" lg="12">
                    <label class="label-custom" for="input-name">Прикрепить спецификацию</label>
                    <multiselect 
                        v-model="selectSpec"  
                        placeholder="Выберите спецификацию" 
                        :options="specificationsList" 
                        label="name"
                        track-by="name"
                        :show-labels="false"
                    >
                        <template slot="option" slot-scope="props">
                            <div class="option__desc">
                                <div class="c-avatar-list">
                                    <div class="c-list-spec">
                                        <span class="ml-1"> {{ props.option.name }} </span>
                                        <span class="c-list-spec__object"> ( {{ props.option?.objectName }} )</span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </multiselect>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="addSpecifications" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Добавить
            </button>
        </template>
    </b-modal>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex';
    import { required } from 'vuelidate/lib/validators';
    import { validationMixin } from "vuelidate";
    import { dateMin } from '@/utils/customValidations';

    export default {
        name: 'MasterAddSpecificationsForBrigade',
        data(){
            return {
                specificationId: '',
                dateEnd: '',
                selectSpec: null
            }
        },
        mixins: [validationMixin],
        validations: {
            selectSpec: {
                required,
            },
            dateEnd: {
                required,
                dateMin
            }
        },
        props: {
            specificationsList: { type: Array, default: () => [] },
        },
        computed: {
            ...mapGetters({
                masterError: 'masterErrorGetter'
            }),
            validationsComputed(){
                if( 
                    this.$v.selectSpec.$invalid  == false &&
                    this.$v.dateEnd.$invalid  == false 
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
                masterBrigadeAddSpecifications: 'masterBrigadeAddSpecificationsActions'
            }),
            hideModal(){
                this.$refs['master-add-specifications-for-brigade'].hide();
            },
            async addSpecifications(){
                await this.masterBrigadeAddSpecifications({
                    specificationId: this.selectSpec.id,
                    dateEnd: this.dateEnd,
                })

                if(!this.masterError){
                    this.hideModal()
                }
            },
        }
    }
</script>