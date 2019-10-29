<template>
    <div class="relative h-64 w-48 mb-6 mx-auto">
        <div class="h-full w-full overflow-hidden rounded-lg shadow-lg">
            <img :src="avatarPath" :alt="name" class="w-full h-full object-cover" />
        </div>
        <div v-if="canUpdate" class="absolute h-full top-0 w-full" @click.prevent.self="bgClick">
            <div class="">
                <div class="absolute bg-gray-800 bg-white bottom-0 cursor-pointer mb-2 mr-2 px-2 py-1 right-0 rounded text-gray-100 shadow-lg" @click.prevent.stop="toggleMenu">
                    <svg class="h-3 w-3 inline-block fill-current align-baseline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/></svg>
                    <span>Edit</span>
                </div>
                <div v-if="isOpen" class="absolute bg-white bottom-0 leading-snug left-0 mb-3 ml-2 py-1 rounded text-sm border border-gray-400">
                    <a href="#" class="block px-2 py-1" @click.prevent="fileInput.click()">Upload new image</a>
                    <a v-if="avatar != ''" href="#" class="block px-2 py-1" @click.prevent="deleteImage">Delete image</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['currentAvatarPath', 'id', 'name', 'isKid', 'userId'],

        data() {
            return {
                avatar: this.currentAvatarPath,
                isOpen: false,
                fileInput: null,
                selectedFile: null,
            }
        },

        created() {
            this.listenForEsc();
            this.setFileInput();
        },

        computed: {
            avatarPath() {
                return '/' + ((this.avatar != '') ? this.avatar : 'avatars/default.jpg');
            }
        },

        methods: {
            canUpdate() {
                return ! this.isKid || this.id == this.userId;
            },

            listenForEsc() {
                const handleEscape = (e) => {
                    if ((e.key === 'Esc' || e.key === 'Escape') && this.isOpen) {
                        this.toggleMenu();
                    }
                }
                document.addEventListener('keydown', handleEscape);
                this.$once('hook:beforeDestroy', () => {
                    document.removeEventListener('keydown', handleEscape)
                });
            },

            setFileInput() {
                this.fileInput = document.createElement('input');
                this.fileInput.type = 'file';

                this.fileInput.onchange = e => { 
                    this.onChange(e);
                }
            },

            bgClick(e) {
                if (this.isOpen) {
                    this.toggleMenu();
                }
            },

            toggleMenu() {
                this.isOpen = ! this.isOpen;
            },

            onChange(e) {
                let file = e.target.files[0];

                let reader = new FileReader();

                reader.readAsDataURL(file);

                reader.onload = e => {
                    this.persist(file);
                };
            },

            persist(avatar) {
                let data = new FormData;

                data.append('avatar', avatar);

                axios.post(`/api/users/${this.id}/avatar`, data)
                    .then(response => {
                        this.avatar = response.data['avatar_path'];
                        this.isOpen = false;
                        flash('Avatar Uploaded!');
                    });
            },
            
            deleteImage() {
                axios.delete(`/api/users/${this.id}/avatar`)
                    .then(response => {
                        this.avatar = '';
                        this.isOpen = false;
                        flash('Avatar Deleted!');
                    });
            }
        }
    }
</script>