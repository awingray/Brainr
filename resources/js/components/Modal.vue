<template>
    <div class="fixed w-full h-full top-0 left-0 flex items-center justify-center z-10"
         v-if="active"
    >
        <div class="container md:max-w-md mx-auto mx-auto z-20">
            <div class="bg-white rounded shadow-lg z-50 overflow-y-auto">

                <div class="relative py-4 text-left px-6">
                    <slot name="header">
                        <div class="flex justify-between items-center pb-3">
                            <p class="text-xl font-bold" v-text="title"></p>
                            <div class="cursor-pointer" @click="close">
                                <svg class="fill-current text-black"
                                     xmlns="http://www.w3.org/2000/svg"
                                     width="18"
                                     height="18"
                                     viewBox="0 0 18 18"
                                >
                                    <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                                </svg>
                            </div>
                        </div>
                    </slot>

                    <slot></slot>

                </div>

            </div>
        </div>

        <div class="absolute w-full h-full bg-gray-900 opacity-50"></div>
    </div>
</template>

<script>
    export default {
        data()
        {
            return {
                active: false,
            };
        },
        props: {
            title: {
                type: String,
            },
        },
        watch: {
            active(val)
            {
                val ? this.$emit('opened') : this.$emit('closed');
            },
        },
        methods: {
            open()
            {
                this.active = true;
            },
            close()
            {
                this.active = false;
            },
        },
    };
</script>
