module.exports = {
	install: function(Vue, options) {
		var loaded = [];

		axios.get('/api/info').then(function(ret) {
			//------------------------------- user --------------------------------
			var User = new Vue({
				data: function() {
					return {
						user: ret.data.user
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

			var Config = new Vue({
				data: function() {
					return {
						config: ret.data.conf
					};
				},
				methods: {
					value: function(key) {
						return this.config[key];
					}
				}
			});

			Object.defineProperty(Vue.prototype, '$config', {
				get: function get() {
					return Config;
				}
			});

			options.mount.$mount(options.to);
		});
	}
};
