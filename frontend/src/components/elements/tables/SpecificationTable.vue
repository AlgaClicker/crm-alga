<template>
    <div class="c-wrapper-table">
        <div v-if="loadingProps" class="c-spinner_wrapper c-empty-table">
            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
        </div>
        <template v-else>
            <div class="c-wrapper-table__title">
                Спецификации
                <b-icon v-if="isViewTable"
                    @click="toggleView()"
                    icon="chevron-down"
                    font-scale="0.85"
                >
                </b-icon>
                <b-icon v-else
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
                                Название объекта
                            </th>
                            <th class="th-date"> 
                                Дата создания
                            </th>
                            <th v-if="false" v-show="isEditProps" class="th-actions"></th>
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
                                    <base-icon  v-show="item.fixed" iconProps="loock" sizeProps="md"/>
                                    <router-link    
                                        v-if="roles == 'master'"
                                        :to="`/crm/master/specification/${item.id}`"
                                    >
                                        {{ item.name }}
                                    </router-link>
                                    <router-link    
                                        v-else-if="roles == 'snabzenie'"
                                        :to="`/crm/master/specification/${item.id}`"
                                    >
                                        {{ item.name }}
                                    </router-link>
                                    <router-link    
                                        v-else
                                        :to="`/crm/upravlenie/specification/${item.id}`"
                                    >
                                        {{ item.name }}
                                    </router-link>
                                </td>
                                <td>{{ item.objectName }}</td>
                                <td class="td-date">{{ item.created_at | dateFilter }}</td>
                                <td v-show="false" class="td-actions">
                                    <span @click="deleteSpec(item)">
                                        Удалить
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
          <div class="d-flex justify-content-end">
            <div class="p-2 bd-highlight">
              <div v-if="isEditProps & isViewTable" class="d-flex">
                <button @click="uploadModalShow" v-b-modal.file-upload-modal-form class="c-button-add">
                  <b-icon icon="upload" scale="1"></b-icon>
                  <span>
                        Загрузить
                  </span>
                </button>
              </div>
            </div>
            <div class="p-2 bd-highlight">
              <div v-if="isEditProps & isViewTable" class="d-flex">
                <button v-b-modal.specification-modal-add class="c-button-add">
                  <b-icon icon="plus-lg" scale="1"></b-icon>
                  <span>
                        Создать
                    </span>
                </button>
              </div>
            </div>

          </div>

        </template>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import BaseIcon from "@/components/elements/BaseIcon"

    export default {
        name: 'SpecificationTable',
        data(){
            return {
                viewChildren: false,
                iconLoock: '',
                importForm: false,
                isViewTable: true
            }
        },
        props: {
            specificationListProps: { type: Array, default: () => [] },
            titleProps: { type: String, default: '' },
            isEditProps: { type: Boolean, default: false},
            loadingProps: { type: Boolean, default: false}
        },
        components: {
            BaseIcon
        },
        emits: ['deleteSpecEmit'],
        computed: {
            ...mapGetters({
                roles: 'rolesGetter'
            }),
            pathSpec(id){   
                if(this.roles == 'master'){
                    return `/crm/master/specification/${id}`
                }else if(this.roles == 'supply'){
                    return `/crm/supply/specification/${id}`
                }else{
                    return `/crm/upravlenie/specification/${id}`
                }
            } 
        },
        methods: {
          uploadModalShow() {
            this.importForm = true
          },
          fileUploadModalFormClose() {
            console.log("fileUploadModalFormClose");
            this.importForm = false
          },
          toggleView(){
              this.isViewTable = !this.isViewTable
          },
          deleteSpec(spec){
              this.$emit('deleteSpecEmit', this.$event, spec)
          }
        }
    }
</script>