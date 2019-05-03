import { checkConditions } from '../../mixins/check-conditions';

const ListTable = {
	name: 'cx-vui-list-table',
	template: '#cx-vui-list-table',
	mixins: [ checkConditions ],
	props: {
		conditions: {
			type: Array,
			default() {
				return [];
			}
		},
	},
};

export default ListTable;