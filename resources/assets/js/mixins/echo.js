import Echo from 'laravel-echo';
import ioc from 'socket.io-client';

export default {
    methods: {
        listenOnChannels: function() {
            var store = this.$store;

            window.Echo = new Echo({
                broadcaster: 'socket.io',
                host: document.querySelector('meta[name="socket_host"]').getAttribute('content')
            });

            window.Echo.channel('import')
                .listen('Import\\MemberCreated', function (e) {
                    store.commit('startProcess', {
                        name: 'memberImported',
                        amount: e.progress,
                        message: 'Mitglied importiert: '+e.member.firstname + ' ' + e.member.lastname
                    });
                })
                .listen('Import\\MemberUpdated', function (e) {
                    store.commit('startProcess', {
                        name: 'memberImported',
                        amount: e.progress,
                        message: 'Mitglied aktualisiert: '+e.member.firstname + ' ' + e.member.lastname
                    });
                })
            ;

            window.Echo.channel('notification')
                .listen('MemberCancelled', function(e) {
                    store.commit('member/destroy', {
                        id: e.memberId
                    });
                });
        }
    }
}
