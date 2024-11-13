<template>
    <div class="c-specification-table-list" >
        <table>
            <thead>
                <tr>
                    <th> 
                        Название спецификации
                    </th>
                    <th> 
                        Название объекта
                    </th>
                    <th> 
                        Дата создания
                    </th>
                    <th v-show="isEditProps" class="th-actions"></th>
                </tr>
            </thead>
        </table>
        <div class="c-specification-table-list__body">
            <div 
                v-if="specificationListProps.length == 0 | specificationListProps == null"
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
                            <span class="icon" v-html="iconLoock"></span>
                            <router-link    
                                :to="`/crm/specification/${item.id}`" 
                            >
                                {{ item.name }}
                            </router-link>
                        </td>
                        <td>{{ item.objectName }}</td>
                        <td>{{ item.created_at | dateFilter }}</td>
                        <td v-show="isEditProps" class="td-actions">
                            <span @click="deleteSpec(item)">
                                Удалить
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import { svgs } from '@/utils/svgList'

    export default {
        name: 'SpecificationModalTabel',
        data(){
            return {
                iconLoock: ''
            }
        },
        props: {
            specificationListProps: { type: Array, default: () => [] },
            titleProps: { type: String, default: '' },
            isEditProps: { type: Boolean, default: false},
        },
        emits: ['deleteSpecEmit'],
        methods: {
            deleteSpec(spec){
                this.$emit('deleteSpecEmit', this.$event, spec)
            }
        },
        mounted(){
            this.iconLoock = svgs.loock.md
        }
    }
</script>