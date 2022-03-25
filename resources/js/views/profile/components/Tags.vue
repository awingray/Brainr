<template>
    <b-api :url="profileTagsUrl" v-slot:default="{data: tags}" ref="tags">
        <div class="mb-4">
            <div class="bg-green-200 rounded px-2 mr-2 inline-block mb-2"
                 v-for="tag in tags"
            >
                <span class="text-sm py-1" v-text="tag.name"></span>
                <svg class="inline-block cursor-pointer"
                     @click="deleteTag(tag)"
                     xmlns="http://www.w3.org/2000/svg"
                     width="14"
                     height="14"
                     viewBox="0 0 18 18"
                >
                    <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                </svg>
            </div>

            <div class="inline-block mb-2">
                <b-form :action="profileTagsUrl"
                        method="post"
                        @submitted="$refs.tags.loadData()"
                        v-slot:default="{data, errors}"
                >
                    <div class="flex">
                        <input class="appearance-none block w-32 bg-gray-200 text-gray-700 border border-gray-200 rounded-l py-1 px-2 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 text-sm"
                               type="text"
                               name="name"
                               placeholder="Tag"
                               v-model="data.name"
                        >
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-sm text-white py-1 px-2 rounded-r focus:outline-none focus:shadow-outline"
                            type="submit"
                        >
                            Add
                        </button>
                    </div>

                    <p class="text-red-500 text-xs italic" v-if="errors.has('name')"
                       v-text="errors.get('name')"
                    ></p>
                </b-form>
            </div>
        </div>
    </b-api>
</template>

<script>
    import brainr from '../../../apis/brainr';

    export default {
        props: {
            profile: {
                required: true,
            },
        },
        computed: {
            profileTagsUrl()
            {
                return `/api/profiles/${ this.profile }/tags`;
            },
        },
        methods: {
            deleteTag(tag)
            {
                brainr.delete(`${ this.profileTagsUrl }/${ tag.name }`).
                    then(() => this.$refs.tags.loadData()).
                    catch(() => alert('Failed to delete tag'));
            },
        },
    };
</script>
