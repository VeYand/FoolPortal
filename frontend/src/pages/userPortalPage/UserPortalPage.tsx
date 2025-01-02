import {useAppSelector} from 'shared/redux'
import {Header} from 'widgets/header/Header'
import {Tabs} from 'widgets/tabs/Tabs'
import {useTabs} from './model/useTabs'

const UserPortalPage = () => {
	const currentUserRole = useAppSelector(state => state.userEntity.user.role)
	const tabs = useTabs(currentUserRole)

	return (
		<>
			<Header/>
			<Tabs tabs={tabs}/>
		</>
	)
}

export {UserPortalPage}
