const getCsrfToken = () => {
	const element = document.getElementById('_csrf_token') as HTMLInputElement | undefined
	return element?.value ?? ''
}

export {
	getCsrfToken,
}