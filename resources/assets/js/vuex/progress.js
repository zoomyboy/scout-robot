export default {
    state: {
        running: []
    },
    mutations: {
        startProcess(state, data) {
            var runningProcess = state.running.filter((p) => {
                return p.name == data.name;
            });

            if (runningProcess.length) {
                state.running = state.running.map((p) => {
                    if (p.name == data.name) {return data;}
                    return p;
                });
            } else {
                state.running.push(data);
            }

            if (data.amount == 100) {
                this.commit('killProcess', data.name);
            }
        },
        killProcess(state, data) {
            state.running = state.running.filter((p) => {
                return p.name != data;
            });
        }
    },
    getters: {
        firstRunning(state) {
            return (state.running.length > 0)
                ? state.running[0]
            : null;
        }
    }
}
