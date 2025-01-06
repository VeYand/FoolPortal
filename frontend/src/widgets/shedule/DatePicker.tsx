import {DatePicker as AntdDatePicker, ConfigProvider} from 'antd'
import locale from 'antd/locale/ru_RU'
import moment from 'moment'
import {useState} from 'react'

type DatePickerProps = {
	onSelectedDateChange: (startDate: Date, endDate: Date) => void,
}

const DatePicker = (props: DatePickerProps) => {
	const [selectedWeek, setSelectedWeek] = useState<moment.Moment | undefined>(undefined)

	const handleWeekChange = (date?: moment.Moment) => {
		if (date && !date.isSame(selectedWeek, 'week')) {
			const startOfWeek = date.clone().startOf('week')
			const endOfWeek = date.clone().endOf('week')

			setSelectedWeek(date)
			props.onSelectedDateChange(startOfWeek.toDate(), endOfWeek.toDate())
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