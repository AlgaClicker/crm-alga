<template>
   <div class="c-tabel-material-body-groupe">
        <div class="c-tabel-material-body-groupe-row">
            <div class="c-tabel-material-body-groupe-row_name" :style="{ paddingLeft: levelProps * 20 + 'px !important'}">
                <svg
                    v-if="isVisible"
                    @click="toggleVisibleGroup"
                    class="c-caret-right"
                    width="24" 
                    height="24" 
                >
                    <use xlink:href="@/assets/icons/sprite.svg#caret-right"></use>
                </svg>
                <svg
                    v-else
                    @click="toggleVisibleGroup"
                    class="c-caret-right"
                    width="24" 
                    height="24" 
                >
                    <use xlink:href="@/assets/icons/sprite.svg#caret-right"></use>
                </svg>
                <input 
                    class="с-checkbox" 
                    type="checkbox"
                    :checked=" groupeProps.selected"
                    @change="directoryPartnersChecked(groupeProps)"
                >
                {{ groupeProps.name }}
            </div>
        </div>
        <div class="c-tabel-material-body" v-show="isVisible" style="border: 0;" v-for="(item, key) in materialsGroupe" :key="key">
            <DirectoryMaterialsGroupeRows 
                v-if="item.isGroup"
                :groupeProps="item" 
                :unitsProps="unitsProps"
                :order="order + '.' + String(Number(key + 1))" 
                :levelProps="levelProps + 1"
            />
            <DirectoryMaterialsRows 
                v-else
                :materialProps="item" 
                :order="order + '.' + String(Number(key + 1))"
                :levelProps="levelProps + 1"
                :unitsProps="unitsProps"
                v-on:deleteElement="removeItem(item)"
            />
        </div > 
    </div>
</template>
<script>
    import { mapActions } from 'vuex'

    import DirectoryMaterialsGroupeRows from '@/components/directory/DirectoryMaterialsGroupeRows'
    import DirectoryMaterialsRows from '@/components/directory/DirectoryMaterialsRows'

    export default {
        name: 'DirectoryPartnersGroupeRow',
        data(){
            return {
                isVisible: false
            }
        },
        components: {
            DirectoryMaterialsGroupeRows,
            DirectoryMaterialsRows,
        },
        props: {
            groupeProps: { type: Object, default: function () {} },
            order: { type: String, default: '' },
        },
        methods: {
            ...mapActions({
                directoryPartnersChecked: 'directoryPartnersCheckedActions',
            }),   
            directoryGroupe(){
                if(this.materialsGroupeList != []){
                    return this.materialsGroupeList.filter(item => item.parent.id == this.groupeProps?.id)
                }
                else{
                    return []
                }
            },
            async toggleVisibleGroup(){
                const params = {
                    parent: this.partnersProps.id,
                    options: this.options
                }
                
                if(this.isVisible == false){
                    await this.materialsGet(params)

                    this.isVisible = !this.isVisible
                }else{
                    this.isVisible = !this.isVisible
                }
            },
        }
    }

</script>