<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Contacts</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Email</th>
                                <th scope="col">Sticky Phone Number ID</th>
                                <th scope="col">More info</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="contact in parsedContacts">
                                <td>{{ contact.name }}</td>
                                <td>{{ contact.phone }}</td>
                                <td>{{ contact.email }}</td>
                                <td>{{ contact.sticky_phone_number_id }}</td>
                                <td><a href="#" @click.prevent="getInfo(contact)" > <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.134 13.134 0 0 0 1.66 2.043C4.12 11.332 5.88 12.5 8 12.5c2.12 0 3.879-1.168 5.168-2.457A13.134 13.134 0 0 0 14.828 8a13.133 13.133 0 0 0-1.66-2.043C11.879 4.668 10.119 3.5 8 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.133 13.133 0 0 0 1.172 8z"/>
                                    <path fill-rule="evenodd" d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg></a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <custom-attributes-modal v-if="showModal" :contact="contactToShow" @close="closeModal()"></custom-attributes-modal>
    </div>
</template>

<script>
import CustomAttributesModal from "./CustomAttributesModal";
export default {
    components: { CustomAttributesModal },
    props: {
        contacts: {
            type: String,
            default: {}
        },
    },
    data() {
        return {
            parsedContacts: JSON.parse(this.contacts),
            contactToShow: {},
            showModal: false
        }
    },
    methods: {
        getInfo(contact) {
            this.contactToShow = contact
            this.showModal = true
        },
        closeModal() {
            this.contactToShow = {}
            this.showModal = false
        }
    },
}
</script>

<style>

</style>
