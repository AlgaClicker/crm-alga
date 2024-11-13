<template>
    <div class="c-responsibles-card">
        <header class="c-responsibles-card__header">
            <div class="c-responsibles-card__title">Ответственные</div>
        </header>
        <div class="c-responsibles-card__wrapper">
            <div class="" v-for="item in responsiblesListProps" :key="item.id">
                <BaseAvatarProfile 
                    class="mt-4"
                    :avatarProfileProps="item"
                />
                
            </div>
        </div>
    </div>
</template>

<script>
    import BaseAvatarProfile from "@/components/elements/avatars/BaseAvatarProfile"
    export default {
        name: 'ResponsiblesTable',
        props: {
            responsiblesListProps: { type: Array, default: function () {} },
        },
        components: {
            BaseAvatarProfile
        }
    }
</script>