<template>
    <div class="counter">
        <button type="button" class="counter__minus"
            :disabled="value <= 1"
            @click="$emit('input', --data, row, cell)"
        >
            <svg><use xlink:href="#svg-counter-minus"></use></svg>
        </button>
        <input type="number" class="counter__input form-control"
            :value="value"
            @input="$emit('input', data=(+$event.target.value), row, cell)"
            @change="onChange"
        >
        <button type="button" class="counter__plus"
            @click="$emit('input', ++data, row, cell)"
        >
            <svg><use xlink:href="#svg-counter-plus"></use></svg>
        </button>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: Number,
            default: 1,
        },
        row: {
            type: Number,
            default: 1,
        },
        cell: {
            type: Number,
            default: 1,
        }
    },
    data:()=>({
        data: 0,
    }),
    beforeMount() {
        this.data = this.value
    },

    watch: {
        value: function (newVal, oldVal) {
            this.data = newVal
        },
    },

    methods: {
        onChange(e) {
            if (!e.target.value || e.target.value < 1) {
                this.$emit('input', 1);
            }
        }
    },
}
</script>

<style>

</style>
