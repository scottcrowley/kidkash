<template>
    <div class="dropdown relative">
        <div class="flex items-center justify-between p-2">
            <p class="text-xl" v-text="title"></p>
            <div>
                <svg class="fill-current h-6 w-6 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" @click.prevent="isOpen = ! isOpen">
                    <path v-if="! isOpen" d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zM2 10a8 8 0 1 0 16 0 8 8 0 0 0-16 0zm10.54.7L9 14.25l-1.41-1.41L10.4 10 7.6 7.17 9 5.76 13.24 10l-.7.7z"/>
                    <path v-else d="M20 10a10 10 0 1 1-20 0 10 10 0 0 1 20 0zM10 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm-.7 10.54L5.75 9l1.41-1.41L10 10.4l2.83-2.82L14.24 9 10 13.24l-.7-.7z"/>
                </svg>
            </div>
        </div>

        <div v-show="isOpen" class="border-t px-3 py-4">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            title: { 
                default: '' 
            },
            openDefault: {
                default: false 
            }
        },
        data() {
            return { isOpen: this.openDefault }
        },
        methods: {
            
        }
    }
</script>