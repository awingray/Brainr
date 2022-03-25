<template>
    <div class="rounded border-2 px-3 py-2 mb-3">
        <h2 class="text-2xl font-bold mb-3">Files</h2>

        <b-api :url="profileFilesUrl" v-slot:default="{data:files}" ref="profileFiles">
            <div class="mb-3">

                <div v-if="files.length" v-for="file in files" :key="file.id">
                    <p class="break-words">
                        <a :href="`/${file.path}`"
                           target="_blank"
                           class="text-blue-600"
                           v-text="file.filename"
                        ></a>
                        <span class="text-gray-500" v-text="file.filesize"></span>
                    </p>
                    <div>
                        <a @click="deleteFile(file)"
                           class="text-red-700"
                           href="#"
                        >
                            Delete
                        </a>
                    </div>
                </div>

                <p v-else>No files</p>
            </div>
        </b-api>

        <form @submit.prevent="" ref="fileForm">
            <label class="cursor-pointer">
                <span class="w-full inline-block text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded focus:outline-none focus:shadow-outline"
                >Upload files</span>
                <input type="file" class="hidden" name="files[]" multiple @change="fieldChange">
            </label>
        </form>
    </div>
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
            profileFilesUrl()
            {
                return `/api/profiles/${ this.profile }/files`;
            },
        },
        methods: {
            fieldChange()
            {
                const formData = new FormData(this.$refs.fileForm);

                brainr.post(this.profileFilesUrl, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() =>
                {
                    this.$refs.profileFiles.loadData();
                    alert('Files uploaded');
                }).catch(() =>
                {
                    alert('Failed to upload files');
                });

            },
            deleteFile(file)
            {
                if (confirm('Are you sure?'))
                {
                    brainr.delete(`${ this.profileFilesUrl }/${ file.id }`).
                        then(() =>
                        {
                            this.$refs.profileFiles.loadData();
                        }).
                        catch(() =>
                        {
                            alert('Failed to delete file');
                        });
                }
            },
        },
    };
</script>
