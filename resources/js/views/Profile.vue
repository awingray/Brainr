<template>
    <b-api :url="profileUrl" v-slot:default="{data: profile}">
        <div class="container mx-auto mt-6">
            <button
                class="bg-teal-500 hover:bg-teal-700 text-white font-bold px-2 mb-3 rounded focus:outline-none focus:shadow-outline"
                @click="$router.back()"
            >
                <svg class="fill-current text-white inline-block mr-1 -mt-1"
                     width="20"
                     height="20"
                     viewBox="0 0 20 20"
                >
                    <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"/>
                </svg>
                <span class="inline-block py-1">Back</span>
            </button>

            <div class="clearfix">
                <h1 class="text-4xl font-bold float-left" v-text="profile.name"></h1>

                <auth>
                    <b-api :url="favoriteUrl"
                           v-slot:default="{data: {favorite}}"
                           ref="favorite"
                    >
                        <b-form :action="favoriteUrl"
                                method="put"
                                @submitted="$refs.favorite.loadData()"
                        >
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-3 ml-3 px-3 rounded focus:outline-none focus:shadow-outline float-left"
                            >
                                <svg class="fill-current text-white inline-block mr-1 -mt-1"
                                     xmlns="http://www.w3.org/2000/svg"
                                     width="16"
                                     height="16"
                                     viewBox="0 0 20 20"
                                >
                                    <path v-if="favorite"
                                          d="M17.684,7.925l-5.131-0.67L10.329,2.57c-0.131-0.275-0.527-0.275-0.658,0L7.447,7.255l-5.131,0.67C2.014,7.964,1.892,8.333,2.113,8.54l3.76,3.568L4.924,17.21c-0.056,0.297,0.261,0.525,0.533,0.379L10,15.109l4.543,2.479c0.273,0.153,0.587-0.089,0.533-0.379l-0.949-5.103l3.76-3.568C18.108,8.333,17.986,7.964,17.684,7.925z"
                                    />
                                    <path v-else
                                          d="M17.684,7.925l-5.131-0.67L10.329,2.57c-0.131-0.275-0.527-0.275-0.658,0L7.447,7.255l-5.131,0.67C2.014,7.964,1.892,8.333,2.113,8.54l3.76,3.568L4.924,17.21c-0.056,0.297,0.261,0.525,0.533,0.379L10,15.109l4.543,2.479c0.273,0.153,0.587-0.089,0.533-0.379l-0.949-5.103l3.76-3.568C18.108,8.333,17.986,7.964,17.684,7.925 M13.481,11.723c-0.089,0.083-0.129,0.205-0.105,0.324l0.848,4.547l-4.047-2.208c-0.055-0.03-0.116-0.045-0.176-0.045s-0.122,0.015-0.176,0.045l-4.047,2.208l0.847-4.547c0.023-0.119-0.016-0.241-0.105-0.324L3.162,8.54L7.74,7.941c0.124-0.016,0.229-0.093,0.282-0.203L10,3.568l1.978,4.17c0.053,0.11,0.158,0.187,0.282,0.203l4.578,0.598L13.481,11.723z"
                                    />

                                </svg>
                                <span class="inline-block py-1" v-text="favorite ? 'Unfavorite' : 'Favorite'"></span>
                            </button>
                        </b-form>
                    </b-api>
                </auth>
            </div>

            <p class="text-gray-800" v-text="profile.description"></p>

            <div class="my-2">
                <span class="text-gray-700">Tags:</span>
                <router-link class="bg-green-200 rounded px-2 mr-2 inline-block mb-2"
                             v-for="tag in profile.tags"
                             :key="tag.name"
                             :to="{ name: 'home', query: { q: tag.name } }"
                             v-text="tag.name"
                ></router-link>
            </div>


            <div class="grid grid-cols-4 mb-3">
                <div class="col-span-3">
                    <p class="p-2 whitespace-pre-line" v-text="profile.content ? profile.content : 'No content'"></p>
                </div>
                <div class="col-span-1">

                    <!-- Relations -->
                    <div class="mb-3">
                        <h2 class="border-b-2 px-1 font-bold text-xl">Relations</h2>
                        <div class="px-3 py-1">
                            <p v-for="relation in profile.relations">
                                <span v-text="relation.name"></span>
                                &gt;
                                <router-link class="text-blue-700"
                                             :to="{ name: 'profile', params: { profile: relation.other.id } }"
                                             v-text="relation.other.name"
                                ></router-link>
                            </p>
                        </div>
                    </div>

                    <!-- Contacts -->
                    <div class="mb-3">
                        <h2 class="border-b-2 px-1 font-bold text-xl">Relations</h2>
                        <div class="px-3 py-1">
                            <p v-for="contact in profile.contacts">
                                <a v-if="contact.type === 'website'"
                                   :href="contact.detail"
                                   target="_blank"
                                   class="text-blue-600"
                                   v-text="contact.detail"

                                ></a>
                                <a v-else-if="contact.type === 'email'"
                                   :href="`mailto:${contact.detail}`"
                                   target="_blank"
                                   class="text-blue-600"
                                   v-text="contact.detail"
                                ></a>
                                <a v-else-if="contact.type === 'phone'"
                                   :href="`tel:${contact.detail}`"
                                   target="_blank"
                                   class="text-blue-600"
                                   v-text="contact.detail"
                                ></a>
                                <span v-else v-text="contact.detail"></span>
                                ({{ contact.type }})
                            </p>
                        </div>
                    </div>

                    <!-- Files -->
                    <div class="mb-3">
                        <h2 class="border-b-2 px-1 font-bold text-xl">Files</h2>
                        <div class="px-3 py-2">
                            <p v-for="file in profile.files">
                                <a :href="`/${file.path}`"
                                   target="_blank"
                                   class="text-blue-600 underline break-words"
                                   v-text="file.filename"
                                ></a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </b-api>
</template>

<script>
    export default {
        computed: {
            profileUrl()
            {
                return `/api/search/${ this.$route.params.profile }`;
            },
            favoriteUrl()
            {
                return `/api/favorites/${ this.$route.params.profile }`;
            },
        },
    };
</script>
