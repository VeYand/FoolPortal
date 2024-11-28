import {Header} from 'widgets/header/Header'
import {Tabs} from 'widgets/tabs/Tabs'
import {useTabs} from './model/useTabs'

const UserPortalPage = () => {
	const tabs = useTabs()

	return (
		<>
			<Header/>
			<Tabs tabs={tabs}/>
		</>
	)
}

export {UserPortalPage}
