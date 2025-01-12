import {ConfigProvider} from 'antd'
import locale from 'antd/locale/ru_RU'
import dayjs from 'dayjs'
import 'dayjs/locale/ru'
import moment from 'moment'
import {useEffect} from 'react'
import {Route, Routes} from 'react-router-dom'
import {useInitializeUser} from 'shared/libs/hooks'
import {LoginRoute, UserPortalRoute, ProfileRoute} from 'shared/routes'
import {Preloader} from 'widgets/preloader/Preloader'
import {LoginPage, UserPortalPage, NotFoundPage, ProfilePage} from '../pages'
import {globalTheme} from './globalTheme'

dayjs.locale('ru')
moment.updateLocale('ru', {
	week: {
		dow: 1,
		doy: 4,
	},
})

const App = () => {
	const {isLoading, initialize} = useInitializeUser()
	useEffect(initialize, [initialize])

	if (isLoading) {
		return <Preloader/>
	}

	return (
		<ConfigProvider theme={globalTheme} locale={locale}>
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