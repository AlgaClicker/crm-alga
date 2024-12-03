<template>
    <b-modal
        id="specification-master-modal-info"
        ref="specification-master-modal-info"
        title="Информация"
        content-class="c-modal-default c-specifications-master-modal-info"
        centered 
        hide-footer
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>{{ specificationProps.name }}</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-specifications-master-modal-info__body">
            <b-row>
                <b-col cols="6">
                    <b-row>
                        <b-col class="mb-4" cols="12">
                            <label class="label-custom" for="input-name">Название спецификации</label>
                            <b-form-input
                                id="input-name"
                                class="input-custom"
                                aria-describedby="input-name-help input-name-feedback"
                                :value='specificationProps.name'
                                placeholder="Введите название спецификации"
                                trim
                            ></b-form-input>
                            <b-form-invalid-feedback id="input-name-feedback">
                                Название объекта должно быть длиннее шести символов 
                            </b-form-invalid-feedback>
                        </b-col>
                        <b-col class="mb-4" cols="12">
                            <label class="label-custom" for="input-locnum">Расположение объекта</label>
                            <b-form-input
                                id="input-locnum"
                                class="input-custom"
                                aria-describedby="input-locnum-help input-locnum-feedback"
                                :value="specificationProps.locnum"
                                placeholder="Введите расположение объекта"
                                trim
                            ></b-form-input>
                            <b-form-invalid-feedback id="input-locnum-feedback">
                                Название объекта должно быть длиннее шести символов 
                            </b-form-invalid-feedback>
                        </b-col>
                        <b-col class="mb-4" cols="12">
                            <label class="label-custom" for="input-description">Описание спецификации</label>
                            <b-form-textarea
                                id="input-description"
                                class="input-custom"
                                aria-describedby="input-description-feedback"
                                :value="specificationProps.description"
                                placeholder="Введите описание..."
                                trim
                                rows="3"
                                no-resize
                            ></b-form-textarea>
                        </b-col>
                    </b-row>
                </b-col>
                <b-col cols="6">
                    <label class="label-custom" for="input-responsible">Подписчики</label>       
                    <div v-for="item in specificationProps.responsibles" :key="item.id" class="c-workpeople-list">
                        <base-avatar
                            :avatarProfileProps="item"
                        />
                        <span class="c-workpeople-list_text">
                            {{ item.username }} <small> {{ item.roles.name }} </small>
                        </span>
                    </div>
                </b-col>
            </b-row>
            <b-row v-show="specificationProps.files?.length < 0">
                <b-col>
                    <label class="label-custom mt-3" for="input-responsible">Файлы</label>
                    <div class="c-specification-modal-info-body__files-wrapper">
                        <file
                            class="mt-1"
                            v-for="file in specificationProps.files" :key="file.hash"
                            :fileProps="file"
                            :deleteProps=true
                            @deleteFileEmit="deleteFile(file.hash)"
                        />
                    </div>
                </b-col>        
            </b-row>
        </div>
    </b-modal>
</template>

<script>
    //import { mapActions, mapGetters } from 'vuex' 
    import File from '@/components/elements/files/File'

    import BaseAvatar from '@/components/elements/avatars/BaseAvatar'

    export default {
        name: 'SpecificationMasterModalInfo',
        data(){
            return {
      
            }
        },
        components: {
            File,
            BaseAvatar
        },
        props: {
            specificationProps:  { type: Object, default: () => {}},
        },
        methods: {
            hideModal(){
                this.$refs['specification-master-modal-info'].hide();
            },
            mountedData(){

            }
        }

  
    }
</script>