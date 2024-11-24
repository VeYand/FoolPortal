export type ApiWrapper<API> = {
	init: () => void,
	get: () => API,
}