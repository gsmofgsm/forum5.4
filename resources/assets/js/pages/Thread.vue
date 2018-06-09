<template>

</template>

<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';

    export default {
        props: ['dataRepliesCount', 'dataLocked'],

        data() {
            return {
                repliesCount: this.dataRepliesCount,
                locked: this.dataLocked
            }
        },

        components: { Replies, SubscribeButton }
    }
</script>