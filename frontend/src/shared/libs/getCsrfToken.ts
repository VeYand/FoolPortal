const getCsrfToken = () => document.getElementById('_csrf_token')?.textContent ?? ''

export {
	getCsrfToken,
}