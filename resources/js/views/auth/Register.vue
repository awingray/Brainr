<template>
    <div class="mx-auto w-1/2 md:1/4 lg:w-1/3 mt-12">
        <div class="border shadow-md rounded px-5 py-6 mb-6">
            <b-form action="/register"
                    method="post"
                    :form-data="data"
                    @submitted="submitted"
                    v-slot:default="{data, errors}"
            >
                <div class="mb-5">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">Name
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                           :class="{ 'border-red-500': errors.has('name') }"
                           type="text"
                           name="name"
                           id="name"
                           placeholder="Name"
                           autofocus
                           v-model="data.name"
                    >
                    <span class="text-red-500 text-xs italic" v-if="errors.has('name')"
                          v-text="errors.get('name')"
                    ></span>
                </div>

                <div class="mb-5">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">E-mail
                        Address</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                           :class="{ 'border-red-500': errors.has('email') }"
                           type="email"
                           name="email"
                           id="email"
                           placeholder="E-mail Address"
                           v-model="data.email"
                    >
                    <span class="text-red-500 text-xs italic" v-if="errors.has('email')"
                          v-text="errors.get('email')"
                    ></span>
                </div>

                <div class="mb-5">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                           for="password"
                    >Password</label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        :class="{ 'border-red-500': errors.has('password') }"
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Password"
                        v-model="data.password"
                    >
                    <span class="text-red-500 text-xs italic" v-if="errors.has('password')"
                          v-text="errors.get('password')"
                    ></span>
                </div>

                <div class="mb-5">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                           for="password"
                    >Confirm Password</label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        :class="{ 'border-red-500': errors.has('password_confirmation') }"
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        placeholder="Confirm Password"
                        v-model="data.password_confirmation"
                    >
                    <span class="text-red-500 text-xs italic" v-if="errors.has('password_confirmation')"
                          v-text="errors.get('password_confirmation')"
                    ></span>
                </div>

                <div class="mb-5">
                    <p class="text-sm">By registering you agree to the <a class="text-blue-600 hover:text-blue-500"
                                                                          href="#"
                    >Terms
                        of Service</a> and
                        <a class="text-blue-600 hover:text-blue-500" href="#">Privacy Policy</a></p>
                </div>

                <button
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-light uppercase py-3 px-6 rounded focus:outline-none focus:shadow-outline"
                    type="submit"
                >Sign Up
                </button>
            </b-form>
        </div>

        <div class="border shadow-md rounded p-4">
            <p class="text-center text-md font-light">Already have an account?
                <router-link class="font-light text-md text-blue-600" :to="{ name: 'login' }">Sign in
                </router-link>
            </p>
        </div>

        <p class="text-center text-gray-400 text-sm pt-3">&copy; 2020 Brainr. All rights reserved.</p>
    </div>
</template>

<script>
    export default {
        methods: {
            async submitted()
            {
                await this.$store.dispatch('auth/checkAuth');

                this.$router.push({name: 'dashboard'});
            },
        },
    };
</script>
