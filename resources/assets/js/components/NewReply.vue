<template>
    <div class="form-group" v-if="signedIn">
        <textarea name="body"
                  id="body"
                  class="form-control"
                  placeholder="Have something to say?"
                  rows="5"
                  required
                  v-model="body"></textarea>
        <button type="button"
                class="btn btn-default"
                @click="addReply">Post</button>
    </div>
    <p class="text-center" v-else>
        Please <a href="/login">Sign in</a> to participate in this discussion.
    </p>
</template>

<script>
    import 'at.js';
    import 'jquery.caret';

    export default {
        props: ['endpoint'],

        data(){
            return {
                body: ''
            }
        },

        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks: {
                    remoteFilter: function(query, callback) {
                        console.log('called');
                        $.getJSON("/api/users", {name: query}, function(usernames){
                            callback(usernames)
                        });
                    }
                }
            });
        },

        methods: {
            addReply() {
                axios.post(this.endpoint, { body: this.body })
                    .then(response => {
                        this.body = '';
                        flash('Your reply has been posted.');
                        this.$emit('created', response.data);
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
            }
        }
    }
</script>