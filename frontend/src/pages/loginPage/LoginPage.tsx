import {Box} from '@mui/material'
import {Authorization} from '../../features/authorization/authorization'

const LoginPage = () => (
	<Box
		sx={{
			display: 'flex',
			flexDirection: 'column',
			alignItems: 'center',
			justifyContent: 'center',
			height: '100vh',
			backgroundColor: '#f5f5f5',
		}}>
		<Authorization/>
	</Box>
)

export {
	LoginPage,
}