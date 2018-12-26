export default {
    state: {
        members: []
    },
    namespaced: true,
    getters: {
        mappedMembers: (state) => {
            return state.members.map((member) => {
                member.present = {
                    strikes: member.strikes
                        ? (member.strikes / 100).toFixed(2).replace('.', ',')+' €'
                        : '---'
                };

                return member;
            });
        }
    },
    mutations: {
        getmembers(store) {
            axios.get('/api/member/table').then((data) => {
                store.members = data.data;
            });
        },
        cancel(store, member) {
            axios.get('/api/member/'+member.id+'/cancel');
        },
        destroy(store, member) {
            store.members = store.members.filter((m) => {
                return m.id !== member.id;
            });
        }
    }
}
