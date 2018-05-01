<template>
    <div>
        <h1 v-text="user.name"></h1>

        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <image-upload @loaded="onLoad"></image-upload>
        </form>

        <img :src="avatar" alt="profile_image" width="50" height="50">
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue';

    export default {
        props: ['user'],
        components: { ImageUpload },

        data() {
            return {
                avatar: this.user.avatar_path
            };
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id)
            }
        },

        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;
                this.persist(avatar.file);
            },

            persist(avatar) {
                let data = new FormData();

                data.append('avatar', avatar);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded'));
            }
        }
    }
</script>