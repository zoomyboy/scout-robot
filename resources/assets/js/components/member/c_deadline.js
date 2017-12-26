import moment from 'moment';

export default function() {
	var now = moment();

	if (!this.config.deadlineunit) {
		return '';
	}

	var units = ['', 'days', 'weeks', 'months', 'years'];

	if (this.config.deadlineunit.id >= 1 && this.config.deadlineunit.id <= 4) {
		return now.add(this.config.deadlinenr, units[this.config.deadlineunit.id]).format('DD.MM.YYYY');
	}

	return '';
};
