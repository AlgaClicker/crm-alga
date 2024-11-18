<template>
    <b-modal 
       id="stock-modal-add"
       ref="stock-modal-add"
       title="Добавить материал"
       content-class="c-modal-default"
       centered    
       @shown="mountedData"
   >   
       <template #modal-header>
           <h5>Добавить склад</h5>
           <button @click="hideModal">
               <b-icon icon="x" scale="1"></b-icon>
           </button>
       </template>
       <div class="c-body stock-modal-add">
           <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-surname">Название</label>
                    <b-form-input
                        id="input-surname"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.name.$error}"
                        aria-describedby="input-surname-feedback"
                        v-model="form.name"
                        v-model.trim="$v.form.name.$model"
                        :state="!$v.form.name.$error && null"
                        placeholder="Введитите название"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-surname-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-fullname">Полное название</label>
                    <b-form-input
                        id="input-fullname"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.fullname.$error}"
                        aria-describedby="input-fullname-feedback"
                        v-model="form.fullname"
                        v-model.trim="$v.form.fullname.$model"
                        :state="!$v.form.fullname.$error && null"
                        placeholder="Введитите полное название"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-fullname-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-address">Адресс</label>
                    <b-form-input
                        id="input-address"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.address.$error}"
                        aria-describedby="input-address-feedback"
                        v-model="form.address"
                        v-model.trim="$v.form.address.$model"
                        :state="!$v.form.address.$error && null"
                        placeholder="Введитите адресс"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-address-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
           </b-row>
       </div>
       <template #modal-footer>
            <button 
                @click="addStock" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Добавить
            </button>
       </template>
   </b-modal>
</template>
<script>
   import { mapActions, mapGetters } from "vuex";
   
   import { required } from 'vuelidate/lib/validators'
   import { validationMixin } from "vuelidate";

   export default {
       name: 'StockModalAdd',
       data(){
           return {
               form: {
                   name: '',
                   fullname: '',
                   address: '',
               },
           }    
       },
       mixins: [validationMixin],
       validations: {
           form: {
                name: { 
                    required
                },
                fullname: { 
                    required
                },
                address: { 
                    required
                },
           }
       },
       computed: {
           ...mapGetters({
               specificationError: "specificationErrorGetter",
               specification: "specificationGetter"
           }),
           validationsComputed() {
               if(  this.$v.form.name.$invalid  == false && 
                    this.$v.form.fullname.$invalid  == false && 
                    this.$v.form.address.$invalid == false 
               ){
                   return true
               } else {
                   return false  
               }
           }
       },
       methods: {
            ...mapActions({ 
                stockAdd: "stockAddActions",
            }),
            hideModal(){
                this.$refs['stock-modal-add'].hide();
            },
            async addStock(){
                this.stockAdd(this.form)
                this.hideModal()
            },
            mountedData(){
                this.form.name = ''
                this.form.fullname = ''
                this.form.address = ''
                this.$v.$reset()
            }
       }
   }
</script>
