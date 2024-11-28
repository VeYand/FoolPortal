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

const ProfileRoute: RouteType = {
	path: '/profile',
	getUrl: () => ProfileRoute.path,
}

export {
	LoginRoute,
	UserPortalRoute,
	ProfileRoute,
}