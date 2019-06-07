<div class="cbw-import-content">
	<div class="cbw-body__title"><?php
		_e( 'Importing sample data', 'crocoblock-wizard' );
	?></div>
	<cbw-clear-content
		v-if="'replace' === importType && ready"
		@content-cleared="startImport"
	></cbw-clear-content>
	<cbw-progress :value="progress"></cbw-progress>
	<div class="cbw-import-summary">
		<table>
			<thead>
				<tr>
					<th class="summary-label"><?php _e( 'Import summary', 'crocoblock-wizard' ); ?></th>
					<th class="summary-progress"><?php _e( 'Progress', 'crocoblock-wizard' ); ?></th>
					<th class="summary-total"><?php _e( 'Completed', 'crocoblock-wizard' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr
					v-for="( summaryLabel, summaryKey ) in summaryInfo"
					v-if="summaryTotal[ summaryKey ]"
				>
					<td class="summary-label">{{ summaryLabel }}</td>
					<td class="summary-progress">
						<cbw-progress-alt
							:value="getSummaryProgress( summaryKey )"
						></cbw-progress-alt>
					</td>
					<td class="summary-total">
						<div class="summary-total-content">
							<span>{{ summaryCurrent[ summaryKey ] }}/{{ summaryTotal[ summaryKey ] }}</span>
							<svg v-if="summaryCurrent[ summaryKey ] == summaryTotal[ summaryKey ]"width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.07733 9.48252L14.72 0L16 1.25874L5.07733 12L0 7.00699L1.28 5.74825L5.07733 9.48252Z" fill="#46B450"/></svg>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>