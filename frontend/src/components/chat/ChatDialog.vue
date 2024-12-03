<template> 
    <div class="c-chat-dialog" @click="select()" :class="{ 'c-chat-dialog--selected' : selectedDialogProps?.id == dialogProps?.id }">
        <div class="content">
            <div class="header">
                <div class="header__name">{{ dialogProps.username }}</div>
                <div class="header__date"> 1998.05.20</div>
            </div>

        </div>
    </div>
</template> 
<script>

    export default {
        name: 'ChatDialog',
        components: {
            
        },
        props: {
            dialogProps: { type: Object, default: function () {} },
            selectedDialogProps: { type: Object, default: function () {} },
        },
        emits: ['selectRoomEmit'],
        methods: {
            select(){
                this.$emit( 'selectRoomEmit', this.dialogProps)
            }
        }
    }
</script>