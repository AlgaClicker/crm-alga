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
                    {{filter}}
                    <div v-for="item in optionsProps" :key="item?.id">
                        {{item.key}}
                        <label class="c-label-filter" for="input-type" >
                            {{ item.label }}    
                            <b-form-select 
                                v-if="item.type == 'select'"
                                class="mt-2 custom-select-filter"
                                style="width: 100%; height: 35px"
                                v-model="filter[item.key]" 
                                :options="item.list"
                            >
                            </b-form-select>
                        </label>
                    </div>
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
    import { clearField } from '@/utils/objectHelpers'
    import BaseIcon from "@/components/elements/BaseIcon"

    export default {
        name: 'BaseFilter',
        data() {
            return {
                isFilter: false,
                filter: {}
            }
        },
        props: {
            optionsProps: { type: Array, default: () => []},
            setFilterActionsNameProps: { type: String, default: '' },
        },
        components: {
            BaseIcon
        },
        watch: {
            'optionsProps': {
                handler() {
                    for( let i = 0; i < this.optionsProps.length; i++ ){
                        console.log(this.optionsProps)

                        this.filter[this.optionsProps[i].key] = null
                    }
                }
            }
        },
        methods: {
            clearFilter(){
                console.log(clearField(this.filter))
                this.filter = clearField(this.filter)
            },
            closeDropdown(){
                this.$refs.dropdown.hide(true)
            },
            setFilter(){
                store.dispatch(this.setOptionsActionsNameProps, this.filter)
            },
            set(value){
                console.log(value)
            }
        },
        mounted() {
            console.log( this.optionsProps)
            for( let i = 0; i < this.optionsProps.length; i++ ){
                console.log(this.optionsProps)

                this.filter[this.optionsProps[i].key] = null
            }
        },
    }
</script>