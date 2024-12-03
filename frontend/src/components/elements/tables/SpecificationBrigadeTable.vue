<template> 
    <div class="c-wrapper-table">
        <div class="c-wrapper-table__title">
            Спецификации бригады
            <b-icon 
                v-if="isViewTable"
                @click="toggleView()"
                icon="chevron-down"
                font-scale="0.85"
            >
            </b-icon>
            <b-icon 
                v-else
                @click="toggleView()"
                icon="chevron-up"
                font-scale="0.85" 
            >
            </b-icon>
        </div>
        <div class="c-specification-table-list" v-show="isViewTable">
            <table>
                <thead>
                    <tr>
                        <th> 
                            Название спецификации
                        </th>
                        <th> 
                            Начало
                        </th>
                        <th> 
                            Конец
                        </th>
                        <th></th>
                    </tr>
                </thead>
            </table>
            <div class="c-specification-table-list__body">
                <div 
                    v-if="specificationListProps?.length == 0 | specificationListProps == null"
                    class="c-empty-table"
                >
                    <img src="@/assets/images/box.png">
                    <p>
                        Нет прикрепленных спецификаций
                    </p>
                </div>
                <table v-else>
                    <tbody>
                        <tr v-for="item in specificationListProps" :key="item?.id">
                            <td class="td-name-spec">
                                <base-icon  v-show="item.fixed" iconProps="loock" sizeProps="md"/>
                                <span>
                                    <router-link    
                                        :to="`/crm/master/specification/${item.id}`"
                                    >
                                        {{ item.specification.name }}
                                    </router-link>
                                    ( {{ item.specification.objectName }} )
                                </span>
                            </td>
                            <td class="td-date">{{ item.created_at | dateFilterWithoutTime }}</td>
                            <td class="td-date">{{ item.end_at | dateFilterWithoutTime }}</td>
                            <td class="td-actions">
                                <span v-b-modal.base-delete-modal @click="deleteSpecification(item.id)">
                                    Удалить
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <button v-b-modal.master-add-specifications-for-brigade class="c-button-add">
            <b-icon icon="plus-lg" scale="1"></b-icon>
            <span>
                Назначить спецификацию
            </span>
        </button>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'
    import BaseIcon from "@/components/elements/BaseIcon"

    export default {
        name: 'SpecificationBrigadeTable',
        data(){
            return {
                isViewTable: true
            }
        },
        props: {
            specificationListProps: { type: Array, default: () => [] },
        },
        components: {
            BaseIcon
        },
        methods: {
            ...mapActions({
                setParamsDeleteModal: 'setParamsDeleteModalActions',
            }),
            toggleView(){
                this.isViewTable = !this.isViewTable
            },
            deleteSpecification(id){
                this.setParamsDeleteModal(
                    {
                        title: 'Cпецификацию',
                        actionsName: 'masterBrigadeDeletepecificationsActions',
                        data: [ id ]
                    }
                )
            }
        }
    }
</script>