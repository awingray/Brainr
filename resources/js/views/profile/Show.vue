<template>
    <b-api :url="profileUrl" v-slot:default="{data:profile}" ref="profile">
        <div class="container mx-auto mt-8">

            <p class="text-gray-500 m-1">
                created
                <date :date="profile.created_at"></date>
                ,
                updated
                <date :date="profile.updated_at"></date>
            </p>

            <div class="mb-3 clearfix">
                <h1 class="font-bold text-4xl float-left" v-text="profile.name"></h1>

                <router-link :to="{name: 'profiles.edit', params: {profile: profile.id}}"
                             class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-3 ml-3 py-1 px-4 rounded focus:outline-none focus:shadow-outline float-left"
                >Edit
                </router-link>

                <b-form :action="profileUrl"
                        method="patch"
                        :formData="{published: !profile.published}"
                        @submitted="$refs.profile.loadData()"
                        v-slot:default="{data}"
                >
                    <input type="hidden" name="published" :value="data.published">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-3 ml-3 py-1 px-4 rounded focus:outline-none focus:shadow-outline float-left"
                            v-text="profile.published ? 'Conceal' : 'Publish'"
                    />
                </b-form>
            </div>

            <div class="mb-3">
                <p class="w-full text-gray-600"
                   v-text="profile.description ? profile.description: 'No description'"
                ></p>
            </div>

            <Tags :profile="$route.params.profile"/>

            <div class="grid grid-cols-4 mb-3">
                <div class="col-span-3">
                    <p class="p-2 w-full whitespace-pre-line"
                       v-text="profile.content ? profile.content: 'No content'"
                    ></p>
                </div>

                <div class="col-span-1">

                    <Relations :profile="$route.params.profile"/>

                    <Contacts :profile="$route.params.profile"/>

                    <Locations :profile="$route.params.profile"/>

                    <Files :profile="$route.params.profile"/>

                </div>

            </div>
        </div>
    </b-api>
</template>

<script>
    import Contacts from './components/Contacts';
    import Locations from './components/Locations';
    import Files from './components/Files';
    import Relations from './components/Relations';
    import Tags from './components/Tags';

    export default {
        components: {
            Contacts,
            Files,
            Relations,
            Tags,
            Locations,
        },
        computed: {
            profileUrl()
            {
                return `/api/profiles/${ this.$route.params.profile }`;
            },
        },
    };
</script>
