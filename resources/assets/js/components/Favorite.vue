<template>
    <button type="submit" :class="classes" @click="toggle">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="favoritesCount"></span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],

        data() {
            return {
                favoritesCount: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited
            }
        },

        computed: {
            classes() {
                return ['btn', this.isFavorited ? 'btn-primary' : 'btn-default'];
            }
        },

        methods: {
            toggle() {
                if (this.isFavorited) {
                    axios.delete('/replies/' + this.reply.id + '/favorites');
                    this.isFavorited = false;
                    this.favoritesCount--;
                } else {
                    axios.post('/replies/' + this.reply.id + '/favorites');
                    this.isFavorited = true;
                    this.favoritesCount++;
                }
            }
        }
    }
</script>