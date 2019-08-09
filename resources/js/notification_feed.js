import VueNativeNotification from 'vue-native-notification'

Nova.booting((Vue, router) => {
    Vue.use(VueNativeNotification, {
        requestOnNotify: true
    })
    Vue.component('notification-feed', require('./components/NotificationFeed'))
    Vue.component('notifications-panel', require('./components/NotificationsPanel'))
    Vue.component('notification-message', require('./components/NotificationMessage'))
})
