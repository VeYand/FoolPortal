import {Header} from 'widgets/header/Header'
import {UserCardWidget} from 'widgets/userCardWidget/UserCardWidget'

const ProfilePage = () => (
	<>
		<Header />
		<div style={{padding: '24px', maxWidth: '600px', margin: '0 auto'}}>
			<UserCardWidget/>
		</div>
	</>
)

export {
	ProfilePage,
}
