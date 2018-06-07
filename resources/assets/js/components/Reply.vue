<template>
    <div class="card" :id="'reply-'+id" :class="isBest ? 'bg-success' : ''">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+attributes.owner.name" v-text="attributes.owner.name"></a>
                    said
                    <span v-text="ago"></span>
                    ...
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="attributes"></favorite>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <textarea name="" id="" class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-xs btn-primary">Update</button>
                    <button type="button" class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>

        <!--@can('update', $reply)-->
        <div class="card-footer level" v-if="authorize('updateReply', reply)">
            <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
            <button class="btn btn-xs btn-danger" @click="destroy">Delete</button>
        </div>

        <button class="btn btn-xs btn-default ml-auto" @click="markBestReply" v-if="authorize('updateThread', reply.thread)" v-show="! isBest">Best Reply?</button>
        <!--@endcan-->
    </div>

</template>

<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';

    export default {
        props: ['attributes'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                body: this.attributes.body,
                id: this.attributes.id,
                isBest: this.attributes.isBest,
                reply: this.attributes
            };
        },

        computed: {
            ago() {
                return moment(this.attributes.created_at).fromNow() + '...';
            }
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                })
                .then(() => {

                    this.editing = false;

                    flash('Updated!');

                })
                .catch(error => {
                    flash(error.response.data, 'danger');
                });
            },

            destroy() {
                axios.delete('/replies/' + this.attributes.id);

                this.$emit('deleted', this.attributes.id);
                // $(this.$el).fadeOut(300);
                // flash('Your reply has been deleted!');
            },

            markBestReply() {
                this.isBest = true;

                axios.post('/replies/' + this.attributes.id + '/best');

                window.events.$emit('best-reply-selected', this.attributes.id);
            }
        }
    }
</script>

<style>
</style>
