    <template>
        <b-modal
            id="supply-requisition-modal-add-invoice"
            ref="supply-requisition-modal-add-invoice"
            title="Добавить материал"
            content-class="c-modal-default c-modal-supply-requisition-add-invoice"
            centered 
            no-close-on-backdrop
            no-close-on-esc
            @shown="mountedData"
            @hide="clearSelect"
        >
            <template #modal-header>
                <h5>Прикрепить счета</h5>
                <button @click="hideModal">
                    <b-icon icon="x" scale="1"></b-icon>
                </button>
            </template>
            <div class="c-body c-body-invoice">
                <b-row>
                    <b-col sm="12" lg="6">
                        <label class="label-custom" for="input-name">Дата поставки</label>
                        <date-picker
                            style="width: 100%" 
                            input-class='mx-input-modal'
                            v-model="newInvoice.delivery_at"
                        >
                        </date-picker>
                    </b-col>
                    <b-col sm="12" lg="6">
                        <label class="label-custom" for="input-name">Договоры</label>
                        <multiselect 
                            v-model="newInvoice.contract" 
                            :options="contractsList"
                            label="number"
                            placeholder="Введите номер договора" 
                            :searchable="true"
                            :loading="directoryContractsLoading"
                            :internal-search="false"
                            :clear-on-select="false" 
                            :options-limit="300" 
                            :limit="3" 
                            :max-height="600" 
                            :show-no-results="true" 
                            :show-labels="false" 
                            @search-change="asyncFind" 
                            class='mb-2' 
                            :class="{ 'input-custom--invalid': $v.newInvoice.contract.$error}"
                        >
                            <template slot="option" slot-scope="props">
                                <div class="option__desc">
                                    {{ props.option.number }} 
                                    <span class="c-list-spec__object"> ( {{ props.option.partner.name }} )</span>
                                </div>
                            </template>
                        </multiselect>
                    </b-col>
                </b-row>
                <b-row class="mt-2">
                    <b-col sm="12" lg="6">
                        <label class="label-custom" for="input-name">Склад</label>
                        <multiselect 
                            v-model="newInvoice.stock"  
                            placeholder="Выберите склад" 
                            :options="StocksProps" 
                            label="name"
                            track-by="name"
                            :show-labels="false"
                        >
                            <template slot="option" slot-scope="props">
                                <div class="option__desc">
                                    {{ props.option.name }}
                                </div>
                            </template>
                        </multiselect>
                    </b-col>
                    <b-col>
                        <label class="label-custom mt-2" for="input-name">Комментарий</label>
                        <b-form-textarea
                            id="input-description"
                            class="input-custom"
                            aria-describedby="input-description-feedback"
                            v-model="newInvoice.description"
                            placeholder="Введите описание..."
                            trim
                            rows="5"
                            no-resize
                        ></b-form-textarea>
                    </b-col>
                </b-row>
                <b-row class="scroll-wrapper-table">
                    <label class="label-custom mt-4" for="input-name">Материалы</label>
                    <div class='c-master-requisitions-universal-table mt-2'>
                        <table>
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th width="7%">Ед. изм</th>
                                    <th width="6%">Кол по заявке</th>
                                    <th width="8%">Заказать</th>
                                    <th width="7%">Заказано</th>
                                    <th width="30%">Файлы</th>
                                    <th width="10%">Ценна за штуку</th>
                                    <th>Комментарий</th>
                                </tr>
                            </thead>
                        </table>
                        <div class='c-master-requisitions-universal-table__body' :style="{ maxHeight: 400 + 'px'}" >
                            <table>
                                <tbody> 
                                    <tr v-for="item in supplyRequisitionMaterialNewInvoice.materials" :key="item.id" >
                                        <td>
                                            <base-textarea v-model="item.fullname" :valueProps="item.fullname"> </base-textarea>
                                        </td>
                                        <td width="7%">
                                            <input v-model="item.unit" />
                                        </td>
                                        <td width="6%">
                                           {{ item.order }}
                                        </td>
                                        <td width="8%">
                                            <input v-model="item.quantity" />
                                        </td>
                                        <td width="7%">
                                           {{ item.ordered }}
                                        </td>
                                        <td width="30%" class="attach-files">
                                            <files-block-attach 
                                                :fileProps="item.files"
                                                :idMaterialProps="item.id"
                                                @addFileEmit="addFile"
                                                @deleteFileEmit="deleteFile"
                                                @setFlagEmit="setFlag"
                                                @resetFlagEmit="resetFlag"
                                            />
                                        </td>
                                        <td width="10%">
                                            <input
                                                v-maska
                                                data-maska="0.99"
                                                data-maska-tokens="0:\d:multiple|9:\d:optional" 
                                                v-model="item.price" 
                                            />
                                        </td>
                                        <td>
                                            <base-textarea 
                                                v-model="item.description" 
                                                :valueProps="item.description"> 
                                            </base-textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </b-row>
            </div>
            <template #modal-footer>
                <button 
                    @click="addInvoice()" 
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
        import { validationMixin } from "vuelidate";
        import { required } from 'vuelidate/lib/validators';
        import { dateMin } from '@/utils/customValidations';
        import { vMaska } from "maska";

        import BaseTextarea from "@/components/elements/BaseTextarea";
        import FilesBlockAttach from '@/components/elements/files/FilesBlockAttach'

        export default {
            name: 'SupplyRequisitionModalAddInvoice',
            directives: { maska: vMaska },
            data(){
                return {  
                    errUpload: null,
                    showProgress: false,
                    files: [],
                    newFile: {},
                    idMaterial: '',
                    newInvoice: {
                        delivery_at: "", 
                        partner: "",
                        contract: {},
                        stock: "",
                        description: "",
                        materials: []
                    },
                    uploaded: [],
                    contractsList: [],
                    flagUpload: 0
                }
            },
            mixins: [validationMixin],
            validations: {
                newInvoice: {
                    delivery_at: {
                        required,
                        dateMin
                    },
                    contract: required
                }
            },
            props: {
                MaterialsProps:  { type: Array, default: () => [] },
                StocksProps: { type: Array, default: () => [] }, 
                ContractsProps: { type: Array, default: () => [] }
            },
            computed: {
                ...mapGetters({
                    supplyMyRequisition: 'supplyMyRequisitionGetter',
                    directoryContractsLoading: 'directoryContractsLoadingGetter',
                    directoryContractsSupplyList: 'directoryContractsSupplyListGetter',
                    supplyRequisitionMaterialError: 'supplyRequisitionMaterialErrorGetter',
                    supplyRequisitionMaterialNewInvoice: 'supplyRequisitionMaterialNewInvoiceGetter',
                }),
                validationsComputed(){
                    if( 
                        this.$v.newInvoice.delivery_at.$invalid == false &
                        this.supplyRequisitionMaterialNewInvoice.materials.filter( item => item.fullname == '').length == 0 &
                        this.supplyRequisitionMaterialNewInvoice.materials.filter( item => item.files.length == 0).length == 0 &
                        this.supplyRequisitionMaterialNewInvoice.materials.filter( item => item.price == 0 | item.price == '').length == 0 & 
                        this.supplyRequisitionMaterialNewInvoice.materials.filter( item => item.quantity == 0 | item.quantity == '').length == 0 
                    ){
                        return true
                    }
                    else {
                        return false  
                    }
                },
            },
            components: {
                FilesBlockAttach,
                BaseTextarea
            },
            methods: {
                ...mapActions({
                    directoryContractsSearch: 'directoryContractsSearchActions',
                    directoryContractsSetList: 'directoryContractsSetListActions',
                    supplyRequisitionMaterialSetError: 'supplyRequisitionMaterialSetErrorActions',
                    supplyRequisitionMaterialClearSelect: 'supplyRequisitionMaterialClearSelectActions',
                    supplyRequisitionMaterialAddInvoices: 'supplyRequisitionMaterialAddInvoicesActions',
                    supplyRequisitionMaterialNewInvoiceSet: 'supplyRequisitionMaterialNewInvoiceSetActions',
                    supplyRequisitionMaterialDeleteAttachFile: 'supplyRequisitionMaterialDeleteAttachFileActions',
                    supplyRequisitionMaterialAttachFileForInvoices: 'supplyRequisitionMaterialAttachFileForInvoicesActions',
                }),
                setIdUpload(id){
                    this.idMaterial = id
                },
                addFile(params){
                    this.supplyRequisitionMaterialAttachFileForInvoices(params)
                },
                deleteFile(params){
                    this.supplyRequisitionMaterialDeleteAttachFile(params.hash)
                },
                setFlag(){
                    this.flagUpload += 1
                },
                resetFlag(){
                    this.flagUpload -= 1
                },
                async addInvoice(){

                    let invoice = {
                        delivery_at: '',
                        contract: '',
                        stock: '',
                        description: '',
                        materials: [{}]
                    }

                    invoice.delivery_at = this.newInvoice.delivery_at
                    invoice.contract = this.newInvoice.contract.id
                    invoice.stock = this.newInvoice.stock.id
                    invoice.description = this.newInvoice.description
                    invoice.materials = this.newInvoice.materials.materials 
                    
                    for(let i = 0; i < invoice.materials.length; i++){
                        invoice.materials[i].files = invoice.materials[i].files.map(item => item.hash)
                    }

                    await this.supplyRequisitionMaterialAddInvoices({ idRequisition: this.supplyMyRequisition.id, newInvoice: invoice})

                    if(!this.supplyRequisitionMaterialError){
                        this.hideModal()
                    }
                },
                clearSelect(){
                    this.supplyRequisitionMaterialClearSelect()
                },
                hideModal(){
                    if(this.flagUpload == 0){
                        this.supplyRequisitionMaterialClearSelect()
                        this.$refs['supply-requisition-modal-add-invoice'].hide();
                    }
                },
                async asyncFind(query){
                    if( query.length >= 2 ){
                        await this.directoryContractsSearch(query)
                        this.contractsList = this.directoryContractsSupplyList
                    } else {
                        this.directoryContractsSearch(null).then( () => this.contractsList = this.directoryContractsSupplyList)
                    }
                },
                async mountedData(){
                    await this.directoryContractsSetList() 
                    this.supplyRequisitionMaterialNewInvoiceSet(this.MaterialsProps)

                    this.newInvoice.delivery_at = ''
                    this.newInvoice.contract = ''
                    this.newInvoice.stock = ''
                    this.newInvoice.description = ''

                    this.newInvoice.materials = this.supplyRequisitionMaterialNewInvoice
                    this.contractsList = this.directoryContractsSupplyList
                },
            },
        }
    </script>