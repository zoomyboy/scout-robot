<template>
	<div class="cp-wrap cp-member-payment">
		<v-table v-if="payments" :url="url" :border="false" controller="member" :headings="[{title: 'Year', data: 'nr'}, {title: 'Status', 'data': 'status[title]'}]" :editaction="false" :deleteaction="false" :actions="actions">
			
		</v-table>
	</div>
</template>

<style lang="less">
	.cp-wrap.cp-member-payment {

	}
</style>

<script>
	export default {
		data: function() {
			return  {
				payments: [],
				actions: [
					{'icon': 'edit', 'event': 'edit'}
				]
			};
		},
		props: {
			member: {
				required: true,
				default: false
			}
		},
		watch: {
			member: function(newVal, oldVal) {
				this.loadmember();
			}
		},
		components: {
			vTable: function(resolve) {
				require(['z-ui/table.vue'], resolve);
			}
		},
		computed: {
			url: function() {
				return '/api/member/'+this.member.id+'/payments';
			}
		},
		methods: {
			loadmember: function() {
				var vm = this;
			}
		},
		mounted: function() {
			this.loadmember();
		}
	}
</script>
