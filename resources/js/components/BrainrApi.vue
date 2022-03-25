<script>
    import brainr from '../apis/brainr';

    export default {
        props: {
            url: {
                type: String,
                required: true,
            },
            params: {
                type: Object,
                default: () => ({}),
            },
        },
        watch: {
            url()
            {
                this.loadData();
            },
            params()
            {
                this.loadData();
            },
        },
        data()
        {
            return {
                loaded: false,
                response: null,
                error: null,
            };
        },
        methods: {
            loadData()
            {
                brainr.get(this.url, {params: this.params}).
                    then(response => this.response = response).
                    catch(e => this.error = e).
                    finally(() => this.loaded = true);
            },
        },
        mounted()
        {
            this.loadData();
        },
        render(h)
        {
            if (!this.loaded)
            {
                return this.$slots.loading
                    ? this.$slots.loading[0] :
                    h('p', 'Loading');
            }

            if (this.error)
            {
                return h('p', this.error.message);
            }

            return this.$scopedSlots.default({
                data: this.response.data,
            });
        },
    };
</script>
