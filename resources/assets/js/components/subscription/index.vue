<template>
	<div class="cp-fee cp-wrap">
		<grid>
			<article>
				<panel title="Übersicht">
					<div slot="action">
						<buttonbar>
						<v-link route="subscription.add" add size="sm"></v-link>
						</buttonbar>
					</div>
					<v-table
						:border="false"
						v-if="subscription !== false"
						controller="subscription"
						:headings="headings"
						:collection="subscription"
						:deleteaction="false"
	  					scrolling
					>
				
					</v-table>
				</panel>
			</article>
		</grid>
	</div>
</template>

<style lang="less">
	.cp-wrap.cp-fee-index {

	}
</style>

<script>
	export default {
		data: function() {
			return  {
				subscription: [],
				headings: [
					{title: 'Name', data: 'title'},
					{title: 'Lokaler Beitrag', data: 'amount', type: 'money'},
					{title: 'NaMi-Beitrag', data: 'fee[title]'},
				]
			};
		},
		components: {
			panel: require('z-ui/panel/panel.vue'),
			grid: require('z-ui/grid/grid.vue'),
			vTable: require('z-ui/table/table.vue')
		},
		mounted: function() {
			var vm = this;

			axios.get('/api/subscription').then(function(data) {
				vm.subscription = data.data;
			});
		}
	}
</script>
