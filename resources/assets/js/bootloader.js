module.exports = {
	install: function(Vue, options) {
		var loaded = [];

		axios.get('/api/profile').then(function(ret) {
			var User = new Vue({
				data: function() {
					return {
						user: ret.data
					};
				},
				computed: {
					name: function() {return this.user.name;},
					id: function() {return this.user.id;}
				},
				methods: {
					hasRight: function(right) {
						return this.user.usergroup.rights.find(function(r) {
							return r.key == right;
						}) != undefined;
					}
				}
			});

			Object.defineProperty(Vue.prototype, '$user', {
				get: function get() {
					return User;
				}
			});

			loaded = triggerMount(options, 'user', loaded);
		});

		axios.get('/api/conf').then(function(ret) {
			var Config = new Vue({
				data: function() {
					return {
						config: ret.data
					};
				},
				methods: {
					value: function(key) {
						return this.config[0][key];
					}
				}
			});

			Object.defineProperty(Vue.prototype, '$config', {
				get: function get() {
					return Config;
				}
			});

			loaded = triggerMount(options, 'config', loaded);
		});
	}
};

function triggerMount(options, loaded, alreadyLoaded) {
	alreadyLoaded.push(loaded);

	if (alreadyLoaded.indexOf('user') !== -1 &&  alreadyLoaded.indexOf('config') !== -1) {
		options.mount.$mount(options.to);
	}

	return alreadyLoaded;
}

