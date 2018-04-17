<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            Notifications
        </a>

        <div class="dropdown-menu">
            <a class="dropdown-item"
               v-for="notification in notifications"
               v-text="notification.data.message"
               :href="notification.data.link"
               @click="markAsRead(notification)"
            >
            </a>
        </div>
    </li>
</template>

<script>
    export default {
        data() {
            return {notifications: false}
        },

        created() {
            axios.get('/profiles/' + window.App.user.name + '/notifications')
                .then(response => this.notifications = response.data);
        },

        methods: {
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id )
            }
        }
    }
</script>