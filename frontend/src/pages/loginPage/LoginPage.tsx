import {Box} from '@mui/material'
import {Authorization} from 'features/authorization/authorization'
import {Preloader} from 'features/preloader/Preloader'
import {useEffect} from 'react'
import {useNavigate} from 'react-router-dom'
import {useAppSelector} from 'shared/redux'
import {UserPortalRoute} from 'shared/routes'

const LoginPage = () => {
	const navigate = useNavigate()
	const {loading, initialized} = useAppSelector(state => state.userEntity)

	useEffect(() => {
		if (!loading && initialized) {
			navigate(UserPortalRoute.path)
		}
	}, [loading, initialized, navigate])

	if (loading) {
		return <Preloader/>
	}

	return (
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
}

export {
	LoginPage,
}