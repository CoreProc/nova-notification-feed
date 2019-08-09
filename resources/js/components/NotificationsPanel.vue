<template>
  <div class="text-white notifications-panel">
    <div class="border-b border-80">
      <div class="text-center px-6" id="notifications-panel-close" @click="toggleNotificationsPanel">
        Close
      </div>
    </div>
    <div class="px-4 border-b border-80 overflow-y-scroll h-full">
      <div v-for="notification in notifications">
        <notification-message :notification="notification"></notification-message>
      </div>
      <infinite-loading @infinite="getNotifications"></infinite-loading>
    </div>
  </div>
</template>

<script>
  import InfiniteLoading from 'vue-infinite-loading'

  export default {
    name: 'NotificationsPanel',
    props: [
      'broadcastOn'
    ],
    components: {
      InfiniteLoading,
    },
    data () {
      return {
        notifications: [],
        interval: null,
        currentPage: 0
      }
    },
    methods: {
      toggleNotificationsPanel: function () {
        this.$emit('toggleNotificationsPanel')
      },
      getNotifications: function ($state) {
        this.currentPage += 1
        axios.get('/nova-vendor/nova-notifications/notifications?page=' + this.currentPage).then(response => {
          this.$emit('showUnreadNotificationCount', response.data.meta.unread_count)
          if (response.data.data.length) {
            response.data.data.forEach(i => {
              this.notifications.push(i)
            })
            if ($state !== undefined) {
              $state.loaded()
            }
          } else {
            if($state !== undefined) {
              $state.complete()
            }
          }
          // Assign current page just for redundancy's sake
          this.currentPage = response.data.meta.current_page
        })
      },
      listenForNotifications () {
        window.userPrivateChannel
          .notification((notification) => {
            // Increment the unread count
            this.$emit('incrementUnreadCount')
            // Add the notification to the top
            this.notifications.unshift(notification)
            // Show a toast
            this.$toasted.show(notification.data.message, {type: notification.data.level})
          })
      }
    },
    created () {
      this.getNotifications()
      this.listenForNotifications()
    }
  }
</script>

<style scoped>
  .notifications-panel {
    z-index: 999;
    position: fixed !important;
    right: 0;
    top: 0;
    width: 340px;
    height: 100%;
    background-color: #536170;
    padding-bottom: 70px;
  }

  #notifications-panel-close {
    height: 60px;
    line-height: 60px;
    cursor: pointer;
  }

  #notifications-panel-close:hover {
    background-color: #252D37;
  }
</style>
