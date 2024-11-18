<<<<<<< HEAD
<template>
    <div class="c-filter"> 
        <b-dropdown 
            :active="true" 
            ref="dropdown"
            active-class='dropdown--active' 
            class="dropdown-filter m-0" 
            variant="link" 
            toggle-class="text-decoration-none" 
            no-caret 
            left
        >
            <template #button-content>
                <button class='c-button-filter'>
                    <base-icon iconProps="setting" sizeProps="md" />
                    Фильтр
                    <span v-show="isFilter" class='c-filter-dot'></span>
                </button>
            </template>
            <div class='c-filter-menu'>
                <header>
                    Фильтр
                    <div                                     
                        @click="closeDropdown()"
                        class="icon" 
                    >
                        <base-icon 
                            iconProps="x" 
                            sizeProps="sm" 
                        />
                    </div>
                </header>   
                <div class='c-filter-menu__wrapper'>
                    <label v-if="isStatusProps" class="c-label-filter" for="input-type" >
                        Статус
                        <b-form-select 
                            class="mt-2 custom-select-filter"
                            style="width: 100%"
                            v-model="filter.status" 
                            :options="status"
                        >
                        </b-form-select>
                    </label>
                    <label class="c-label-filter" for="input-type" >
                        Спецификация
                        <b-form-select 
                            class="mt-2 custom-select-filter"
                            style="width: 100%"
                            v-model="filter.specification" 
                            :options="specificationOptionsProps"
                        >
                        </b-form-select>
                    </label>
                    <label v-show="supllyListProps.length > 0" class="c-label-filter" for="input-type" >
                        Менаджер
                        <b-form-select 
                            class="mt-2 custom-select-filter"
                            style="width: 100%"
                            v-model="filter.manage" 
                            :options="supllyListProps"
                        >
                        </b-form-select>
                    </label>
                    <label v-show="mastersListProps.length > 0" class="c-label-filter" for="input-type" >
                        Мастер
                        <b-form-select 
                            class="mt-2 custom-select-filter"
                            style="width: 100%"
                            v-model="filter.master" 
                            :options="mastersListProps"
                        >
                        </b-form-select>
                    </label>
                </div>
                <footer>
                    <button @click='clearFilter' class='c-button-clear-sm'>
                        Очистить
                    </button>
                    <button @click='setFilter' class='c-button-apply-sm'>
                        Применить
                    </button>
                </footer>
            </div>
        </b-dropdown>
    </div>
</template>

<script>
    import store from '@/store'
    import { statusRequisition } from '@/utils/types'
    import { clearField } from '@/utils/objectHelpers'
    import BaseIcon from "@/components/elements/BaseIcon"
    
    export default {
        name: 'FilterRequisition',
        data() {
            return {
                filter: {
                    status: null,
                    manage: null,
                    master: null,
                    specification: null
                },
                isFilter: false
            }
        },
        props: {
            supllyListProps: { type: Array, default: () => [] },
            mastersListProps: { type: Array, default: () => [] },
            setListActionsNameProps: { type: String, default: '' },
            setFilterActionsNameProps: { type: String, default: '' },
            specificationOptionsProps: { type: Array, default: () => []},
            isStatusProps: { type: Boolean, default: true }
        },
        computed: {
            status(){   
                return statusRequisition
            }
        },
        components: {
            BaseIcon
        },
        methods: {
            closeDropdown(){
                this.$refs.dropdown.hide(true)
            },
            clearFilter(){
                console.log(clearField(this.filter))
                this.filter = clearField(this.filter)
                store.dispatch(this.setFilterActionsNameProps, this.filter)
                store.dispatch(this.setListActionsNameProps)
            },
            setFilter(){
                console.log(this.filter)
                store.dispatch(this.setFilterActionsNameProps, this.filter)
                store.dispatch(this.setListActionsNameProps)
            }
        },
    }
=======
<template>
    <div class="c-filter"> 
        <b-dropdown 
            :active="true" 
            ref="dropdown"
            active-class='dropdown--active' 
            class="dropdown-filter m-0" 
            variant="link" 
            toggle-class="text-decoration-none" 
            no-caret 
            left
        >
            <template #button-content>
                <button class='c-button-filter'>
                    <base-icon iconProps="setting" sizeProps="md" />
                    Фильтр
                    <span v-show="isFilter" class='c-filter-dot'></span>
                </button>
            </template>
            <div class='c-filter-menu'>
                <header>
                    Фильтр
                    <div                                     
                        @click="closeDropdown()"
                        class="icon" 
                    >
                        <base-icon 
                            iconProps="x" 
                            sizeProps="sm" 
                        />
                    </div>
                </header>   
                <div class='c-filter-menu__wrapper'>
                    <label v-if="isStatusProps" class="c-label-filter" for="input-type" >
                        Статус
                        <b-form-select 
                            class="mt-2 custom-select-filter"
                            style="width: 100%"
                            v-model="filter.status" 
                            :options="status"
                        >
                        </b-form-select>
                    </label>
                    <label class="c-label-filter" for="input-type" >
                        Спецификация
                        <b-form-select 
                            class="mt-2 custom-select-filter"
                            style="width: 100%"
                            v-model="filter.specification" 
                            :options="specificationOptionsProps"
                        >
                        </b-form-select>
                    </label>
                    <label v-show="supllyListProps.length > 0" class="c-label-filter" for="input-type" >
                        Менеджер
                        <b-form-select 
                            class="mt-2 custom-select-filter"
                            style="width: 100%"
                            v-model="filter.manage" 
                            :options="supllyListProps"
                        >
                        </b-form-select>
                    </label>
                    <label v-show="mastersListProps.length > 0" class="c-label-filter" for="input-type" >
                        Мастер
                        <b-form-select 
                            class="mt-2 custom-select-filter"
                            style="width: 100%"
                            v-model="filter.master" 
                            :options="mastersListProps"
                        >
                        </b-form-select>
                    </label>
                </div>
                <footer>
                    <button @click='clearFilter' class='c-button-clear-sm'>
                        Очистить
                    </button>
                    <button @click='setFilter' class='c-button-apply-sm'>
                        Применить
                    </button>
                </footer>
            </div>
        </b-dropdown>
    </div>
</template>

<script>
    import store from '@/store'
    import { statusRequisition } from '@/utils/types'
    import { clearField } from '@/utils/objectHelpers'
    import BaseIcon from "@/components/elements/BaseIcon"
    
    export default {
        name: 'FilterRequisition',
        data() {
            return {
                filter: {
                    status: null,
                    manage: null,
                    master: null,
                    specification: null,
                },
                isFilter: false
            }
        },  
        props: {
            supllyListProps: { type: Array, default: () => [] },
            mastersListProps: { type: Array, default: () => [] },
            setListActionsNameProps: { type: String, default: '' },
            setFilterActionsNameProps: { type: String, default: '' },
            specificationOptionsProps: { type: Array, default: () => []},
            isStatusProps: { type: Boolean, default: true }
        },
        computed: {
            status(){   
                return statusRequisition
            }
        },
        components: {
            BaseIcon
        },
        methods: {
            closeDropdown(){
                this.$refs.dropdown.hide(true)
            },
            clearFilter(){
                this.filter = clearField(this.filter)
                store.dispatch(this.setFilterActionsNameProps, this.filter)
                store.dispatch(this.setListActionsNameProps)
                this.isFilter = false
            },
            setFilter(){
                store.dispatch(this.setFilterActionsNameProps, this.filter)
                store.dispatch(this.setListActionsNameProps)
                this.isFilter = true
            }
        },
    }
>>>>>>> feature/f_requisitions_master
</script>