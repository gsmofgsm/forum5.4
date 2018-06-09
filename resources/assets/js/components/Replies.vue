<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :reply="reply" @deleted="remove(index)" :key="reply.id"></reply>
        </div>

        <paginator :dataSet="dataSet" @updated="fetch"></paginator>

        <new-reply :endpoint="endpoint" @created="add" v-if="! $parent.locked"></new-reply>
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
            fetch(page) {
                axios.get(this.url(page))
                    .then(this.refresh);
            },

            url(page) {
                if(! page){
                    let query = location.search.match(/page=(\d+)/);
                    page = query ? query[1] : 1;
                }
                return location.pathname + '/replies?page=' + page;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;

                window.scrollTo(0, 0);
            }
        }
    }
</script>