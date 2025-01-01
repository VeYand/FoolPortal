const formatDateToISO = (date: Date) => {
	const localDateString = date.toLocaleString('sv-SE', {timeZone: 'Europe/Moscow'})
	const [datePart, timePart] = localDateString.split(' ')
	const [timeWithoutMillis] = timePart?.split('.') ?? ['']
	return `${datePart}T${timeWithoutMillis}Z`
}

export {
	formatDateToISO,
}