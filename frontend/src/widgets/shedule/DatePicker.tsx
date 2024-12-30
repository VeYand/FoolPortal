import {DatePicker as AntdDatePicker} from 'antd'
import moment from 'moment/moment'
import {useState} from 'react'

type DatePickerProps = {
	onSelectedDateChange: (startDate: Date, endDate: Date) => void,
}

const DatePicker = (props: DatePickerProps) => {
	const [selectedWeek, setSelectedWeek] = useState<moment.Moment>(moment())

	const handleWeekChange = (date?: moment.Moment) => {
		if (date && selectedWeek !== date) {
			setSelectedWeek(date)
			props.onSelectedDateChange(
				date.startOf('week').toDate(),
				date.endOf('week').toDate(),
			)
		}
	}

	return (
		<AntdDatePicker
			picker="week"
			value={selectedWeek}
			onChange={handleWeekChange}
			style={{marginBottom: 20}}
		/>
	)
}

export {
	DatePicker,
}