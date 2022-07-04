<template>
    <form class="generator">
        <div class="container">
            <h1 class="generator__title">Создать наклейки</h1>

            <div class="generator__buttons">
                <div class="generator__buttons-left">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-generator-download-result">
                        <svg><use xlink:href="#svg-download"></use></svg>
                        Скачать наклейки
                    </button>
                <button @click="previewDocument" type="button" class="btn btn-outline-primary">
                        <svg><use xlink:href="#svg-preview"></use></svg>
                        Предпросмотр
                    <span v-show="isWaiting" class="spinner-grow spinner-grow-sm" role="status"></span>
                    </button>
                </div>

                <div class="generator__buttons-right">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-generator-import-table">
                        <svg><use xlink:href="#svg-excel"></use></svg>
                        Импорт таблицы
                    </button>
                    <button type="button" class="btn btn-outline-primary">
                        <svg><use xlink:href="#svg-wb"></use></svg>
                        Импорт товаров из WB
                    </button>
                </div>
            </div>

            <div class="generator__params">
                <div class="generator__params-row">
                    <div class="generator__params-col">
                        <p class="generator__params-title small fw-bold">Формат</p>
                        <select class="form-select" v-model="params.format">
                            <option value="EAN-13">EAN-13</option>
                            <option value="CODE-128">CODE-128</option>
                        </select>
                    </div>
                    <div class="generator__params-col">
                        <p class="generator__params-title small fw-bold">Материал</p>
                        <div class="btn-group">
                            <input type="radio" class="btn-check" name="material" id="generator-params-material-0" autocomplete="off"
                                :value="false"
                               @click="changeMaterial"
                                v-model="params.is_termopaper"
                            >
                            <label class="btn btn-outline-primary" for="generator-params-material-0">A4 бумага</label>

                            <input type="radio" class="btn-check" name="material" id="generator-params-material-1" autocomplete="off"
                                :value="true"
                               @click="changeMaterial"
                                v-model="params.is_termopaper"
                            >
                            <label class="btn btn-outline-primary" for="generator-params-material-1">Термоэтикетка</label>
                        </div>
                    </div>

                    <div class="generator__params-col">
                        <p class="generator__params-title small fw-bold">Размер штрих-кода</p>
                        <div class="btn-group">
                            <input type="radio" class="btn-check" name="size" id="generator-params-size-0" autocomplete="off"
                                :value="false"
                                v-model="params.is_double_barcode_size"
                            >
                            <label class="btn btn-outline-primary" for="generator-params-size-0">х1</label>

                            <input type="radio" class="btn-check" name="size" id="generator-params-size-1" autocomplete="off"
                                :value="true"
                                v-model="params.is_double_barcode_size"
                            >
                            <label class="btn btn-outline-primary" for="generator-params-size-1">х2</label>
                        </div>
                    </div>
                    <div class="generator__params-col">
                        <p class="generator__params-title small fw-bold">Размер наклейки</p>
                        <select ref="selectSize" class="form-select" v-model="params.stickerSize" :style="selectWidth ? `width: ${selectWidth}px;` : ''">
                            <option value="66d7x46">66.7x46 мм</option>
                            <option v-if="!params.is_termopaper" value="64d6x33d8">64.6x33.8 мм</option>
                        </select>
                    </div>
                    <div class="generator__params-col">
                        <p class="generator__params-title small fw-bold">Знак ЕАС</p>
                        <div class="btn-group">
                            <input type="radio" class="btn-check" name="eac" id="generator-params-eac-0" autocomplete="off"
                                :value="true"
                                v-model="params.has_eac"
                            >
                            <label class="btn btn-outline-primary" for="generator-params-eac-0">Есть</label>

                            <input type="radio" class="btn-check" name="eac" id="generator-params-eac-1" autocomplete="off"
                                :value="false"
                                v-model="params.has_eac"
                            >
                            <label class="btn btn-outline-primary" for="generator-params-eac-1">Нет</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="generator__buttons">
                <div class="generator__buttons-left">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-generator-settings">
                        <svg><use xlink:href="#svg-settings"></use></svg>
                        Настройка полей
                    </button>
                </div>

                <div class="generator__buttons-right">
                    <button type="button" class="btn btn-outline-secondary"
                        @click="clearAll"
                    >
                        <svg><use xlink:href="#svg-reset"></use></svg>
                        Очистить таблицу
                    </button>
                </div>
            </div>

            <div class="generator__codes">
                <div class="generator__codes-numbers small">
                    <span
                        v-for="(code, index) in codes"
                        :key="index"
                    >
                        {{index + 1}}
                    </span>
                </div>

                <div class="generator__codes-table-wrap"
                    :class="{
                        'has-shadow-left': tableShadow.left,
                        'has-shadow-right': tableShadow.right
                    }"
                >
                    <span class="generator__codes-scrollbar-thumb"
                        :style="{
                            display: tableScrollbarThumb.width === '100%' ? 'none' : 'block',
                            left: tableScrollbarThumb.left,
                            width: tableScrollbarThumb.width
                        }"
                    ></span>
                    <div class="generator__codes-table-container"
                        ref="tableContainer"
                        @scroll="this.onTableContainerScroll"
                    >
                        <table class="generator__codes-table">
                            <tr>
                                <template v-if="spreadsheetData">
                                    <th
                                        v-for="name in keys"
                                        :key="name"
                                        v-show="!excepted.includes(name)"
                                    >
                                        {{name}}
                                    </th>
                                </template>

                                <th
                                    v-for="name in addFields"
                                    :key="name"
                                    v-show="!excepted.includes(name)"
                                >
                                    {{name}}
                                </th>

                                <th></th>
                            </tr>

                            <template v-if="spreadsheetData">
                                <tr
                                    v-for="(row, r_it) in spreadsheetData"
                                    :key="r_it"
                                    v-show="!deleted.includes(r_it)"
                                >
                                    <td
                                        v-for="(field, id) in row"
                                        :key="id"
                                        v-show="!excepted.includes(keys[id])"
                                    >
                                        <input v-if="!isQuantity(id)" type="text" class="form-control"

                                               :value="field"
                                               @focus="onFieldFocus($event, field, id, r_it)"
                                               @blur="onFieldBlur"
                                               @input="changeValue($event.target.value, r_it, id)"
                                        >
                                        <counter v-else
                                                 :value="field"
                                                 :row="r_it"
                                                 :cell="id"
                                                 @input="changeValue"
                                        ></counter>
                                    </td>

                                    <td
                                        v-for="(field, add_id) in addFields"
                                        :key="field"
                                        v-show="!excepted.includes(field)"
                                    >
                                        <input type="text" class="form-control"

                                               :value="addData[add_id][r_it]"
                                               @focus="onFieldFocus($event, field, add_id+keys.length, r_it)"
                                               @blur="onFieldBlur"
                                               @input="changeNewField($event.target.value, r_it, add_id)"
                                        >
                                    </td>

                                    <td>
                                        <button type="button" class="generator__code-delete btn btn-light"
                                                @click="removeCode(r_it)"
                                        >
                                            <svg><use xlink:href="#svg-close"></use></svg>
                                        </button>
                                    </td>
                                </tr>

                                <tr
                                    v-for="(row, r_it) in appended"
                                    :key="r_it+elementsCount"
                                >
                                    <td
                                        v-for="(field, id) in row"
                                        :key="id"
                                        v-show="!excepted.includes(keys[id])"
                                    >
                                        <input v-if="!isQuantity(id)" type="text" class="form-control"

                                               :value="field"
                                               @focus="onFieldFocus($event, field, id, r_it+elementsCount)"
                                               @blur="onFieldBlur"
                                               @input="changeValue($event.target.value, r_it+elementsCount, id)"
                                        >
                                        <counter v-else
                                                 :value="field"
                                                 :row="r_it+elementsCount"
                                                 :cell="id"
                                                 @input="changeValue"
                                        ></counter>
                                    </td>

                                    <td
                                        v-for="(field, add_id) in addFields"
                                        :key="field"
                                        v-show="!excepted.includes(field)"
                                    >
                                        <input type="text" class="form-control"

                                               :value="addData[add_id][r_it+elementsCount]"
                                               @focus="onFieldFocus($event, field, add_id+keys.length, r_it+elementsCount)"
                                               @blur="onFieldBlur"
                                               @input="changeNewField($event.target.value, r_it+elementsCount, add_id)"
                                        >
                                    </td>

                                    <td>
                                        <button type="button" class="generator__code-delete btn btn-light"
                                                @click="deleteCode(r_it)"
                                        >
                                            <svg><use xlink:href="#svg-close"></use></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                        </table>

                        <button type="button" class="generator__btn-fill-column btn btn-primary"
                            ref="btnFillColumn"
                            v-show="btnFillColumn.show"
                            @click="fillColumn"
                            @blur="removeBtnFillColumn"
                        >Применить ко всему столбцу</button>
                    </div>
                </div>

                <div class="generator__codes-footer">
                    <button type="button" class="generator__codes-add btn btn-success"
                        @click="appendCodes(counterForAppend)"
                    >
                        <svg><use xlink:href="#svg-plus"></use></svg>
                        Добавить еще
                    </button>
                    <counter v-model="counterForAppend"></counter>
                </div>
            </div>
        </div>

        <import-table @spreadsheetLoaded="loadData"></import-table>

        <settings
            ref="field_settings"
            :params="regularFields"
            :excludedFields="excludedFields"
            @save="updateFields"
            @add="addNewField"
            @deleteField="deleteNewField"
        >
        </settings>

        <div id="modal-generator-download-result" class="custom-modal modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="custom-modal__content">
                    <button type="button" class="custom-modal__close" data-bs-dismiss="modal">
                        <svg><use xlink:href="#svg-close"></use></svg>
                    </button>
                    <p class="h2">Получить наклейки</p>
                    <div class="custom-modal__text">
                        <p>
                            🚨  После печати проверьте считываемость штрихкода приложением на Вашем телефоне.
                            Цифры при сканировании должны совпасть с цифровым штрихкодом
                        </p>
                    </div>

                    <div class="generator__params-row">
                        <div class="generator__params-col">
                            <p class="generator__params-title small fw-bold">Формат</p>
                            <select class="form-select" v-model="params.format">
                                <option value="EAN-13" selected>EAN-13</option>
                                <option value="CODE-128">CODE-128</option>
                            </select>
                        </div>
                        <div class="generator__params-col">
                            <p class="generator__params-title small fw-bold">Материал</p>
                            <div class="btn-group">
                                <input type="radio" class="btn-check" name="material-modal" id="generator-download-result-params-material-0" autocomplete="off"
                                    :value="false"
                                    @click="changeMaterial"
                                    v-model="params.is_termopaper"
                                >
                                <label class="btn btn-outline-primary" for="generator-download-result-params-material-0">A4 бумага</label>

                                <input type="radio" class="btn-check" name="material-modal" id="generator-download-result-params-material-1" autocomplete="off"
                                    :value="true"
                                    @click="changeMaterial"
                                    v-model="params.is_termopaper"
                                >
                                <label class="btn btn-outline-primary" for="generator-download-result-params-material-1">Термоэтикетка</label>
                            </div>
                        </div>
                        <div class="generator__params-col">
                            <p class="generator__params-title small fw-bold">Размер наклейки</p>
                            <select class="form-select" v-model="params.stickerSize">
                                <option value="66d7x46">66.7x46 мм</option>
                                <option v-if="!params.is_termopaper" value="64d6x33d8">64.6x33.8 мм</option>
                            </select>
                        </div>
                    </div>

                    <div class="custom-modal__checks">
                        <p class="custom-modal__checks-title fw-bold">Дополнительные поля:</p>
                        <div class="custom-modal__checks-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="generator-download-result-checkbox-0"
                                    v-model="params.do_merge_color_and_size"
                                >
                                <label class="form-check-label" for="generator-download-result-checkbox-0">
                                    Цвет и размер в одну строку
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="generator-download-result-checkbox-1"
                                    v-model="params.do_split_barcode_and_info"
                                >
                                <label class="form-check-label" for="generator-download-result-checkbox-1">
                                    Разделить штрих-код и информацию на две наклейки
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="generator-download-result-checkbox-2"
                                    v-model="params.is_dashed"
                                >
                                <label class="form-check-label" for="generator-download-result-checkbox-2">
                                    Линии обреза
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="generator-download-result-checkbox-3"
                                    v-model="params.has_eac"
                                >
                                <label class="form-check-label" for="generator-download-result-checkbox-3">
                                    Знак ЕАС
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="generator-download-result-checkbox-4"
                                    v-model="params.is_certified"
                                >
                                <label class="form-check-label" for="generator-download-result-checkbox-4">
                                    Товар не подлежит обязательной сертификации
                                </label>
                            </div>
                        </div>
                    </div>

                    <button @click="generateBarcodes" type="button" class="custom-modal__btn btn btn-primary btn-lg" data-bs-dismiss="modal">Скачать наклейки</button>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import Counter from '../components/Counter.vue';
