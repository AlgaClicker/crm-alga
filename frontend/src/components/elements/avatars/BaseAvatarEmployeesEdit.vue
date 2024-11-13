<template>
    <div class="c-avatars-list-edit">
        <div>
            <multiselect 
                v-model="value" 
                :options="employeesListComputed"
                label="name"
                placeholder="Выберите пользователя"
                track-by="name"
                :show-labels="false"
                @input=addEmployees()
            >
                <template slot="option" slot-scope="props">
                    <div class="option__desc">
                        <div class="c-avatar-list">
                            <span class="option__title c-avatar">{{ props.option.name.split('')[0].toUpperCase() }}</span>
                            <span class="ml-1"> {{ props.option.name }} </span>
                        </div>
                    </div>
                </template>
            </multiselect>
            <div class="c-flex-container">
                <div class="c-avatars-edit mt-3" v-for="(item, key) in list" :key="key">
                    <div class="c-avatars-edit_delete">
                        <svg @click="deleteEmployees(item)" width="4" height="4" ><use xlink:href="@/assets/icons/sprite.svg#x-outline-sm"></use></svg>   
                    </div>
                    <span  v-tooltip.bottom-start="`${item.name}`" class="c-avatars-edit_title">{{ item.name.split('')[0].toUpperCase() }}</span>
                </div>
            </div>
        </div>
    </div>  
</template>
<script>

    export default {
        name: 'BaseAvatarEmployeesEdit',
        data(){
            return {
                employeesList: [],
                list: [],
                value: {},
            }
        },
        props: {
            employeesListProps: { type: Array, default: () => [] },
            workpeoplesListProps: { type: Array, default: () => [] }
        },
        emits: ['addEmployeesEmit', 'deleteEmployeeEmit'],
        methods: {
            addEmployees(){
                if(this.list.filter(item => item.id == this.value.id).length == 0){
                    this.list.push(this.value)
                    this.$emit('addEmployeesEmit', this.list)
                    this.employeesList = this.employeesList.filter(item => item.id != this.value.id)
                    this.value = null
                }
                this.value = null
            },
            deleteEmployees(item){
                this.list = this.list.filter(element => element.id != item.id)
                this.employeesList.push(item)
                this.$emit('deleteEmployeesEmit', this.list)
            },
        },
        computed: {
            employeesListComputed(){
                return this.employeesList
            },
        },
        mounted(){
            this.list = this.workpeoplesListProps
            this.employeesList = this.employeesListProps
            
        }
    }
</script>

