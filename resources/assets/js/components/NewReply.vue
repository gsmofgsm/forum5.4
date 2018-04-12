<template>
    <!--@if(auth()->check())-->
    <!--<form method="POST" action="{{ $thread->path() . '/replies' }}">-->
        <!--{{ csrf_field() }}-->
    <div class="form-group">
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
    <!--</form>-->
    <!--@else-->
    <!--<p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>-->
    <!--@endif-->
</template>

<script>
    export default {
        data(){
            return {
                body: '',
                endpoint: '/threads/necessitatibus/15/replies'
            }
        },

        methods: {
            addReply() {
                axios.post(this.endpoint, { body: this.body })
                    .then(response => {
                        this.body = '';
                        flash('Your reply has been posted.');
                        this.$emit('created', response.data);
                    })
            }
        }
    }
</script>