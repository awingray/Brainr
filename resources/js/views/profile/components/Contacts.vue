<template>
    <div class="rounded border-2 px-3 py-2 mb-3">
        <h2 class="text-2xl font-bold mb-3">Contacts</h2>

        <b-api :url="profileContactsUrl" v-slot:default="{data:contacts}" ref="contacts">
            <div class="mb-3">

                <div v-for="contact in contacts" :key="contact.id">
                    <p class="break-words">
                        <a v-if="contact.type === 'website'" :href="contact.detail" target="_blank" class="text-blue-600">
                            {{ contact.detail }}
                        </a>
                        <a v-else-if="contact.type === 'email'" :href="`mailto:${contact.detail}`" target="_blank" class="text-blue-600">
                            {{ contact.detail }}
                        </a>
                        <a v-else-if="contact.type === 'phone'" :href="`tel:${contact.detail}`" target="_blank" class="text-blue-600">
                            {{ contact.detail }}
                        </a>
                        <span v-else>
                            {{ contact.detail }}
                        </span>
                        ({{ contact.type }})
                        <a class="text-red-700"
                           href="#"
                           @click.prevent="deleteContact(contact)"
                        >Delete</a>
                    </p>
                </div>

                <p v-if="!contacts.length">No contacts</p>
            </div>
        </b-api>

        <!-- Create contact form -->
        <button class="w-full inline-block text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded focus:outline-none focus:shadow-outline"
                @click="$refs.create.open()"
        >Create contact
        </button>
        <Modal ref="create" title="Create contact">
            <b-form :action="profileContactsUrl"
                    method="post"
                    @submitted="onCreate"
                    v-slot:default="{data, errors}"
            >

                <div class="w-full">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                           for="contact-type"
                    >
                        Type
                    </label>
                    <select class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="contact-type"
                            v-model="data.type"
                    >
                        <option v-for="(name, type) in contactTypes" :value="type" v-text="name"></option>
                    </select>

                    <p class="text-red-500 text-xs italic" v-if="errors.has('type')"
                       v-text="errors.get('type')"
                    ></p>
                </div>

                <div class="w-full">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                           for="contact-detail"
                    >
                        Detail
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                           id="contact-detail"
                           type="text"
                           placeholder="Detail"
                           v-model="data.detail"
                    >

                    <p class="text-red-500 text-xs italic" v-if="errors.has('detail')"
                       v-text="errors.get('detail')"
                    ></p>
                </div>

                <div class="w-full">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit"
                    >
                        Create
                    </button>
                    <a href="#"
                       class="bg-gray-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block focus:outline-none focus:shadow-outline"
                       @click.prevent="$refs.create.close()"
                    >
                        Cancel
                    </a>
                </div>
            </b-form>
        </Modal>
        <!-- End create contact form -->
    </div>
</template>

<script>
    import Modal from '../../../components/Modal';
    import brainr from '../../../apis/brainr';

    export default {
        data()
        {
            return {
                contactTypes: {
                    website: 'Website',
                    email: 'Email address',
                    phone: 'Phone number',
                },
            };
        },
        components: {
            Modal,
        },
        props: {
            profile: {
                required: true,
            },
        },
        computed: {
            profileContactsUrl()
            {
                return `/api/profiles/${ this.profile }/contacts`;
            },
        },
        methods: {
            onCreate()
            {
                this.$refs.contacts.loadData();
                this.$refs.create.close();
            },
            onUpdate()
            {
                this.$refs.contacts.loadData();
                // this.$refs.update.close();
            },
            deleteContact(contact)
            {
                if (confirm('Are you sure?'))
                {
                    brainr.delete(`${ this.profileContactsUrl }/${ contact.id }`).
                        then(() =>
                        {
                            this.$refs.contacts.loadData();
                        }).
                        catch(() =>
                        {
                            alert('Failed to delete contact');
                        });
                }
            },
        },
    };
</script>
