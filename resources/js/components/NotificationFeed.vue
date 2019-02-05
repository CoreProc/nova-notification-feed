<template>
  <div>
    <div @click="toggleNotificationsPanel" class="cursor-pointer notification-dropdown text-center notification-button" style="width:40px;">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
        <path class="heroicon-ui" d="M15 19a3 3 0 0 1-6 0H4a1 1 0 0 1 0-2h1v-6a7 7 0 0 1 4.02-6.34 3 3 0 0 1 5.96 0A7 7 0 0 1 19 11v6h1a1 1 0 0 1 0 2h-5zm-4 0a1 1 0 0 0 2 0h-2zm0-12.9A5 5 0 0 0 7 11v6h10v-6a5 5 0 0 0-4-4.9V5a1 1 0 0 0-2 0v1.1z"/>
      </svg>
      <div class="badge" v-show="unreadCount > 0">
        {{ unreadCount }}
      </div>
    </div>
    <div v-show="isNotificationsPanelVisible">
      <notifications-panel
        @toggleNotificationsPanel="toggleNotificationsPanel"
        @showUnreadNotificationCount="showUnreadNotificationCount"
        @incrementUnreadCount="incrementUnreadCount"
        v-bind:broadcast-on="this.broadcastOn">
      </notifications-panel>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'NovaNotifications',
    props: [
      'pusherKey',
      'pusherCluster',
      'broadcastOn'
    ],
    data () {
      return {
        isNotificationsPanelVisible: false,
        unreadCount: 0,
      }
    },
    methods: {
      toggleNotificationsPanel: function () {
        this.isNotificationsPanelVisible = !this.isNotificationsPanelVisible

        // Mark unread count as 0 when notifications panel is opened
        if (this.isNotificationsPanelVisible) {
          axios.get('/nova-vendor/nova-notifications/notifications?mark_as_read=1').then(response => {
            this.unreadCount = 0
          })
        }
      },
      showUnreadNotificationCount: function (unreadCount) {
        this.unreadCount = unreadCount
      },
      incrementUnreadCount: function () {
        this.unreadCount += 1
      }
    }
  }
</script>

<style scoped>
  .notification-button {
    color: white;
    display: inline-block; /* Inline elements with width and height. TL;DR they make the icon buttons stack from left-to-right instead of top-to-bottom */
    position: relative; /* All 'absolute'ly positioned elements are relative to this one */
  }

  .badge {
    background-color: #fa3e3e;
    border-radius: 2px;
    color: white;

    padding: 1px 3px;
    font-size: 10px;

    position: absolute;
    top: 0;
    right: 5px;
  }
</style>
