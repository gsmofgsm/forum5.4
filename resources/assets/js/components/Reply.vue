<template>
    <div class="card" :id="'reply-'+id">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+attributes.owner.name" v-text="attributes.owner.name"></a>
                    said
                    {{ attributes.created_at }}
                    ...
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="attributes"></favorite>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea name="" id="" class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>

        <!--@can('update', $reply)-->
        <div class="card-footer level">
            <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
            <button class="btn btn-xs btn-danger" @click="destroy">Delete</button>
        </div>
        <!--@endcan-->
    </div>

</template>

<script>
    import Favorite from './Favorite.vue';

    export default {
        props: ['attributes'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                body: this.attributes.body,
                id: this.attributes.id
            };
        },

        computed: {
            signedIn: window.App.signedIn
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                });

                this.editing = false;

                flash('Updated!');
            },

            destroy() {
                axios.delete('/replies/' + this.attributes.id);

                this.$emit('deleted', this.attributes.id);
                // $(this.$el).fadeOut(300);
                // flash('Your reply has been deleted!');
            }
        }
    }
</script>

<style>
</style>
