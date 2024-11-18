<template>
    <div class="c-bank-account-card">
        <div class="name-wrapper">
            <div class="name-wrapper__name">
                {{ bankAccountProps.bank.name }}
            </div>
            <span class="name-wrapper__delete"  @click="deleteBankAccount()">
                <base-icon iconProps="trash" sizeProps="md" />
            </span>
        </div>
        <div class="account">
            {{ bankAccountProps.account }}
        </div>
    </div>
</template>

<script>
    import BaseIcon from "@/components/elements/BaseIcon"
    import { mapActions, mapGetters } from "vuex";
    
    export default {
        name: "DirectoryPartnersBankAccountCard",
        props: {
            bankAccountProps: { type: Object, default: function () {} },
        },
        components: {
            BaseIcon
        },
        computed: {
            ...mapGetters({
                "directoryPartner": "directoryPartnerGetter"
            })
        },
        methods: {
            ...mapActions({
                directoryPartnersDeleteBankAccount: "directoryPartnersDeleteBankAccountActions"
            }),
            deleteBankAccount(){
                this.directoryPartnersDeleteBankAccount({
                    idAccount: this.bankAccountProps.id,
                    idPartner: this.directoryPartner.id
                })
            }
        }
    }
</script>
