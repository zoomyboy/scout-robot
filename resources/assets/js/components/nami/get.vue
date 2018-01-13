<template>
	<div class="cp-wrap cp-nami-get">
		<grid>
			<article>
				<panel title="Mitglieder abrufen">
					<v-link @click="sync" icon="refresh">Synchronisieren</v-link>
				</panel>
			</article>
		</grid>
	</div>
</template>

<style lang="less">
	.cp-wrap.cp-nami-get {

	}
</style>

<script>
	export default {
		data: function() {
			return  {

			};
		},
		components:  {
			grid: require('z-ui/grid/grid.vue'),
			panel: require('z-ui/panel/panel.vue'),
		},
		methods: {
			sync: function() {
				axios.post('/api/nami/getmembers').then(function() {

				});
			}
		},
		mounted: function() {

		}
	}
</script>
