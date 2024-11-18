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
                    <base-icon 
                        iconProps="setting" 
                        sizeProps="md" 
                    />
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
                    <label class="c-label-filter" for="input-type" >
                        Статус
                        <b-form-select 
                            class="mt-2 custom-select-filter"
                            style="width: 100%"
                            v-model="filter.type" 
                            :options="type"
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
    import { typePayment } from '@/utils/types'
    import { clearField } from '@/utils/objectHelpers'
    import BaseIcon from "@/components/elements/BaseIcon"

    export default {
        name: 'FilterDate',
        data() {
            return {
                filter: {
                    type: null,
                },
                isFilter: false
            }
        },
        computed: {
            type(){
                return typePayment
            }
        },
        components: {
            BaseIcon
        },
        props: {
            setListActionsNameProps: { type: String, default: '' },
            setFilterActionsNameProps: { type: String, default: '' },
        },
        methods: {
            closeDropdown(){
                this.$refs.dropdown.hide(true)
            },
            clearFilter(){
                this.filter = clearField(this.filter)

                this.isFilter = false
            },
            setFilter(){
                store.dispatch(this.setFilterActionsNameProps, this.filter)
                store.dispatch(this.setListActionsNameProps)

                this.isFilter = true
            }
        }
    }
</script>