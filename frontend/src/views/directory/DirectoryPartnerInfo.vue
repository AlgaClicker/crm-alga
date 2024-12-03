<template>
    <div class="l-object-view">
        <header class="l-object-view__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
            <div class="flex-left">
                <button v-b-modal.directory-partners-modal-edit class='c-button-add ml-1'>
                    <b-icon icon="pencil-fill" scale="1"></b-icon>
                    Изменить
                </button>
            </div>
        </header>
        <b-row class="mt-4">
            <b-col cols sm="12" md="5" xl="5" class="mb-3">
                <div class="c-requisition-info-card">
                    <header>
                        <div class="title"> Информация </div>
                    </header>
                    <b-row>
                        <b-col>
                            <label class="mt-3 c-requisition-info-card__label">Название</label>
                            <div class="c-requisition-info-card__text">{{ partner.name }}</div>
                        </b-col>
                        <b-col>
                            <label class="mt-3 c-requisition-info-card__label">Полное</label>
                            <div class="c-requisition-info-card__text">{{ partner.fullname }}</div>
                        </b-col>
                        <b-col cols="12">
                            <label class="mt-3 c-requisition-info-card__label">Адресс</label>
                            <div class="c-requisition-info-card__text">{{ partner.address }}</div>
                        </b-col>    
                    </b-row>
                    <b-row>
                        <b-col>
                            <label class="mt-3 c-requisition-info-card__label">ИНН</label>
                            <div class="c-requisition-info-card__text">{{ partner.inn }}</div>
                        </b-col>
                        <b-col>
                            <label class="mt-3 c-requisition-info-card__label">КПП</label>
                            <div class="c-requisition-info-card__text">{{ partner.kpp }}</div>
                        </b-col>
                        <b-col cols="12">
                            <label class="mt-3 c-requisition-info-card__label">ОГРН</label>
                            <div class="c-requisition-info-card__text">{{ partner.ogrn }}</div>
                        </b-col>  
                    </b-row>
                </div>
            </b-col>
            <b-col cols sm="12" md="7" xl="7" class="mb-3">
                <div class="bank-account-panel">
                    <header class="bank-account-panel__header">
                        <div class="bank-account-panel__title">Банковские счета</div>
                        <button v-b-modal.directory-partners-modal-add-bank-account class="c-button-add">
                            <b-icon icon="plus-lg" scale="1"></b-icon>
                            <span>
                                Создать 
                            </span>
                        </button>
                    </header>
                    <div class="bank-account-panel__wrapper">
                        <directory-partners-bank-account-card 
                            v-for="(item, key) in partner.bank_accounts" :key="key"
                            :bankAccountProps="item"
                        />
                    </div>
                </div>
            </b-col>
        </b-row>
        <directory-partners-modal-edit 
            :partnerProps="partner"
        />
        <directory-partners-modal-add-bank-account
            :partnerProps="partner"
        />
    </div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import DirectoryPartnersModalAddBankAccount from '@/components/directory/DirectoryPartnersModalAddBankAccount'
    import DirectoryPartnersModalEdit from '@/components/directory/DirectoryPartnersModalEdit'
    import DirectoryPartnersBankAccountCard from "@/components/directory/DirectoryPartnersBankAccountCard"

    export default {
        name: 'DirectoryPartnerInfo',
        data(){
            return{
                partner: {},
                breadcrumb: []
            }
        },
        methods: {
            ...mapActions({ 
                directoryPartnerSet: 'directoryPartnerSetActions',
            }),
        },
        components: {
            DirectoryPartnersModalEdit,
            DirectoryPartnersModalAddBankAccount,
            DirectoryPartnersBankAccountCard
        },
        computed: { 
            ...mapGetters({
                directoryPartner: 'directoryPartnerGetter',
            }),
        },
        watch: {
            '$route.params.id': {
                async handler() {
                    let id = window.location.href.split('/').reverse()[0];

                    await this.directoryPartnerSet(id)
                    
                    this.partner = this.directoryPartner
                }
            }
        },
        async mounted(){
            this.bodyHeight = "350"
            let id = window.location.href.split('/').reverse()[0];

            await this.directoryPartnerSet(id)
            this.partner = this.directoryPartner

            this.breadcrumb.push(
                {   
                    text: 'партнеры', 
                    to: `/crm/directory/partners` 
                },
                {   
                    text: this.partner.name,
                    href: `/crm/directory/partners/view/${this.partner.id}` 
                },
            )

        }
    }
</script>