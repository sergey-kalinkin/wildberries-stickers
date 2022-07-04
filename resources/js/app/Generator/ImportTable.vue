<template>
    <div ref="modal" id="modal-generator-import-table" class="custom-modal modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="custom-modal__content">
                <button type="button" class="custom-modal__close" data-bs-dismiss="modal">
                    <svg><use xlink:href="#svg-close"></use></svg>
                </button>
                <p class="h2">Импорт таблицы</p>

                <div class="generator-import">
                    <div class="generator-import__nav nav btn-group">
                        <button  @click="changeImportStatement(true)" type="button" class="btn btn-outline-primary active" data-bs-toggle="pill" data-bs-target="#generator-import-excel">Excel-файл</button>
                        <button @click="changeImportStatement(false)" type="button" class="btn btn-outline-primary" data-bs-toggle="pill" data-bs-target="#generator-import-google">Гугл-таблица</button>
                    </div>
                    <div class="generator-import__tab-content tab-content">
                        <div id="generator-import-excel" class="tab-pane fade show active">
                            <ol class="generator-import__steps list-unstyled">
                                <li class="generator-import__step">
                                    <p class="generator-import__step-title h5">1.  Скачайте шаблон для печати наклеек</p>
                                    <div class="generator-import__step-buttons">
                                        <a href="#" class="generator-import__step-btn btn btn-success" download>
                                            <svg>
                                                <use xlink:href="#svg-plus"></use>
                                            </svg>
                                            Скачать Excel файл
                                        </a>
                                        <a href="#" class="generator-import__step-link text-success">Как работать с таблицей?</a>
                                    </div>
                                </li>
                                <li class="generator-import__step">
                                    <p class="generator-import__step-title h5">2. Заполните данные о товарах</p>
                                    <div class="generator-import__step-text">
                                        <p>Все вкладки и столбцы должны иметь правильную очередность. Если у вас возникли трудности с заполнением, воспользуйтесь <a href="#">инструкцией по работе с таблицей</a></p>
                                    </div>
                                </li>
                                <li class="generator-import__step">
                                    <p class="generator-import__step-title h5">3. Загрузите подготовленный Excel-файл</p>
                                    <div class="generator-import__upload-file">
                                        <input @change="onFileLoading" class="form-control" type="file"
                                               :class="errors.spreadsheetFile ? 'is-invalid' : ''"
                                               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                        <div class="invalid-feedback">
                                            {{ errors.spreadsheetFile ? errors.spreadsheetFile[0] : '' }}
                                        </div>
                                    </div>
                                </li>
                            </ol>
                        </div>

                        <div id="generator-import-google" class="tab-pane fade">
                            <ol class="generator-import__steps list-unstyled">
                                <li class="generator-import__step">
                                    <p class="generator-import__step-title h5">1.  Откройте таблицу по ссылке ниже и сделайте копию документа</p>
                                    <div class="generator-import__step-buttons">
                                        <a href="#" class="generator-import__step-btn btn btn-success">
                                            <svg>
                                                <use xlink:href="#svg-plus"></use>
                                            </svg>
                                            Ссылка на файл
                                        </a>
                                        <a href="#" class="generator-import__step-link text-success">Как сделать копию?</a>
                                    </div>
                                </li>
                                <li class="generator-import__step">
                                    <p class="generator-import__step-title h5">2. Заполните данные о товарах</p>
                                    <p class="generator-import__step-text">
                                        Все вкладки и столбцы должны иметь правильную очередность. Если у вас возникли трудности с заполнением, воспользуйтесь <a href="#">инструкцией по работе с таблицей</a>
                                    </p>
                                </li>
                                <li class="generator-import__step">
                                    <p class="generator-import__step-title h5">3. Вставьте ссылку на Гугл-таблицу с заполненными данными</p>
                                    <p class="generator-import__step-text">
                                        Откройте доступ к файлу в правом верхнем углу, чтобы бот смог увидеть таблицу<br>
                                        <a href="#">Как открыть доступ к таблице?</a>
                                    </p>
                                    <input v-model="spreadsheetLink" type="text" class="form-control" placeholder="Ссылка на гугл таблицу">
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>


                <button @click="importSpreadsheet" type="button" class="custom-modal__btn btn btn-primary btn-lg">
                    Создать наклейки
                    <span v-show="isWaiting" class="spinner-grow sr-only" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
//import { Modal } from 'bootstrap'
export default {

    data: ()=>({
        isWaiting: false,
        isFileImport: true,

        spreadsheetFile: null,
        spreadsheetLink: null,

        errors: {},
    }),

    methods: {

        changeImportStatement(state) {
            this.$set(this, 'isFileImport', !!state)
        },

        importSpreadsheet() {

            //
            if(this.isFileImport && !!this.spreadsheetFile) {
                this.importSpreadsheetFile()
            }
            else {
                this.$set(this.errors, 'spreadsheetFile', ['Файл не задан'])
            }

            //
            if(!this.isFileImport && !!this.spreadsheetLink) {

            }
        },

        async importSpreadsheetFile() {
            this.isWaiting = true;

            //
            const data = new FormData();
            data.append('spreadsheetFile', this.spreadsheetFile)

            await axios.post('/spreadsheet/importFile', data)
                .then((res)=>{
                    this.errors = {}

                    //this.$emit('spreadsheetLoaded', res.data)
                    //const modal = new Modal(this.$refs.modal, {})
                    //modal.hide()
                    window.location = res.data
                })
                .catch((err)=>{
                    this.$set(this.errors, 'spreadsheetFile', err.response.data.errors);
                })

            //
            this.isWaiting = false;
        },

        onFileLoading(e) {
            const files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.spreadsheetFile = files[0];
            this.$set(this.errors, 'spreadsheetFile', undefined)
        },
    }
}
</script>

<style>

</style>
