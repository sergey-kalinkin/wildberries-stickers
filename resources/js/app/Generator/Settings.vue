<template>
    <div id="modal-generator-settings" class="custom-modal modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="custom-modal__content">
                <button type="button" class="custom-modal__close" data-bs-dismiss="modal">
                    <svg><use xlink:href="#svg-close"></use></svg>
                </button>
                <p class="h2">Настройка полей</p>
                <div class="custom-modal__text">
                    <p>ℹ️  После выбора дополнительных полей они появятся в таблице для заполнения.</p>
                    <p>🚨  Учитывайте размер вашей наклейки при добавлении полей. Наклейка не резиновая</p>
                </div>

                <div class="custom-modal__checks">
                    <p class="custom-modal__checks-title fw-bold">Дополнительные поля:</p>
                    <div class="custom-modal__checks-wrap">
                        <div class="form-check"
                            v-for="(field, index) in fields"
                            :key="index"
                        >
                            <input class="form-check-input" type="checkbox"
                                v-model="field.checked"
                                @change="save"
                                :id="`generator-settings-field-${index}`"
                            >
                            <label class="form-check-label"
                                :for="`generator-settings-field-${index}`"
                            >
                                {{ field.title }}
                            </label>
                            <button
                                type="button"
                                class="custom-modal__check-delete"
                                v-if="field.custom"
                                @click="deleteField(index)"
                            >
                                <svg>
                                    <use xlink:href="#svg-close"></use>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="custom-modal__checks-add">
                        <input type="text" class="form-control" v-model="newFieldTitle" placeholder="Введите название">
                        <button type="button" class="btn btn-success"
                            @click="appendField"
                        >
                            <svg>
                                <use xlink:href="#svg-plus"></use>
                            </svg>
                            Добавить поле
                        </button>
                    </div>
                </div>

                <button type="button" class="custom-modal__btn btn btn-primary btn-lg" data-bs-dismiss="modal"
                    @click="save"
                >Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    props: {
        params: {
            default: ()=>[],
            type: Array
        },
        excludedFields: {
            type: Array,
            default: ()=>[]
        }
    },

    data() {
        return {
            newFieldTitle: '',
            fields: [],
        }
    },

    beforeMount() {
        for (const field of this.params) {
            this.fields.push({
                title: field,
                checked: !this.excludedFields.includes(field),
            });
        }
    },

    methods: {
        save() {
            const added = [],
                removed = [];

            const uncheckedFields = this.fields
                .filter((field) => !field.checked)
                .map((field) => field.title);

            this.$emit('save', {
                uncheckedFields
            });

            //TODO save on server
        },

        appendField() {
            if (!this.newFieldTitle) return;

            this.fields.push({
                title: this.newFieldTitle,
                checked: true,
                custom: true,
            });

            this.$emit('add', this.newFieldTitle);
            this.newFieldTitle = '';
        },

        deleteField(index) {
            this.fields.splice(index, 1);
            this.$emit('deleteField', index-this.params.length);
        },

        applyChanges() {
            for(const it of this.fields)
                it.custom = false
        }
    }
}
</script>

<style>

</style>
