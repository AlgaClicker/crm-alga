<template>
    <div v-show="markProps.length != ''" class="c-tabel-mark" :style="{ backgroundColor: colorMark }">
        <span class="text">{{ textMark + ' (' + specificationObjectNameProps + ') ' }} </span>
        <!-- <b-icon
            icon="x"
            size="1.2"
        >
        </b-icon> -->
    </div>
</template>

<script>

import { mapActions } from 'vuex'

    export default {
        name: 'TabelMark',
        props: {
            markProps: { type: String, default: "" },
            cellDate: { type: String, default: "" },
            workpeopleIdProps: { type: String, default: "" },
            specificationObjectNameProps: { type: String, default: "" }
        },
        computed: {
            textMark(){
                if(this.markProps == 'о'){
                    return 'отпуск'
                } else if(this.markProps == 'б'){
                    return 'больничный'
                }else if(this.markProps == 'ну'){
                    return 'прогул по неувож'
                } else if(this.markProps == 'нп'){
                    return 'прогул по увож'
                } else if(this.markProps == 'до'){
                    return 'дополнительный отгул'
                } else {
                    return this.markProps + ' ' + 'ч'
                }
            },
            colorMark(){
                if(this.markProps == 'ну'){
                    return '#FFB3B3'
                }else if(this.markProps == 'б' || this.markProps == 'нп' || this.markProps == 'о' || this.markProps == 'до') {
                    return '#DEFFB3'
                } else {
                    return '#EEEBFF'
                }
            }
        },
        methods: {
            ...mapActions({
                tabelDeleteMark: 'tabelDeleteMarkActions'
            }),
        }
    }
</script>