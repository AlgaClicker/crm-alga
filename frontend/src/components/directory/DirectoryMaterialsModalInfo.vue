<template>
    <b-modal 
        id="directory-materials-modal-info"
        ref="directory-materials-modal-info"
        title="Новый сотрудник"
        content-class="c-modal-default"
        hide-footer
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Информация </h5>
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
                        disabled
                        v-model="directoryMaterialsSelected.name"
                        placeholder="Введите название группы"
                        trim
                    ></b-form-input>
                </b-col>
                <b-col class="mt-2" cols="12">
                    <label class="label-custom" for="input-name">Описание</label>
                    <b-form-textarea
                        id="input-description"
                        class="input-custom"
                        aria-describedby="input-description-feedback"
                        disabled
                        v-model="directoryMaterialsSelected.description"
                        placeholder="Введите описание..."
                        trim
                        rows="3"
                        max-rows="12"
                        no-resize
                    ></b-form-textarea>
                </b-col>
            </b-row>
            <b-row>
                <b-row class="mt-2">
                    <b-col cols="6">
                        <label class="label-custom" for="input-live">Код</label>
                        <b-form-input
                            id="input-live"
                            aria-describedby="input-live-help input-live-feedback"
                            class="input-custom"
                            v-model="directoryMaterialsSelected.code"
                            disabled
                            placeholder="Введите код"
                        ></b-form-input>
                    </b-col>
                    <b-col cols="6">
                        <label class="label-custom" for="input-live">Производитель</label>
                        <b-form-input
                            id="input-live"
                            aria-describedby="input-live-help input-live-feedback"
                            disabled
                            class="input-custom"
                            v-model="directoryMaterialsSelected.vendor"
                            placeholder="Введите название владельца"
                        ></b-form-input>
                    </b-col>
                </b-row>
                <b-row class="mt-2 mb-4">
                    <b-col cols="6">
                        <label class="label-custom" for="input-live">Артикул</label>
                        <b-form-input
                            id="input-live"
                            aria-describedby="input-live-help input-live-feedback"
                            class="input-custom"
                            disabled
                            v-model="directoryMaterialsSelected.articul"
                            placeholder="Введите артикул"
                        ></b-form-input>
                    </b-col>
                    <b-col cols="6">
                        <label class="label-custom" for="input-live">Единица измерения</label>
                        <b-form-select 
                            style="width: 100%;"
                            v-model="unit_code" 
                            :options="unitsProps"
                        >
                        </b-form-select>
                    </b-col>
                </b-row>
            </b-row>
        </div>
    </b-modal>
</template>
<script>
    import { mapGetters } from 'vuex'

    export default {
        name: 'DirectoryMaterialsModalInfo',
        data() {
            return {
                unit_code: ''
            }
        },
        props: {
            unitsProps: { type: Array, default: function () {} },
        },
        computed: {
            ...mapGetters({
                directoryMaterialsSelected: 'directoryMaterialsSelectedGetter'
            }),
        },
        methods: {
            mountedData(){
               this.unit_code = this.directoryMaterialsSelected.unit.code
            },
            hideModal(){
                this.$refs['directory-materials-modal-info'].hide();
            }
        }
    }
</script>