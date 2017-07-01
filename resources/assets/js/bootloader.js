module.exports = {
	install: function(Vue, options) {
		axios.get('/api/profile/view').then(function(ret) {
			var User = new Vue({
				data: function() {
					return {
						user: ret.data
					};
				},
				computed: {
					name: function() {return this.user.name;},
					id: function() {return this.user.id;}
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
