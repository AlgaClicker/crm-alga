<template>
    <div :class="{ 'c-tabel-cell--is-current-month': !cellProps.currentMonth }" class="c-tabel-cell" >
        <span class="c-tabel-cell__date c-tabel-cell__label"> {{ cellProps.label }} </span>
    </div>
</template>

<script>
    export default {
        name: "TabelDisabpledCell",
        props: {
            cellProps: { type: Object, default: () => {} }
        },
    }
</script>