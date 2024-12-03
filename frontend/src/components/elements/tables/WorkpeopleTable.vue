<template>
    <div class="c-wrapper-table">
        <div class="c-wrapper-table__title">
            {{ titleProps }}
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
        <div v-show="isViewTable" class='c-table-workpeople'>
            <table>
                <thead>
                    <tr>
                        <th>Фамилия</th>
                        <th>Имя</th>
                        <th>Телефон</th>
                        <th>Табель</th>
                        <th>Профессия</th>
                        <!--<th></th>-->
                    </tr>
                </thead>
            </table> 
            <div class='c-table-workpeople__body'>
                <table>
                    <tbody>
                        <tr v-for="item in workpeopleProps" :key="item.id">
                            <td>
                                <p>
                                    {{ item.surname }}
                                </p>
                            </td>
                            <td>
                                <p>
                                    {{ item.name }}
                                </p>
                            </td>
                            <td>
                                <p>
                                    {{ item.phone_number }}
                                </p>
                            </td>
                            <td>
                                <span 
                                    @click="selectEmployees(item)"
                                    class="c-link-label"
                                    v-b-modal.tabel-modal
                                >
                                    <b-icon icon="table" scale="1"></b-icon>
                                    Табель
                                </span>
                            </td>
                            <td>
                                <p>
                                    {{ item.profession.name }}
                                </p>
                            </td>
                             <!-- <td>
                                Удалить
                            </td> -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-show="isViewTable" class="c-table-workpeople-mobile">
            <div class="c-table-workpeople-mobile-col" v-for="item in workpeopleProps" :key="item.id">
                <div class="c-table-workpeople-mobile-col__row">
                    <div class="title">Фамилия</div>
                    <div class="text"> {{ item.surname }} </div>
                </div>
                <div class="c-table-workpeople-mobile-col__row">
                    <div class="title">Имя</div>
                    <div class="text"> {{ item.name }} </div>
                </div>
                <div class="c-table-workpeople-mobile-col__row">
                    <div class="title">Телефон</div>
                    <div class="text"> {{ item.phone_number }} </div>
                </div>
                <div class="c-table-workpeople-mobile-col__row">
                    <div class="title">Табель</div>
                    <span 
                        @click="selectEmployees(item)"
                        class="text c-link-label"
                        v-b-modal.tabel-modal
                    >
                        <b-icon icon="table" scale="1"></b-icon>
                        Табель
                    </span>
                </div>
                <div class="c-table-workpeople-mobile-col__row">
                    <div class="title">Профессия</div>
                    <div class="text">{{ item.profession.name }}</div>
                </div>
            </div>
        </div>
        <tabel 
            :employeesProps="selectedEmployees"
        />
    </div>
</template>

<script>
    import Tabel from "@/components/tabel/Tabel"

    export default {
        name: 'WorkpeopleTable',
        data(){
            return {
                selectedEmployees: {},
                isViewTable: true
            }
        },
        props: {
            workpeopleProps: { type: Array, default: () => [] },
            titleProps: { type: String, default: '' },
        },
        components: {
            Tabel,
        },
        methods: {
            selectEmployees(employees){
                this.selectedEmployees = employees
            },
            toggleView(){
                this.isViewTable = !this.isViewTable
            }
        }
    }
</script>