<template>
    <div>
        <div v-show="showModal" class="fixed flex bg-smoke top-0 left-0 w-screen h-screen justify-center items-start z-40 overflow-auto" @click.self="close">
            <div class="relative bg-white w-full max-w-md mx-auto mt-32 flex-col rounded flex z-50" ref="messageDiv">
                <div class="bg-secondary-200 p-3 rounded-t">
                    <p class="font-medium text-lg text-primary-600">
                        <slot name="title"></slot>
                    </p>
                    <span class="h-6 w-6 absolute top-0 right-0 mt-2 mr-2" @click="close">
                        <svg class="fill-current text-primary-600 hover:text-primary-800" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </span>
                </div>
                <div class="px-6 py-8">
                    <p class="leading-normal text-secondary-600">
                        <slot></slot>
                    </p>
                </div>
                <div class="flex w-full px-6 py-4 justify-end">
                    <button class="btn mr-3" autofocus @click.prevent="doAction('cancel')">No</button>
                    <button class="btn is-primary" @click.prevent="doAction('delete')">Yes</button>
                </div>
            </div>
        </div>

        <button class="text-gray-600" :class="classList" @click.prevent="showModal = true">
            <svg class="fill-current h-5 w-5 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title v-text="label"></title>
                <path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/>
            </svg>
        </button>
    </div>
</template>

<script>
    export default {
        props: ['classes', 'label', 'path', 'redirectPath'],

        data() {
            return {
                classList: this.classes,
                showModal: false,
            }
        },

        methods: {
            close() {
                this.showModal = false;
            },

            doAction(action) {
                this.close();
                if (action == 'delete') {
                    this.doDelete(); 
                }
            },

            doDelete() {
                axios.delete(this.path)
                    .then(response => {
                        if (response.status == 204) {
                            this.redirect();
                        }
                    })
                    .catch(function(error) {
                        if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            console.log('Data', error.response.data);
                            console.log('Status', error.response.status);
                            console.log('Headers', error.response.headers);
                        } else if (error.request) {
                            // The request was made but no response was received
                            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                            // http.ClientRequest in node.js
                            console.log('Request', error.request);
                        } else {
                            // Something happened in setting up the request that triggered an Error
                            console.log('Error', error.message);
                        }
                        console.log('Config', error.config);
                    });
            },

            redirect() {
                window.location.href = this.redirectPath;
            }
        }
    }
</script>