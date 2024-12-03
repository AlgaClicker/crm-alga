<template>
    <b-modal 
        id="tabel-edit-mark-modal"
        ref="tabel-edit-mark-modal"
        title="Добавить отметку"
        content-class="c-modal-default c-tabel-edit-mark-modal"
        centered    
        @shown="mountedData"
    >   
        <template #modal-header>
            <h5>Отметка на {{ tabelEditCell.date }}</h5>
            <b-icon
                @click="hideModal()"
                icon="x"
                scale="1.1"
            >
            </b-icon>
        </template>
        <div class="c-body">
            <div>
                <label class="label-custom" for="input-name">Отметка</label>
            </div>
            <b-form-select 
                class="c-select"
                v-model="mark" 
                :options="options"
            >
            </b-form-select>
            <div class="mt-2" v-show="typeof masterBrigadeSpecificationsList != 'undefined' ">
                <label class="label-custom" for="input-name">Выбрать спецификацию</label>
                <multiselect 
                    v-model="specification"  
                    placeholder="Выберите спецификацию" 
                    :options="specificationComputed" 
                    label="name"
                    track-by="name"
                    :show-labels="false"
                >
                    <template slot="option" slot-scope="props">
                        <div class="option__desc">
                            <div class="c-avatar-list">
                                <span class="ml-1"> {{ props.option.name }} </span>
                            </div>
                        </div>
                    </template>
                </multiselect>
            </div>
            <div> 
                <label class="label-custom mt-2">Комментарий</label>
                <b-form-textarea
                    id="input-description"
                    class="input-custom"
                    v-model="description"
                    placeholder="Введите описание..."
                    trim
                    rows="3"
                    no-resize
                ></b-form-textarea>
            </div>
        </div>
        <template #modal-footer>
            <button
                @click="editMark()" 
                class="mt-4 " 
                :disabled="!validationsComputed" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
            >
                Сохранить
            </button>
            <button @click="deleteMark()" class="mt-4 ml-1 c-button-save c-button-save-sm--success  button">Удалить</button>
        </template>
    </b-modal>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import { required } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";

    export default {
        name: 'TabelEditMarkModal',
        data(){
            return {
                mark: '',
                specification: {},
                description: '',
                options: [
                    { text: '1 час', value: '1' },
                    { text: '2 час', value: '2' },
                    { text: '3 час', value: '3' },
                    { text: '4 час', value: '4' },
                    { text: '5 час', value: '5' },
                    { text: '6 час', value: '6' },
                    { text: '7 час', value: '7' },
                    { text: '8 час', value: '8' },
                    { text: '9 час', value: '9' },
                    { text: '10 час', value: '10' },
                    { text: '11 час', value: '11' },
                    { text: '12 час', value: '12' },
                    { text: 'больничный', value: 'б' },
                    { text: 'отпуск', value: 'о' },
                    { text: 'прогул по неувож', value: 'ну' },
                    { text: 'прогул по увож', value: 'нп' },
                    { text: 'дополнительный отгул', value: 'до' },
                ]
            }
        },
        validations: {
            mark: { required },
        },
        mixins: [validationMixin],
        props: {
            workpeopleProps: { type: Object, default: () => {}}
        },
        computed: {
            ...mapGetters({
                tabelEditCell: 'tabelEditCellGetter',
                tabelError: 'tabelErrorGetter',
                mastersBrigade: 'masterBrigadeGetter',
                masterBrigadeSpecificationsList: 'masterBrigadeSpecificationsListGetter'
            }),
            validationsComputed() {
                if( 
                    this.$v.mark.$invalid == false
                ){
                    return true
                }else {
                    return false
                }
            },  
            specificationComputed(){
                return this.masterBrigadeSpecificationsList.map(item => item.specification)
            }
        },
        methods: {
            ...mapActions({
                tabelAddMark: 'tabelAddMarkActions',
                tabelEditMark: 'tabelEditMarkActions',
                tabelDeleteMark: 'tabelDeleteMarkActions'
            }),
            async editMark(){
                
                if( typeof this.tabelEditCell.time_amount != 'undefined' & this.tabelEditCell.time_amount != '' ){
                    await this.tabelEditMark({
                        idWorkpeople: this.workpeopleProps.id,
                        date: this.tabelEditCell.date,
                        descriptions: this.description,
                        timeAmount: this.mark,
                        specificationId: typeof this.specification?.id == 'undefined' ? '' : this.specification.id
                    })
                } else {
                    await this.tabelAddMark({
                        idWorkpeople: this.workpeopleProps.id,
                        date: this.tabelEditCell.date,
                        descriptions: this.description,   
                        timeAmount: this.mark,
                        specificationId: typeof this.specification?.id == 'undefined' ? '' : this.specification.id
                    })
                }

                if(!this.tabelError){
                    this.hideModal()
                }
            },
            deleteMark(){
                this.tabelDeleteMark(
                    {
                        date: this.tabelEditCell.date,
                        idWorkpeople: this.workpeopleProps.id
                    }
                )
                this.hideModal()
            },
            hideModal(){
                this.$refs['tabel-edit-mark-modal'].hide();
            },
            mountedData(){
                this.description = this.tabelEditCell.description
                this.specification = this.tabelEditCell.specification_id 
                this.mark = this.tabelEditCell.time_amount
            }
        }
    }
</script>