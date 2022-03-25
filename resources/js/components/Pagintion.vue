<template>
    <ul class="t-pagination table border-collapse">
        <li class="table-cell border border-gray-300 w-12 h-12">
            <button class="flex w-full h-full items-center justify-center font-bold bg-white text-blue-500 hover:text-blue-700 hover:bg-gray-200"
                    @click="browse(1)"
            >‹
            </button>
        </li>

        <li class="table-cell border border-gray-300 w-12 h-12" v-for="page in pages">
            <button class="flex w-full h-full items-center justify-center font-bold bg-white text-blue-500 hover:text-blue-700 hover:bg-gray-200"
                    v-text="page"
                    @click="browse(page)"
            ></button>
        </li>

        <li class="table-cell border border-gray-300 w-12 h-12">
            <button class="flex w-full h-full items-center justify-center font-bold bg-white text-blue-500 hover:text-blue-700 hover:bg-gray-200"
                    @click="browse(data.last_page)"
            >›
            </button>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            value: {
                type: Number,
            },
            data: {
                type: Object,
                required: true,
            },
        },
        computed: {
            pages()
            {
                return Array.from(Array(this.data.last_page + 1).keys()).splice(1);
            },
        },
        methods: {
            browse(page)
            {
                this.$emit('input', page);
            },
        },
    };
</script>
