<template>
    <div 
        :class="{ 
            'c-tabel-cell--is-current-month': !cellProps.currentMonth, 
            'c-tabel-cell--full': typeof cellProps.time_amount != 'undefined' & cellProps.time_amount != ''
        }" 
        class="c-tabel-cell"
        @click="showEditMarkModal()"
    >
        <div class="c-tabel-cell__label">
            <span 
                class="c-tabel-cell__date" 
                :class="{ 'c-tabel-cell__date--is-current-date': isCurrentDate }"
            > 
                {{ cellProps.label }} 
            </span> 
            <tabel-mark 
                :markProps="cellProps.time_amount"
                :cellDate="cellProps.date"
                :workpeopleIdProps="workpeopleIdProps"
                :specificationObjectNameProps="typeof cellProps.specification_id != 'undefined' ? cellProps.specification_id.objectName : ''"
            />
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'
    import TabelMark from '@/components/tabel/TabelMark'

    export default {
        name: 'TabelCell',
        data(){
            return {
                mark: '',
                options: [
                   {
                        text: '1 час',
                        value: '1ч',
                   },
                   {
                        text: '2 час',
                        value: '2ч',
                   },
                   {
                        text: '3 час',
                        value: '3ч',
                   },
                   {
                        text: '4 час',
                        value: '4ч',
                   },
                   {
                        text: '5 час',
                        value: '5ч',
                   },
                   {
                        text: '6 час',
                        value: '6ч',
                   },
                   {
                        text: '7 час',
                        value: '7ч',
                   },
                   {
                        text: '8 час',
                        value: '8ч',
                   },
                   {
                        text: 'отпуск',
                        value: 'о',
                   },
                   {
                        text: 'больничный',
                        value: 'б',
                   },
                   {
                        text: 'прогул по неувож',
                        value: 'нп',
                   },
                   {
                        text: 'дополнительный отгул',
                        value: 'до',
                   }
                ]
            }
        },
        props: {
            cellProps: { type: Object, default: () => {} },
            workpeopleIdProps: { type: String, default: "" }
        },
        components: {
            TabelMark
        },
        computed: {
            isCurrentDate(){
                var date = new Date()

                if(this.cellProps.label == date.getDate()){
                    return true
                }else{
                    return false
                }

            },
            nameObjectComputed(){
                if(typeof this.cellProps.specification_id != 'undefined'){
                    return  this.cellProps.specification_id.objectName
                }else{
                    return ''
                }
            }
        },
        methods: {
            ...mapActions({
                tabelSetEditCell: 'tabelSetEditCellActions'
            }),
            showEditMarkModal(){
                this.tabelSetEditCell(this.cellProps)
                this.$bvModal.show('tabel-edit-mark-modal')
            },
        }
    }
</script>