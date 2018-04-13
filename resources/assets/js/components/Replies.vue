<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :attributes="reply" @deleted="remove(index)" :key="reply.id"></reply>
        </div>

        <paginator :dataSet="dataSet"></paginator>

        <new-reply :endpoint="endpoint" @created="add"></new-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';
    import collection from '../mixins/collection';

    export default {
        components: { Reply, NewReply },

        data() {
            return {
                dataSet: false,
            }
        },

        mixins: [collection],

        created() {
            this.fetch();
        },

        computed: {
            endpoint() {
                return location.pathname + '/replies';
            }
        },

        methods: {
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