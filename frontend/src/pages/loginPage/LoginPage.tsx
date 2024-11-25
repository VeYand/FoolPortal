import {Layout, Row, Col} from 'antd'
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
		<Layout>
			<Row justify="center">
				<Col>
					<Authorization />
				</Col>
			</Row>
		</Layout>
	)
}

export {
	LoginPage,
}