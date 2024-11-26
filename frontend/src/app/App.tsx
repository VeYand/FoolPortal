import {ConfigProvider} from 'antd'
import {Preloader} from 'features/preloader/Preloader'
import {Route, Routes} from 'react-router-dom'
import {useInitializeUser} from 'shared/libs/hooks'
import {LoginRoute, UserPortalRoute, ProfileRoute} from 'shared/routes'
import {LoginPage, UserPortalPage, NotFoundPage, ProfilePage} from '../pages'
import {globalTheme} from './globalTheme'

const App = () => {
	const {isLoading} = useInitializeUser()

	if (isLoading) {
		return <Preloader/>
	}

	return (
		<ConfigProvider theme={globalTheme}>
			<Routes>
				<Route path={LoginRoute.path} element={<LoginPage/>}/>
				<Route path={UserPortalRoute.path} element={<UserPortalPage/>}/>
				<Route path={ProfileRoute.path} element={<ProfilePage/>}/>
				<Route path="*" element={<NotFoundPage/>}/>
			</Routes>
		</ConfigProvider>
	)
}

export {
	App,
}