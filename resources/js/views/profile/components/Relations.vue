<template>
    <div class="rounded border-2 px-3 py-2 mb-3">
        <h2 class="text-2xl font-bold mb-3">Relations</h2>

        <b-api :url="profileRelationsUrl" v-slot:default="{data:relations}" ref="relations">
            <div v-if="relations.length" class="mb-3">

                <div v-for="relation in relations" :key="relation.id">
                    <p class="break-words">
                        {{ relation.name }}
                        <router-link class="text-blue-700"
                                     :to="{ name: 'profiles.show', params:{ profile: relation.other.id } }"
                                     v-text="relation.other.name"
                        ></router-link>
                        <span class="text-red-700 cursor-pointer"
                              @click="deleteRelation(relation)"
                        >Delete</span>
                    </p>
                </div>
            </div>
            <div v-else class="mb-3">
                <p>No relations</p>
            </div>
        </b-api>

        <b-api :url="`${profileRelationsUrl}/unrelated`" v-slot:default="{data:profiles}" ref="profiles">
            <b-form :action="profileRelationsUrl"
                    method="post"
                    @submitted="submitted"
                    v-slot:default="{data, errors}"
            >
                <input class="appearance-none block w-full text-sm bg-gray-200 text-gray-700 border border-gray-200 rounded p-2 mb-2 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                       type="text"
                       name="name"
                       placeholder="Name"
                       v-model="data.name"
                >
                <p class="text-red-500 text-xs italic" v-if="errors.has('name')"
                   v-text="errors.get('name')"
                ></p>

                <select class="w-full mb-2 appearance-none block w-full text-sm bg-gray-200 text-gray-700 border border-gray-200 rounded p-2 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        name="to"
                        v-model="data.to"
                >
                    <option value="" disabled selected
                            v-text="profiles.length ? 'Select profile' : 'No profiles'"
                    ></option>
                    <option v-for="profile in profiles" :value="profile.id">{{ profile.name }}</option>
                </select>
                <p class="text-red-500 text-xs italic" v-if="errors.has('to')"
                   v-text="errors.get('to')"
                ></p>

                <button
                    class=" w-full inline-block text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit"
                >Add relation
                </button>
            </b-form>
        </b-api>
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
            profileRelationsUrl()
            {
                return `/api/profiles/${ this.profile }/relations`;
            },
        },
        methods: {
            submitted()
            {
                this.$refs.relations.loadData();
                this.$refs.profiles.loadData();
            },
            deleteRelation(relation)
            {
                brainr.delete(`${ this.profileRelationsUrl }/${ relation.id }`).
                    then(this.submitted).
                    catch(() => alert('Failed to delete relation'));
            },
        },
    };
</script>
