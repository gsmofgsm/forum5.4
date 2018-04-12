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
        components: { Reply, NewReply },

        data() {
            return {
                dataSet: false,
                items: []
            }
        },

        created() {
            this.fetch();
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
            },

            fetch() {
                axios.get(this.url())
                    .then(this.refresh);
            },

            url() {
                return location.pathname + '/replies';
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
            }
        }
    }
</script>