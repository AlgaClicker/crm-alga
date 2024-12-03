<template>
    <b-modal 
        id="tabel-modal"
        ref="tabel-modal"
        title="Новый сотрудник"
        content-class="c-tabel-modal"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <div class="header-title">
                <h5>Табель от</h5>
            </div>
            <date-picker
                class="ml-1"
                format="YYYY MMMM"
                input-class='mx-input-modal'
                type="month"
                @input="setDate()"
                v-model="date"
            >
            </date-picker>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <template v-if="!tabelLoading">
                <div class="c-body__statistics">
                    <div class="statistics-label">
                        <label>Отработано часов</label>
                        <div class="statistics-label__total"> {{ totalTimeWork }} ч</div>
                    </div>
                    <div class="statistics-label ml-2">
                        <label>Отработано дней</label>
                        <div class="statistics-label__total"> {{ totalDayWork }} д</div>
                    </div>
                </div>
                <div class="c-body__day-of-weak">
                    <div class="c-body__th">
                        ПН
                    </div>
                    <div class="c-body__th">
                        ВТ
                    </div>
                    <div class="c-body__th">
                        СР
                    </div>
                    <div class="c-body__th">
                        ЧТ
                    </div>
                    <div class="c-body__th">
                        ПТ
                    </div>
                    <div class="c-body__th">
                        СБ
                    </div>
                    <div class="c-body__th">
                        ВС
                    </div>
                </div>
                <div class="c-body__cell-wrapper">
                    <div v-for="(item, key) in tabelDaysList" :key="key">
                        <tabel-cell 
                            v-if="item.currentMonth"
                            :cellProps="item"
                            :workpeopleIdProps="employeesProps.id"
                        />
                        <tabel-disabpled-cell
                            v-else
                            :cellProps="item"
                        />
                    </div>
                </div>
            </template>
            <template v-else>
                <div style="height: 62vh;" class="c-spinner_wrapper">
                    <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                </div>
            </template>
        </div>
        <template #modal-footer>
            <b-icon
                icon="person-fill"
                scale="1.2"
            >
            </b-icon>
            <span class="fio"> {{ employeesProps?.name }} {{ employeesProps?.surname }} </span>
        </template>
        <tabel-edit-mark-modal
            :workpeopleProps="employeesProps"
        />
    </b-modal>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import TabelCell from '@/components/tabel/TabelCell'
    import TabelDisabpledCell from '@/components/tabel/TabelDisabpledCell'
    import { ColseDropDown } from '@/utils/dropDown'
    import TabelEditMarkModal from '@/components/tabel/TabelEditMarkModal'

    export default {
        name: 'Tabel',
        data() {
            return {
                currentData: null,
                year: null,
                month: null,
                days: [],
                date: ''
            }
        },
        props: {
            employeesProps: { type: Object, default: () => {} }
        },
        components: {
            TabelCell,
            TabelDisabpledCell,
            TabelEditMarkModal
        },
        computed: {
            ...mapGetters({
                tabelDaysList: "tabelDaysListGetter",
                tabelMarkList: "tabelMarkListGetter",
                tabelLoading: 'tabelLoadingGetter',
                totalTimeWork: 'totalTimeWorkGetter',
                totalDayWork: 'totalDayWorkGetter'
            }),
        },
        methods: {
            ...mapActions({ 
                tabelSetList: "tabelSetListActions",
                tabelSetMarkList: 'tabelSetMarkListActions'
            }),
            hideModal(){
                this.$refs['tabel-modal'].hide();
            },
            async setDate(){

                this.year = this.date.getFullYear()
                this.month = this.date.getMonth()
                this.days = this.getMonthDays(this.year, this.month) 

                await this.tabelSetMarkList({
                    month: this.month < 10 ? '0' + (this.month + 1) : this.month + 1,
                    idWorkpeople: this.employeesProps.id,
                    year: this.year
                })

                for( let i = 0; i < this.days.length; i++ ){
                    if(this.tabelMarkList.filter( item => this.days[i].date == item.date).length != 0 ){
                        this.days[i].time_amount = this.tabelMarkList.filter( item => this.days[i].date == item.date )[0].time_amount
                    }
                } 

                for(let i = 0; i < this.tabelMarkList.length; i++){
                    if(typeof this.tabelMarkList[i].specification_id != 'undefined'){
                        this.days[this.days.findIndex( item => item.date == this.tabelMarkList[i].date & item.currentMonth == true)].specification_id = this.tabelMarkList[i].specification_id
                    }
                }

                this.tabelSetList(this.days)
            },
            getMonthDays(year, month){
                const dayOfWeek = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']

                let dateNextMonth = null
                let dateLastMonth = null
                let count = null

                let daysLastMonth = []
                let daysNextMonth = []
                let days = []

                // last month 
                if(month - 1 < 0){
                    dateLastMonth = new Date(year - 1, 12, 0);
                    count = dateLastMonth.getDate();
                    for (let day = 1; day <= count; day++) {
                        dateLastMonth.setDate(day)
                        
                        daysLastMonth.push({
                            week: dateLastMonth.toLocaleString('en-US', { weekday: 'short' }),
                            label: dateLastMonth.getDate(),
                            currentMonth: false,
                            date: new Date(year - 1, 12, dateLastMonth.getDate()).toLocaleString([], { year: 'numeric', month: 'numeric', day: 'numeric' })
                            .split('.')
                            .reverse()
                            .join('-')
                        });
                    }
                }else{
                    dateLastMonth = new Date(year, month - 1 + 1, 0);
                    count = dateLastMonth.getDate();
                    for (let day = 1; day <= count; day++) {
                        dateLastMonth.setDate(day);
                        
                        daysLastMonth.push({
                            week: dateLastMonth.toLocaleString('en-US', { weekday: 'short' }),
                            label: dateLastMonth.getDate(),
                            currentMonth: false,
                            date: new Date(year, month, dateLastMonth.getDate()).toLocaleString([], { year: 'numeric', month: 'numeric', day: 'numeric' })
                            .split('.')
                            .reverse()
                            .join('-')
                        });
                    }
                }

                // next month
                if(month + 1 == 12){
                    dateNextMonth = new Date(year + 1, 0, 0);
                    count = daysNextMonth.getDate();
                    for (let day = 1; day <= count; day++) {
                        dateNextMonth.setDate(day)
                        
                        daysLastMonth.push({
                            week: dateNextMonth.toLocaleString('en-US', { weekday: 'short' }),
                            label: dateNextMonth.getDate(),
                            currentMonth: false,
                            date: new Date(year + 1, 0, dateNextMonth.getDate()).toLocaleString([], { year: 'numeric', month: 'numeric', day: 'numeric' })
                            .split('.')
                            .reverse()
                            .join('-')
                        });
                    }
                }else{
                    dateNextMonth = new Date(year, month + 2, 0);
                    count = dateNextMonth.getDate();
                    for (let day = 1; day <= count; day++) {
                        dateNextMonth.setDate(day);
                        
                        daysNextMonth.push({
                            week: dateNextMonth.toLocaleString('en-US', { weekday: 'short' }),
                            label: dateNextMonth.getDate(),
                            currentMonth: false,
                            date: new Date(year, month + 2, dateNextMonth.getDate()).toLocaleString([], { year: 'numeric', month: 'numeric', day: 'numeric' })
                            .split('.')
                            .reverse()
                            .join('-')
                        });
                    }
                }

                // current month
                const date = new Date(year, month + 1, 0);
                count = date.getDate(); 
                
                for (let day = 1; day <= count; day++) {
                    date.setDate(day);
  
                    days.push({
                        week: date.toLocaleString('en-US', { weekday: 'short' }),
                        label: date.getDate(),
                        currentMonth: true,
                        date: new Date(year, month, date.getDate()).toLocaleString([], { year: 'numeric', month: 'numeric', day: 'numeric' })
                            .split('.')
                            .reverse()
                            .join('-')
                    });
                }

                let start = days[0].week
                let end = days[days.length - 1].week

                let dayOfWeekStart = dayOfWeek.findIndex( item => item == start )
                let dayOfWeekEnd = dayOfWeek.findIndex( item => item == end )

                days = daysLastMonth.slice(daysLastMonth.length - dayOfWeekStart, daysLastMonth.length).concat(days)
                days = days.concat(daysNextMonth.slice(0, 6 - dayOfWeekEnd))
                
                return days;
            },
            async mountedData(){
                
                ColseDropDown()
                this.currentData = new Date()
                this.date = this.currentData

                this.year = this.currentData.getFullYear()
                this.month = this.currentData.getMonth()
                
                this.days = this.getMonthDays(this.year, this.month)

                var date = new Date();

                await this.tabelSetMarkList({
                    month: date.getMonth() < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1,
                    idWorkpeople: this.employeesProps.id,
                    year: this.year
                })              

                for( let i = 0; i < this.days.length; i++ ){
                    this.days[i].time_amount = ''
                    if(this.tabelMarkList.filter( item => this.days[i].date == item.date).length != 0 ){
                        this.days[i].time_amount = this.tabelMarkList.filter( item => this.days[i].date == item.date )[0].time_amount
                    }
                } 

                for(let i = 0; i < this.tabelMarkList.length; i++){
                    
                    if(typeof this.tabelMarkList[i].specification_id != 'undefined'){
                        this.days[this.days.findIndex( item => item.date == this.tabelMarkList[i].date & item.currentMonth == true)].specification_id = this.tabelMarkList[i].specification_id

                        if(this.days[i].date == '2024-02-29'){
                            this.days[this.days.findIndex( item => item.date == this.tabelMarkList[i].date)].specification_id = this.tabelMarkList[i].specification_id
                        }
                    }
                }

                console.log('days')
    
                console.log(this.days)
                this.tabelSetList(this.days)
            }
        }
    }
</script>