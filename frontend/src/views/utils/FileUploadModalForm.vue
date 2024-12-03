<template>
    <b-modal
        id="file-upload-modal-form"
        ref="file-upload-modal-form"
        size="lg"
        hide-footer
        content-class="c-modal-default c-modal-object"
        v-model="showModal" centered :title=title @close="modalClosed"
    >
      <b-form @submit="onSubmit">
        <b-row class="mt-2 px-3">

          <!--the file list column has different width based on boreakpoint-->
          <!--and is shown only if one or more files are selected-->
          <b-col sm="12" xl="8" v-if="this.filelist.length" v-cloak>

            <!--show a list of the selected files, with an input to (optionally) rename the file before upload-->
            <b-list-group>
              <b-list-group-item class="d-inline-flex p-2" v-for="(file, i) in filelist" :key="i">

                <!--file name input to rename the file before upload-->
                <b-form-input
                    placeholder="File name..."
                    v-model="filelist[i].fileName"
                    required
                />

                <!--file extension-->
                <span class="d-inline-flex align-items-center text-muted ml-1 mr-3">.{{ filelist[i].extension }}</span>

                <!--remove file from list (and from upload)-->
                <b-button variant="outline-danger" size="sm" @click="remove(filelist.indexOf(file))">
                  <b-icon-trash></b-icon-trash>
                </b-button>

              </b-list-group-item>
            </b-list-group>

          </b-col>

          <!--this column contains the file drop area-->
          <!--its width is automatically set based on existance of the file list column and breakpoint-->
          <b-col class="file-drop-column my-4 my-xl-0 p-0">
            <div class="file-drop-area bg-light" @dragover="dragover" @drop="drop">

              <!--we need an actual file input in order to have the drag&drop functionality-->
              <!--and the File object(s) containing the files dropped-->
              <!--We keep it invisible because we want to display something different than a file input-->
              <input type="file" multiple name="file-input" id="file-input"
                     class="file-input invisible" @change="onChange"/>

              <!--clicking on this label will trigger the clicked event on the file input-->
              <!--this will cause the 'choose file' system dialog-->
              <label for="file-input" class="w-100 text-center cursor-pointer text-muted m-0 px-1 py-2">
                <p>
                  <b-icon-cloud-upload-fill font-scale="3"></b-icon-cloud-upload-fill>
                </p>
                <div>
                  <p class="mb-0">Перетащите ваш(и) файл(ы) сюда</p>
                  <p class="mb-0">или <span class="text-info">выберите с компьютера</span></p>
                </div>
              </label>
            </div>
          </b-col>
        </b-row>


        <!--this div contains the two action buttons cancel and upload-->
        <!--It is totally possible to use the bootstrapvue footer buttons instead-->
        <div class="d-flex justify-content-center mt-4">
          <b-button variant="secondary" @click="$bvModal.hide('add-attachments-modal')" class="mr-3 c-button-cancel">
            Отмена
          </b-button>
          <b-button type="submit" variant="success" class="c-button-save">
            Загрузить
          </b-button>
        </div>
      </b-form>
    </b-modal>
</template>

<script>
import { uploadFilesList } from '@/services/files.service.js';
export default {
  name: 'FileUploadModalForm',
  props: {
    title: { type: String, default: "Загрузка файлов"},
    endpoint: { type: String, default: "/"},
    sendData: { type: Object, default: function () {} },
  },
  data() {
    return {
      showModal: false,
      file:"",
      filelist: [],
    }
  },
  methods: {
    modalClosed() {
      console.log("Modal closed");
    },
    remove(i) {
      this.filelist.splice(i, 1);
    },
    dragover(event) {
      event.preventDefault();
    },
    drop(event) {
      event.preventDefault();
      this.onChange();
    },
    onChange(event) {
      const fileArray = [...event.target.files];  //put the files in a temporary array
      fileArray.forEach((file) => {
        const fileName = file.name.replace(/\.[^/.]+$/, "");  //extract filename (no extension) from the file full name
        const extension = file.name.split('.').pop();         //extract extensionfrom the file fullname
        this.filelist.push({fileName, extension, file});      //store selected files and additional data in our array
      })
    },
    onSubmit() {
      event.preventDefault();
      console.log('Perfect! Now implement some way to upload these files: ');
      console.log(this.filelist);
      uploadFilesList(this.filelist,this.endpoint,this.sendData)
    },
  }
}
</script>