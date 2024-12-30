import {DatePicker as AntdDatePicker} from 'antd'
import moment from 'moment/moment'
import {useState} from 'react'

type DatePickerProps = {
	onSelectedDateChange: (startDate: string, endDate: string) => void,
}

function formatDateToISO(date: Date): string {
	const offset = -date.getTimezoneOffset()
	const sign = offset >= 0 ? '+' : '-'
	const pad = (num: number): string => String(num).padStart(2, '0')

	const hoursOffset = Math.floor(Math.abs(offset) / 60)
	const minutesOffset = Math.abs(offset) % 60

	return (
		date.getFullYear()
		+ '-'
		+ pad(date.getMonth() + 1)
		+ '-'
		+ pad(date.getDate())
		+ 'T'
		+ pad(date.getHours())
		+ ':'
		+ pad(date.getMinutes())
		+ ':'
		+ pad(date.getSeconds())
		+ sign
		+ pad(hoursOffset)
		+ ':'
		+ pad(minutesOffset)
	)
}

const DatePicker = (props: DatePickerProps) => {
	const [selectedWeek, setSelectedWeek] = useState<moment.Moment>(moment())
	console.log(selectedWeek)
	const handleWeekChange = (date?: moment.Moment) => {
		if (date && selectedWeek !== date) {
			setSelectedWeek(date)
			props.onSelectedDateChange(
				formatDateToISO(date.startOf('week').toDate()),
				formatDateToISO(date.endOf('week').toDate()),
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
	formatDateToISO,
}