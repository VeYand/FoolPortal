const formatStartTime = (startTime: number) => {
	const hours = Math.floor(startTime / 60)
	const minutes = Math.floor(startTime - hours * 60)
	return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`
}

export {
	formatStartTime,
}