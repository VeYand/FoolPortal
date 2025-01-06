import {DatePicker as AntdDatePicker, ConfigProvider} from 'antd'
import locale from 'antd/locale/ru_RU'
import moment from 'moment'
import {useState} from 'react'

type DatePickerProps = {
	onSelectedDateChange: (startDate: Date, endDate: Date) => void,
}

const DatePicker = (props: DatePickerProps) => {
	const [selectedWeek, setSelectedWeek] = useState<moment.Moment>(moment().startOf('isoWeek'))

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
		<ConfigProvider locale={locale}>
			<AntdDatePicker
				picker="week"
				value={selectedWeek}
				onChange={handleWeekChange}
				style={{marginBottom: 20}}
			/>
		</ConfigProvider>
	)
}

export {
	DatePicker,
}