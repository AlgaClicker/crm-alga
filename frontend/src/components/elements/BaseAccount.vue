<template>
    <div class="responsible-employee">
        <div class="avatar">
            <span class="avatar__button" @click="removeAccount()"> 
                <b-icon icon="x" scale="0.8"></b-icon>
            </span>
            <div v-if="typeof accountProps?.username != 'undefined'" class="avatar__title">
                {{ accountProps?.username.split('')[0] }}{{ accountProps?.username.split('')[1] }}
            </div>
            <div v-if=" typeof accountProps?.username == 'undefined'" class="avatar__title">
                {{ accountProps?.name.split('')[0] }}{{ accountProps?.name.split('')[1] }}
            </div>
        </div>
        <div class="title">
            <div v-if="typeof accountProps?.username != 'undefined'" class="title__name">
                {{ accountProps?.username }}
            </div>
            <div v-if="typeof accountProps?.username == 'undefined'" class="title__name">
                {{ accountProps?.name }}
            </div>
            <span v-if="typeof accountProps.roles != 'undefined'" class="title__role">
                {{ accountProps.roles?.name }}
            </span>
            <span v-if="typeof accountProps.roles == 'undefined'" class="title__role">
                {{ accountProps.profession?.name }}
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'BaseAccount',
        props: {
            accountProps: { type: Object, default: () => {
                return {
                    username: '',
                }
            }},
            isEdit: { type: Boolean, default: false}
        },
        emits: ['deleteAccountEmit'],
        methods: {
            removeAccount(){
                this.$emit('deleteAccountEmit', this.accountProps.id)
            }
        },
    }
</script>

<style lang="scss">
    .responsible-employee{
        display: flex;
        .avatar{
            border-radius: 50%;
            background-color: #b5d7fb;
            height: 40px;
            width: 40px;
            display: flex;
            flex-direction: column;
            &__button{
                margin-left: auto;
                display: flex;
                background-color: #FFFFFF;
                border-radius: 50%;
                border: 1px solid #C5C5C5;
                cursor: pointer;
                width: 12px;
                height: 12px;
                padding: 1px;
                justify-content: center;
                align-items: center;
            }
            &__title{
                align-items: center;
                color: #686d74;
                display: flex;
                justify-content: center;
                margin-top: -0.3rem;
            }
        }
        .title{
            margin-left: 0.8rem;
            &__name{
                top: -4px;
                color: #46405e;
                position: relative;
                font-size: 1.1rem;
            }
            &__role{
                color: #9b9eb1;
                font-size: 0.9rem;
                position: relative;
                top: -9px;
            }
        }
    }
</style>
