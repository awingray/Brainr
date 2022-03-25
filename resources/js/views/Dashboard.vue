<template>
    <div class="container mx-auto mt-8">

        <!--        Notification-->

        <div class="mb-3 clearfix">
            <h1 class="font-bold text-4xl float-left">Profiles</h1>
            <router-link :to="{name: 'profiles.create'}"
                         class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-3 ml-3 py-1 px-4 rounded focus:outline-none focus:shadow-outline float-left"
            >New profile
            </router-link>
        </div>

        <div class="grid grid-cols-4">
            <b-api url="/api/profiles" v-slot:default="{data: profiles}" ref="profiles">
                <div class="col-span-3 grid grid-cols-3 gap-3">
                    <div v-if="!profiles.length">No profiles</div>

                    <div class="" v-else v-for="profile in profiles">
                        <div class="border-2 rounded">
                            <router-link :to="{name:'profiles.show', params:{profile: profile.id}}"
                                         class="inline-block text-2xl text-blue-700 font-bold mx-3 my-2"
                            >{{ profile.name }}
                            </router-link>

                            <div class="m-3 mt-0">
                                <p v-text="profile.description"></p>
                            </div>

                            <div class="m-3 mt-0">
                                <p class="text-gray-600">created
                                    <date :date="profile.created_at"></date>
                                </p>
                                <p class="text-gray-600">updated
                                    <date :date="profile.updated_at"></date>
                                </p>
                            </div>
                            <div class="bg-gray-100 border-t px-3 py-2">
                                <router-link :to="{name: 'profiles.edit', params: {profile: profile.id}}"
                                             class="text-blue-600"
                                >Edit
                                </router-link>
                                <button class="text-red-700 ml-1" @click="deleteProfile(profile)">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </b-api>


            <div class="col-span-1">
                <h2 class="border-b-2 px-1 font-bold text-xl">Favorites</h2>

                <b-api url="/api/favorites" v-slot:default="{data: favorites}">
                    <ul class="list-disc list-inside p-2">
                        <li v-if="favorites.length" v-for="favorite in favorites">
                            <router-link :to="{ name: 'profile', params: {profile: favorite.id}}"
                                         class="text-blue-700"
                                         v-text="favorite.name"
                            ></router-link>
                        </li>

                        <li v-else>No favorites</li>
                    </ul>
                </b-api>
            </div>
        </div>

    </div>
</template>

<script>
    import brainr from '../apis/brainr';

    export default {
        methods: {
            deleteProfile(profile)
            {
                if (confirm(`Are you sure you want to delete the profile '${ profile.name }'?`))
                {
                    brainr.delete(`/api/profiles/${ profile.id }`).
                        then(() => this.$refs.profiles.loadData());
                }
            },
        },
    };
</script>
