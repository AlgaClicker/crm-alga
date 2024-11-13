<template>
    <b-modal
        id="directory-bank-modal-info"
        ref="directory-bank-modal-info"
        centered 
        content-class="c-modal-base с-modal-bank-info"
        hide-footer
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>{{ bankProps?.fullname }}</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="с-modal-bank-info_body">
            <div class='c-label-icon'>
                <span v-html="iconInfo"></span>
                <label>
                    Информация о контрагенте
                </label>
            </div>
            <table class="c-table_info">
                <tbody>
                    <tbody>
                        <tr>
                            <td class='c-tabel-info_title' > Полное название </td>
                            <td class='c-tabel-info_content' > {{ bankProps?.fullname }} </td>
                        </tr>
                        <tr>
                            <td class='c-tabel-info_title' > БИК </td>
                            <td class='c-tabel-info_content' > {{ bankProps?.bik }} </td>
                        </tr>
                        <tr>
                            <td class='c-tabel-info_title' > Дата обновления </td>
                            <td class='c-tabel-info_content' > {{ bankProps?.updated_at }} </td>
                        </tr>
                    </tbody>
                </tbody>
            </table> 
            <div class='c-label-icon mt-2'>
                <span v-html="iconLocations"></span>
                <label>
                    Адресс
                </label>
            </div>
            <p>
                {{ bankProps.address?.split(',')[0] }},
                {{ bankProps.address?.split(',')[1] }},
                {{ bankProps.address?.split(',')[2] }}
            </p>
            <label class="label-custom mt-4" for="input-groupe-description">Банковские счета</label>
            <div class="с-modal-bank-info_bank-account">
                <b-table 
                    class='b-table mb-3' 
                    :items="bankProps.bank_accounts" 
                    :fields="fields" 
                    responsive="sm">
                    <template #cell(date_in)="row">
                       {{ row.item.date_in | dateFilter }}
                    </template>
                </b-table>
            </div>
        </div>
    </b-modal>
</template>
<script>
    import { svgs } from '@/utils/svgList'

    export default {
        name: 'DirectoryBanksAccountInfo',
        data(){
            return {
                fields: [
                    { key: 'account', label: 'Счет' },
                    { key: 'regulation_account_type', label: 'Тип банковского счета' },
                    { key: 'account_cbrbic', label: 'cbrbic' },
                    { key: 'date_in', label: 'date_in' },
                    { key: 'account_status', label: 'Статус аккаунта' },
                ],
                iconInfo: '',
                iconLocations: ''
            }   
        },
        props: {
           bankProps : { type: Object, default: () => {} }
        },
        methods: {
            hideModal(){
                this.$refs['directory-bank-modal-info'].hide();
            },
            mountedData(){
                this.iconInfo = svgs.info.md
                this.iconLocations = svgs.locations.md
            }
        }
    }
</script>