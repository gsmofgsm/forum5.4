<template>
    <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
    export default {
        props: ['active'],

        computed: {
            classes() {
                return 'btn ' + (this.active ? 'btn-primary' : 'btn-default');
            }
        },

        methods: {

            subscribe() {
                let action = this.active ? 'delete' : 'post';
                axios[action](location.pathname + '/subscriptions');

                flash('Subscribed');
            }
        }
    }
</script>