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
        <div v-show="isViewTable" class='c-tabel-income'>
            <table>
                <thead>
                    <tr>
                        <th>Сумма</th>
                        <th>Тип</th>
                        <th>Дата</th>
                    </tr>
                </thead>
            </table>
            <div class='c-tabel-income__body'>
                <div 
                    v-if="incomeProps == null"
                    class="c-empty-table"
                >
                    <img src="@/assets/images/box.png">
                    <p>
                        Нет поступлений
                    </p>
                </div>
                <table v-else>
                    <tbody>
                        <tr v-for="item in incomeProps" :key="item.id">
                            <td>
                                <p>
                                    {{ item.amount }}
                                </p>
                            </td>
                            <td>
                                <p>
                                    {{ item.type | paymentsTypeFilter }}
                                </p>
                            </td>
                            <td>
                                <p>
                                    {{ item.createdAt | dateFilter }}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'IncomeTable',
        data(){
            return {
                isViewTable: true
            }
        },
        props: {
            incomeProps: { type: Array, default: () => [] },
            titleProps: { type: String, default: '' },
        },
        methods: {
            toggleView(){
                this.isViewTable = !this.isViewTable
            }
        }
    }
</script>