<template>
    <div class="alert alert-flash" :class="'alert-'+level" role="alert" v-show="show">
        {{ body }}
    </div>
</template>

<script>
    export default {
        props: ['message'],

        data() {
            return {
                body: '',
                level: 'success',
                show: false
            }
        },

        created() {
            if (this.message) {
                this.flash(this.message);
            }

            window.events.$on('flash', data => {
                this.flash(data);
            });
        },

        methods: {
            flash(data) {
                if(data.message) {
                    this.body = data.message;
                    this.level = data.level;
                } else {
                    this.body = data;
                }
                this.show = true;

                this.hide();
            },

            hide() {
                setTimeout(()=> {
                    this.show = false;
                }, 3000);
            }
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 105px;
    }
</style>
