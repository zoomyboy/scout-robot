import Echo from 'laravel-echo';
import ioc from 'socket.io-client';

export default {
    methods: {
        listenOnChannels: function() {
            var store = this.$store;

            window.Echo = new Echo({
                broadcaster: 'socket.io',
                host: '127.0.0.1:6001'
            });

            window.Echo.channel('import')
                .listen('Import\\MemberCreated', function (e) {
                    console.log(e);
                    store.commit('startProcess', {
                        name: 'memberImported',
                        amount: e.progress,
                        message: 'Mitglied importiert: '+e.member.firstname + ' ' + e.member.lastname
                    });
                })
                .listen('Import\\MemberUpdated', function (e) {
                    console.log(e);
                    store.commit('startProcess', {
                        name: 'memberImported',
                        amount: e.progress,
                        message: 'Mitglied aktualisiert: '+e.member.firstname + ' ' + e.member.lastname
                    });
                })
            ;
        }
    }
}
