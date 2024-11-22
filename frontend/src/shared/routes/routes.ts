type RouteType = {
	path: string,
	getUrl: () => string,
}

const LoginRoute: RouteType = {
	path: '/login',
	getUrl: () => '/login',
}

export {
	LoginRoute,
}