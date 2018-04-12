<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :attributes="reply" @deleted="remove(index)" :key="reply.id"></reply>
        </div>

        <new-reply :endpoint="endpoint" @created="add"></new-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';

    export default {
        props: ['data'],

        components: { Reply, NewReply },

        data() {
            return {
                items: this.data
            }
        },

        computed: {
            endpoint() {
                return location.pathname + '/replies';
            }
        },

        methods: {
            add(reply) {
                this.items.push(reply);
                this.$emit('added');
            },

            remove(index) {
                this.items.splice(index, 1);
                this.$emit('removed');
                flash('Reply was deleted.');
            }
        }
    }
</script>