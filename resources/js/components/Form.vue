<template>
    <form @submit.prevent="submit" @keydown="errors.clear($event.target.name)">
        <slot v-bind:data="data" v-bind:errors="errors"></slot>
    </form>
</template>

<script>
    import brainr from '../apis/brainr';
    import Errors from '../utils/errors';

    export default {
        props: {
            formData: {
                type: Object,
                default: () => ({}),
            },
            method: {
                type: String,
                default: 'post',
            },
            action: {
                type: String,
            },
        },
        data()
        {
            return {
                data: Object.assign({}, this.formData),
                errors: new Errors(),
            };
        },
        methods: {
            reset()
            {
                this.data = Object.assign({}, this.formData);
                this.errors.clearAll();
            },

            submit()
            {
                if (this.$listeners.submit)
                {
                    this.$emit('submit', this.data, this);
                }
                else
                {
                    return brainr[this.method](this.action, this.data).then((response) =>
                    {
                        this.reset();

                        this.$emit('submitted', response.data);
                    }).catch((error) =>
                    {
                        this.errors.record(error.response.data);

                        this.$emit('errors', error.response);
                    });
                }
            },
        },
    };
</script>
