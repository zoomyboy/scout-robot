module.exports = {
	install: function(Vue, options) {
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
						console.log(this.user.usergroup.rights);
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

			options.mount.$mount(options.to);
		});

	}
};
