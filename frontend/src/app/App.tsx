import {Preloader} from 'features/preloader/Preloader'
import {Route, Routes} from 'react-router-dom'
import {useInitializeUser} from 'shared/libs/hooks'
import {LoginRoute, UserPortalRoute} from 'shared/routes'
import {LoginPage, UserPortalPage, NotFoundPage} from '../pages'

const App = () => {
	const {isLoading} = useInitializeUser()

	if (isLoading) {
		return <Preloader/>
	}

	return (
		<Routes>
			<Route path={LoginRoute.path} element={<LoginPage/>}/>
			<Route path={UserPortalRoute.path} element={<UserPortalPage/>}/>
			<Route path="*" element={<NotFoundPage/>}/>
		</Routes>
	)
}

export {
	App,
}