import ImportTable from './ImportTable.vue';
import Settings from './Settings.vue';
import FileDownload from 'js-file-download';

export default {
    props: {
        spreadsheet: {
            type: Array,
            default: ()=>[]
        },
        /** only required fields */
        fields: {
            type: Object,
            default: ()=>null
        },
        /** all column names */
        keys: {
            type: Array,
            default: ()=>[]
        },
        id: {
            type: Number,
            default: ()=>0
        },
        excludedFields: {
            type: Array,
            default: ()=>[]
        }
    },
    components: {
        Counter,
        ImportTable,
        Settings,
    },

    data() {
        return {
            params: {
                format: 'EAN-13',
                is_termopaper: false,
                is_double_barcode_size: false,
                stickerSize: '66d7x46',
                has_eac: true,
                do_merge_color_and_size: false,
                do_split_barcode_and_info: false,
                is_dashed: false,
                is_certified: false,
            },

            codeRow: {
                code: '',
                quantity: 1,
                fields: [
                ],
            },

            codesQuantity: 1,
            codes: [],

            tableShadow: {
                left: false,
                right: false,
            },

            tableScrollbarThumb: {
                left: 0,
                width: '100%'
            },

            counterForAppend: 1,

            btnFillColumn: {
                show: false,
                field: null,
                fieldIndex: null,
                rowIndex: null,
            },

            spreadsheetData: null,
            elementsCount: 0,
            spreadsheetBaseItem: null,

            errors: {},
            selectWidth: null,
            isWaiting: false,

            regularFields: [],
            appended: [],
            deleted: [],
            changed: [],
            excepted: [],
            addFields: [],
            addData: [],
        }
    },

    beforeMount() {
        //
        this.updateSpreadsheet = _.debounce(this.updateSpreadsheet, 5000)
        this.onFieldFocus = _.debounce(this.onFieldFocus, 200)
        this.removeBtnFillColumn = _.debounce(this.removeBtnFillColumn, 200)

        //
        this.spreadsheetData = this.spreadsheet
        this.elementsCount = this.spreadsheetData.length
        this.excepted = this.excludedFields

        //
        const req_fields = Object.values(this.fields);
        this.regularFields = this.keys.filter(val => !req_fields.includes(val))

        this.updateBaseItem()

        if(!this.spreadsheetData.length)
            this.appendCodes(1)
    },

    mounted() {

        this.$refs.tableContainer.dispatchEvent(new Event('scroll'));

        //
        this.selectWidth = this.$refs.selectSize.getBoundingClientRect().width
    },

    methods: {
        isSafari() {
            return /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
        },

        changeNewField: function(value, row, cell) {
            this.addData[cell][row] = value
            this.updateSpreadsheet();//TODO server ddos
        },

        addNewField(new_field) {
            this.addFields.push(new_field)
            this.addData.push(
                Array(this.spreadsheetData.length + this.appended.length).fill('')
            )
        },

        deleteNewField(id) {

            this.addFields.splice(id, 1);
            this.addData.splice(id, 1);
        },

        isQuantity(id) {

            return this.fields && this.fields.quantity &&
                this.keys.indexOf(this.fields.quantity) === id
        },

        updateBaseItem() {

            if(this.spreadsheetData && this.spreadsheetData.length) {
                const row = this.spreadsheetData[0]
                this.spreadsheetBaseItem = row.map((_, key)=> this.isQuantity(key) ? 0 : '')
            }
            else {
                const buff = []
                for(const key in this.fields) {
                    buff.push(key === 'quantity' ? 0 : '')
                }
                this.spreadsheetBaseItem = buff
            }
        },

        onTableContainerScroll(e) {
            const scrollLeft = e.target.scrollLeft;

            this.tableShadow.left = scrollLeft > 0;
            this.tableShadow.right = scrollLeft < e.target.scrollWidth - e.target.offsetWidth;

            this.tableScrollbarThumb.left = `${100 * scrollLeft / e.target.scrollWidth}%`;
            this.tableScrollbarThumb.width = `${100 * e.target.offsetWidth / e.target.scrollWidth}%`;
        },

        appendCodes(quantity) {
            //
            Array(quantity).fill().map(()=>{
                this.appended.push(
                    _.cloneDeep(this.spreadsheetBaseItem)
                )
            })

            for(const it of this.addData) {
                it.push('')
            }
        },

        removeCode(index) {
            this.deleted.push(index)//TODO
            this.updateSpreadsheet()//TODO server ddos
        },

        deleteCode(index) {
            this.appended.splice(index, 1);

            for(const it of this.addData) {
                it.splice(index+this.elementsCount, 1);
            }
            this.updateSpreadsheet()//TODO server ddos
        },

        clearAll() {
            if (!confirm('Вы уверены, что хотите очистить таблицу?')) return;

            if(this.spreadsheetData) {
                this.spreadsheetData.length = 0;
                this.appended.length = 0;
                this.deleted.length = 0;
                this.addData = Array(this.addFields.length).fill([])
            }

            this.appendCodes(1);
            this.updateSpreadsheet()//TODO server ddos
        },

        onFieldFocus(event, field, fieldIndex, rowIndex) {
            const td = event.target.parentElement;

            this.btnFillColumn.show = true;
            this.btnFillColumn.field = field;//no need
            this.btnFillColumn.fieldIndex = fieldIndex;
            this.btnFillColumn.rowIndex = rowIndex;
            td.append(this.$refs.btnFillColumn);
        },

        onFieldBlur(e) {
        //TODO what is it
            if (e.relatedTarget === this.$refs.btnFillColumn) return;

           this.removeBtnFillColumn();
        },

        fillColumn() {

            //TODO
            const new_field_id = this.btnFillColumn.fieldIndex - this.keys.length
            if(new_field_id >= 0) {
                this.$set(
                    this.addData, new_field_id,
                    Array(this.addData[new_field_id].length).fill(this.addData[new_field_id][this.btnFillColumn.rowIndex])
                )
                this.updateSpreadsheet()//TODO server ddos
                return;
            }

            //
            const row_id = this.btnFillColumn.rowIndex
            const field_id = this.btnFillColumn.fieldIndex
            const value = row_id >= this.spreadsheetData.length ?
                this.appended[row_id-this.elementsCount][field_id] : this.spreadsheetData[row_id][field_id]

            //
            this.spreadsheetData.map((row)=>row[field_id]=value)
            this.appended.map((row)=>row[field_id]=value)

            //
            this.removeBtnFillColumn();

            this.updateSpreadsheet()//TODO server ddos
        },

        changeValue: function (value, row=undefined, cell=undefined) {

            const row_id = row ?? this.btnFillColumn.rowIndex
            const field_id = cell ?? this.btnFillColumn.fieldIndex

            if(row_id >= this.spreadsheetData.length) {
                const idx = row_id-this.elementsCount
                const r=this.appended[idx];
                r[field_id] = value;
                this.$set(this.appended, idx, r);
            }
            else {
                const r=this.spreadsheetData[row_id]
                r[field_id] = value
                this.$set(this.spreadsheetData, row_id, r)
            }

            //
            this.updateSpreadsheet();//TODO server ddos
        },

        removeBtnFillColumn() {
            this.btnFillColumn.show = false;
            this.btnFillColumn.field = null;
            this.btnFillColumn.fieldIndex = null;
        },

        updateFields({uncheckedFields}) {

            this.excepted = uncheckedFields
            this.updateSpreadsheet()//TODO server ddos
        },

        loadData(data) {
            //console.log(data)
        },

        //TODO here fully rewriting table
        updateSpreadsheet: function (){

            this.$refs.field_settings.applyChanges()

            const spreadsheet = [].concat(
                this.spreadsheetData
                    .map((item, key)=>{
                        return item.concat(this.addData.map((field)=>field[key]))
                    })
                    .filter((item, key)=>!this.deleted.includes(key)),
                this.appended
                    .map((item, key)=>{
                        return item.concat(this.addData.map((field)=>field[key+this.elementsCount]))
                    })
            )

            const data = JSON.stringify({
                columns: this.keys.concat(this.addFields),
                rows: spreadsheet,
                excluded: this.excepted
            })

            const form = new FormData();
            form.append('spreadsheet', data)

            axios.post('/spreadsheet/update_spreadsheet/' + this.id, form).then((res)=>{
                console.log('ok')
            })
            .catch((err) => {
                console.error(err)
            })
        },

        async generateBarcodes() {


            const config = {
                responseType: 'blob',
            }
            await axios.post(`/spreadsheet/generate/${this.id}`, this.params,config).then((response) => {

                //TODO probably it's crutch
                if(this.isWaiting) {
                    const blob = new Blob([response.data], { type: 'application/pdf' })
                    const url = window.URL.createObjectURL(blob)
                    const preview = window.open()
                    preview.location = url
                }
                else {
                    //dd-mm-yy_hh-mm-ss
                    const date = () => {
                        const padTo2Digits = (num)=>num.toString().padStart(2, '0')
                        const date = new Date
                        return [
                            padTo2Digits(date.getDate()),
                            padTo2Digits(date.getMonth() + 1),
                            date.getFullYear(),
                        ].join('-')
                        + '_' +
                        [
                            padTo2Digits(date.getHours()),
                            padTo2Digits(date.getMinutes()),
                            padTo2Digits(date.getSeconds()),
                        ].join('-')
                    }
                    const time = date()
                    FileDownload(response.data, `wb-robot_${time}.pdf`)
                }

            }).catch((err) => {

                //this.$set(this.errors, 'import_error', err);
            });
        },

        async previewDocument() {
            this.isWaiting = true
            await this.generateBarcodes()
            this.isWaiting = false
        },

        changeMaterial() {
            this.params.stickerSize = '66d7x46'
        }
    },
}
</script>

<style>

</style>
