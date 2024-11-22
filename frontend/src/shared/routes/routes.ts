type RouteType = {
	path: string,
	getUrl: () => string,
}

const LoginRoute: RouteType = {
	path: '/login',
	getUrl: () => LoginRoute.path,
}

const UserPortalRoute: RouteType = {
	path: '/portal',
	getUrl: () => UserPortalRoute.path,
}

export {
	LoginRoute,
	UserPortalRoute,
}