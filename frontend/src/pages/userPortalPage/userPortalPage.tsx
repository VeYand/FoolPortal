import {Header} from 'widgets/header/Header'
import {Tabs} from 'widgets/tabs/Tabs'
import {getTabs} from './model/getTabs'

const UserPortalPage = () => (
	<>
		<Header/>
		<Tabs tabs={getTabs()}/>
	</>
)

export {UserPortalPage}
