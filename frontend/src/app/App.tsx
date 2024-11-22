import {Route, Routes} from 'react-router-dom'
import {LoginPage, UserPortalPage, NotFoundPage} from '../pages'
import {LoginRoute, UserPortalRoute} from '../shared/routes'

const App = () => (
	<Routes>
		<Route path={LoginRoute.path} element={<LoginPage />} />
		<Route path={UserPortalRoute.path} element={<UserPortalPage />} />
		<Route path="*" element={<NotFoundPage />} />
	</Routes>
)

export {
	App,
}