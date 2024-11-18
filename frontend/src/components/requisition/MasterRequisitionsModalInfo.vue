<template>
    <b-modal
        id="master-requisitions-modal-info"
        ref="master-requisitions-modal-info"
        title="Редактировать универсальную заявку"
        content-class="c-modal-default c-modal-requisition"
        hide-footer
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Заявка {{ requisitionProps.number }}</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>   
        </template>
        <div class="c-body">
            <b-row>
                <b-col cols="6">
                    <b-row>
                        <b-col cols="12">
                            <label class="label-custom  mt-2" for="input-date">Дата</label>
                            <b-form-input
                                id="input-date"
                                aria-describedby="input-live-help input-live-feedback"
                                class="input-custom"
                                :value="requisitionProps.end_at | dateFilter"
                                disabled
                            ></b-form-input>
                        </b-col>
                        <b-col cols="12">
                            <label class="label-custom mt-2" for="input-spec">Спецификация</label>
                            <b-form-input
                                id="input-spec"
                                aria-describedby="input-live-help input-live-feedback"
                                class="input-custom"
                                :value="requisitionProps.specification?.name"
                                disabled
                            ></b-form-input>
                        </b-col>
                    </b-row>
                </b-col>
                <b-col cols="6">
                    <label class="label-custom mt-2" for="input-live">Описание</label>
                    <b-form-textarea
                        id="input-description"
                        class="input-custom"
                        aria-describedby="input-description-feedback"
                        :value="requisitionProps.description"
                        placeholder="Введитите описание"
                        trim
                        rows="5"
                        no-resize
                    ></b-form-textarea>
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="6">
                    <label class="label-custom mt-2" for="input-name">Материал</label>
                </b-col>
                <div class='c-master-requisitions-universal-table mt-2'>
                    <table>
                        <thead>
                            <tr>
                                <th width="40px"></th>
                                <th>Название</th>
                                <th width="10%">Ед.изм</th>
                                <th width="10%">Заказать</th>
                                <th>Комментарий</th>
                            </tr>
                        </thead>
                    </table>
                    <div class='c-master-requisitions-universal-table__body' :style="{ maxHeight: 300 + 'px'}" >
                        <table>
                            <tbody>
                                <tr v-for="item in masterRequisitionsMaterialInfoList" :key="item.id" >
                                    <td width="40px" class="text">
                                        <div class="center">
                                            {{ item.index }}
                                        </div>
                                    </td>
                                    <td class="name-input"> 
                                        <template v-if="typeof item.directory_material == 'undefined'">
                                            {{ item.name }}
                                        </template> 
                                        <template v-else >
                                            {{ item.directory_material.name }}
                                        </template>
                                    </td>
                                    <td width="10%">
                                        <template v-if="typeof item.directory_material == 'undefined'">
                                            {{ item.unit.title }}
                                        </template>
                                        <template v-else>
                                            {{ item.directory_material.unit.title }}
                                        </template>
                                    </td>
                                    <td width="10%" > {{ item.quantity }} </td>
                                    <td> {{ item.description }} </td>
                                </tr>  
                            </tbody> 
                        </table>    
                    </div>  
                </div>  
            </b-row>
        </div>
    </b-modal>  
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        name: 'MasterRequisitionsModalInfo',
        props: {
            requisitionProps: { type: Object, default: () => {} }
        },
        computed: {
            ...mapGetters({
                masterRequisitionsMaterialInfoList: 'masterRequisitionsMaterialInfoListGetter'
            })
        },
        methods: {
            ...mapActions({
                masterRequisitionsMaterialInfoSetList: 'masterRequisitionsMaterialInfoSetListActions'
            }),
            hideModal(){
                this.$refs['master-requisitions-modal-info'].hide();
                this.masterRequisitionsMaterialInfoSetList([])
            },
            mountedData(){
                this.masterRequisitionsMaterialInfoSetList(this.requisitionProps.materials)
                console.log('112121')
            }
        },
    }
</script>